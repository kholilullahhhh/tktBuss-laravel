<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Buat (atau ambil) catatan pembayaran untuk sebuah booking.
     */
    public function createPayment(Booking $booking): Payment
    {
        if ($booking->payment) {
            return $booking->payment;
        }

        return Payment::create([
            'booking_id' => $booking->id,
            'order_id' => 'BUS-'.$booking->id.'-'.strtoupper(Str::random(6)),
            'gross_amount' => $booking->total_harga,
            'payment_status' => 'unpaid',
        ]);
    }

    public function isConfigured(): bool
    {
        return filled(config('services.midtrans.server_key'));
    }

    protected function apiBaseUrl(): string
    {
        return config('services.midtrans.is_production', false)
            ? 'https://app.midtrans.com'
            : 'https://app.sandbox.midtrans.com';
    }

    /**
     * Generate Snap token Midtrans. Mengembalikan null jika Midtrans tidak
     * dikonfigurasi (fallback ke pembayaran manual / konfirmasi admin).
     */
    public function generateSnapToken(Booking $booking): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $payment = $this->createPayment($booking);

        $itemDetails = $booking->seats->map(fn ($s) => [
            'id' => $s->seat_id,
            'price' => (int) round((float) $s->harga),
            'quantity' => 1,
            'name' => 'Tiket Bus - Kursi '.($s->seat->nomor_kursi ?? ''),
        ])->values()->all();

        $body = [
            'transaction_details' => [
                'order_id' => $payment->order_id,
                'gross_amount' => (int) round((float) $booking->total_harga),
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email' => $booking->user->email,
                'phone' => $booking->user->phone,
            ],
        ];

        try {
            $client = new Client;
            $response = $client->post($this->apiBaseUrl().'/snap/v1/transactions', [
                'auth' => [config('services.midtrans.server_key'), ''],
                'json' => $body,
                'timeout' => 20,
            ]);

            $data = json_decode((string) $response->getBody(), true);

            $payment->update([
                'payment_status' => 'pending',
                'transaction_status' => $data['status_code'] ?? 'pending',
                'raw_response' => $data,
            ]);

            $booking->update(['status_pembayaran' => 'pending']);

            return [
                'token' => $data['token'] ?? null,
                'redirect_url' => $data['redirect_url'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('Midtrans snap token error: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Proses notifikasi/webhook Midtrans. Status pembayaran hanya diubah
     * melalui callback ini (bukan tombol dari frontend).
     */
    public function handleNotification(array $payload): void
    {
        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;

        $serverKey = config('services.midtrans.server_key');
        if ($serverKey) {
            $expected = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);
            if (! $signatureKey || ! hash_equals($expected, $signatureKey)) {
                Log::warning('Midtrans notification signature mismatch', ['order_id' => $orderId]);
                abort(403, 'Invalid signature.');
            }
        }

        $payment = Payment::where('order_id', $orderId)->first();
        if (! $payment) {
            Log::warning('Midtrans notification for unknown order', ['order_id' => $orderId]);

            return;
        }

        $payment->update([
            'transaction_id' => $payload['transaction_id'] ?? null,
            'payment_type' => $payload['payment_type'] ?? null,
            'gross_amount' => $grossAmount ?? $payment->gross_amount,
            'transaction_status' => $payload['transaction_status'] ?? null,
            'raw_response' => $payload,
        ]);

        $transactionStatus = (string) ($payload['transaction_status'] ?? '');

        match ($transactionStatus) {
            'capture', 'settlement' => $this->markPaid($payment),
            'pending' => $this->markPending($payment),
            'deny', 'cancel', 'expire', 'failure' => $this->markFailed($payment),
            default => null,
        };
    }

    public function markPending(Payment $payment): void
    {
        $payment->update([
            'payment_status' => 'pending',
            'transaction_status' => 'pending',
        ]);
        $payment->booking->update(['status_pembayaran' => 'pending']);
    }

    public function markFailed(Payment $payment): void
    {
        $payment->update([
            'payment_status' => 'failed',
            'transaction_status' => 'failed',
        ]);
        $payment->booking->update([
            'status_booking' => 'cancelled',
            'status_pembayaran' => 'failed',
        ]);
    }

    /**
     * Tandai pembayaran lunas (dipanggil oleh callback Midtrans atau
     * konfirmasi manual admin). Booking menjadi confirmed & tiket aktif.
     */
    public function markPaid(Payment $payment, ?string $paymentType = null): void
    {
        $booking = $payment->booking;

        $payment->update([
            'payment_status' => 'paid',
            'transaction_status' => 'settlement',
            'paid_at' => now(),
        ]);

        if ($paymentType) {
            $payment->update(['payment_type' => $paymentType]);
        }

        $booking->update([
            'status_booking' => 'confirmed',
            'status_pembayaran' => 'paid',
            'payment_method' => $payment->payment_type ?? ($paymentType ?? 'manual'),
            'paid_at' => now(),
            'expired_at' => null,
        ]);
    }
}
