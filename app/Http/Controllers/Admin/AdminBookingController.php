<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
        protected PaymentService $paymentService
    ) {}

    public function index(Request $request)
    {
        $query = Booking::with('user', 'schedule.bus.operator', 'schedule.route.terminalAsal', 'schedule.route.terminalTujuan')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status_booking', $request->status);
        }

        if ($request->filled('q')) {
            $query->where('kode_booking', 'like', '%'.$request->q.'%');
        }

        $data = $query->get();

        return view('pages.admin.bookings.index', compact('data'));
    }

    public function show(int $id)
    {
        $booking = Booking::with('user', 'schedule.bus.operator', 'schedule.route.terminalAsal', 'schedule.route.terminalTujuan', 'seats.seat', 'payment')
            ->findOrFail($id);

        return view('pages.admin.bookings.show', compact('booking'));
    }

    /**
     * Ubah status booking/pembayaran dari sisi admin.
     */
    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status_booking' => ['required', 'in:pending,confirmed,cancelled,completed'],
            'status_pembayaran' => ['nullable', 'in:unpaid,pending,paid,failed,expired'],
        ]);

        $booking = Booking::findOrFail($id);

        if ($request->status_pembayaran === 'paid' && $booking->status_pembayaran !== 'paid') {
            $payment = $booking->payment ?? $this->paymentService->createPayment($booking);
            $this->paymentService->markPaid($payment, 'manual');
            $booking = $booking->fresh();
        } elseif ($request->status_pembayaran === 'failed') {
            $booking->payment?->update(['payment_status' => 'failed']);
            $booking->update(['status_pembayaran' => 'failed', 'status_booking' => 'cancelled']);
        } else {
            $booking->update([
                'status_booking' => $request->status_booking,
                'status_pembayaran' => $request->status_pembayaran ?? $booking->status_pembayaran,
            ]);
        }

        return redirect()->route('admin.bookings.show', $booking->id)
            ->with('success', 'Status booking berhasil diperbarui!');
    }
}
