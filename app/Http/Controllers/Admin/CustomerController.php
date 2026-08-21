<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereHas('role', fn ($q) => $q->where('slug', 'customer'));

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('email', 'like', '%'.$request->q.'%')
                    ->orWhere('phone', 'like', '%'.$request->q.'%');
            });
        }

        $data = $query->withCount('bookings')->latest()->get();

        return view('pages.admin.customers.index', compact('data'));
    }

    public function show(int $id)
    {
        $customer = User::with('role')->withCount('bookings')->findOrFail($id);
        $bookings = $customer->bookings()
            ->with('schedule.bus.operator', 'schedule.route.terminalAsal', 'schedule.route.terminalTujuan')
            ->latest()
            ->get();

        return view('pages.admin.customers.show', compact('customer', 'bookings'));
    }
}
