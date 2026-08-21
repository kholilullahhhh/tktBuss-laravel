<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingService;
use App\Services\TicketService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
        protected TicketService $ticketService
    ) {}

    /**
     * Pastikan user hanya bisa mengakses booking miliknya (atau admin).
     */
    protected function authorizeAccess(Booking $booking): Booking
    {
        if (! auth()->user()->isAdmin() && $booking->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke booking ini.');
        }

        return $booking->load('user', 'schedule.bus.operator', 'schedule.route.terminalAsal', 'schedule.route.terminalTujuan', 'seats.seat', 'payment');
    }

    public function show(Booking $booking)
    {
        $booking = $this->authorizeAccess($booking);

        return view('pages.booking.show', compact('booking'));
    }

    public function cancel(Request $request, Booking $booking)
    {
        $this->authorizeAccess($booking);

        try {
            $this->bookingService->cancel($booking);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('customer.bookings')->with('success', 'Booking berhasil dibatalkan.');
    }

    public function ticket(Booking $booking)
    {
        $booking = $this->authorizeAccess($booking);

        if (! $booking->isPaid()) {
            abort(403, 'Tiket hanya tersedia setelah pembayaran lunas.');
        }

        $ticket = $this->ticketService->ticketData($booking);
        $qr = $this->ticketService->qrSvg($booking->kode_booking);

        return view('pages.booking.ticket', compact('booking', 'ticket', 'qr'));
    }

    public function downloadPdf(Booking $booking)
    {
        $booking = $this->authorizeAccess($booking);

        if (! $booking->isPaid()) {
            abort(403, 'Tiket hanya tersedia setelah pembayaran lunas.');
        }

        return $this->ticketService->downloadPdf($booking);
    }
}
