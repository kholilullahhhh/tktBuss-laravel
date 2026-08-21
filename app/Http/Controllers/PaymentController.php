<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    public function pay(Request $request, Booking $booking)
    {
        if (! auth()->user()->isAdmin() && $booking->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke booking ini.');
        }

        if ($booking->status_pembayaran === 'paid') {
            return redirect()->route('booking.ticket', $booking->id)->with('info', 'Booking ini sudah lunas.');
        }

        $payment = $this->paymentService->createPayment($booking);
        $snap = $this->paymentService->generateSnapToken($booking);
        $clientKey = config('services.midtrans.client_key');
        $snapUrl = $snap['token'] ?? null;

        return view('pages.booking.payment', compact('booking', 'payment', 'clientKey', 'snapUrl'));
    }

    /**
     * Webhook Midtrans (public, CSRF exception). Status pembayaran hanya
     * diubah melalui callback ini atau konfirmasi manual admin.
     */
    public function notification(Request $request)
    {
        $payload = $request->input();

        if (empty($payload)) {
            abort(400, 'Empty payload.');
        }

        try {
            $this->paymentService->handleNotification($payload);
        } catch (\Throwable $e) {
            report($e);

            return response()->json(['status' => 'error'], 500);
        }

        return response('ok', 200);
    }
}
