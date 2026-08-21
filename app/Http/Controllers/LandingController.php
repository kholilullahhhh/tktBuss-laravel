<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use App\Models\Route;
use App\Models\Terminal;

class LandingController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            if (auth()->user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('customer.dashboard');
        }

        $terminals = Terminal::where('status', true)->orderBy('nama_terminal')->get();
        $operators = Operator::where('status', true)->orderBy('nama_operator')->get();

        // Rute populer (dari jumlah jadwal aktif)
        $popularRoutes = Route::with('terminalAsal', 'terminalTujuan')
            ->where('status', true)
            ->withCount(['schedules' => fn ($q) => $q->where('status', 'aktif')])
            ->orderByDesc('schedules_count')
            ->limit(6)
            ->get();

        return view('pages.landing', compact('terminals', 'operators', 'popularRoutes'));
    }
}
