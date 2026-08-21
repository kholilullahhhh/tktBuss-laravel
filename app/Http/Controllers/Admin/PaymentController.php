<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    public function index(Request $request)
    {
        $query = Payment::with('booking.user', 'booking.schedule.bus')->latest();

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        $data = $query->get();

        return view('pages.admin.payments.index', compact('data'));
    }

    public function show(int $id)
    {
        $payment = Payment::with('booking.user', 'booking.schedule.bus.operator', 'booking.seats.seat')->findOrFail($id);

        return view('pages.admin.payments.show', compact('payment'));
    }

    public function markPaid(Request $request, int $id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->booking->status_pembayaran === 'paid') {
            return back()->with('warning', 'Pembayaran sudah berstatus lunas.');
        }

        $this->paymentService->markPaid($payment, 'manual');

        return back()->with('success', 'Pembayaran dikonfirmasi lunas. Tiket kini aktif.');
    }

    public function markFailed(Request $request, int $id)
    {
        $payment = Payment::findOrFail($id);

        $this->paymentService->markFailed($payment);

        return back()->with('success', 'Pembayaran ditandai gagal.');
    }
}
