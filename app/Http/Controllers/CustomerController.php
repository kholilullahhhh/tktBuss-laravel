<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected function userBookings()
    {
        return Booking::where('user_id', auth()->id())
            ->with('schedule.bus.operator', 'schedule.route.terminalAsal', 'schedule.route.terminalTujuan', 'seats.seat')
            ->latest();
    }

    public function dashboard()
    {
        $user = auth()->user();

        $stats = [
            'tiketAktif' => Booking::where('user_id', $user->id)->whereIn('status_booking', ['confirmed', 'completed'])->count(),
            'pending' => Booking::where('user_id', $user->id)->where('status_booking', 'pending')->count(),
            'totalPerjalanan' => Booking::where('user_id', $user->id)->whereIn('status_booking', ['confirmed', 'completed'])->count(),
            'totalBelanja' => Booking::where('user_id', $user->id)->where('status_pembayaran', 'paid')->sum('total_harga'),
        ];

        $recentBookings = $this->userBookings()->limit(5)->get();

        return view('pages.customer.dashboard', compact('stats', 'recentBookings'));
    }

    public function bookings(Request $request)
    {
        $query = $this->userBookings();

        if ($request->filled('status') && in_array($request->status, ['pending', 'confirmed', 'cancelled', 'completed'])) {
            $query->where('status_booking', $request->status);
        }

        $data = $query->get();

        return view('pages.customer.bookings', compact('data'));
    }

    public function tickets()
    {
        $data = Booking::where('user_id', auth()->id())
            ->where('status_pembayaran', 'paid')
            ->with('schedule.bus.operator', 'schedule.route.terminalAsal', 'schedule.route.terminalTujuan', 'seats.seat')
            ->latest()
            ->get();

        return view('pages.customer.tickets', compact('data'));
    }
}
