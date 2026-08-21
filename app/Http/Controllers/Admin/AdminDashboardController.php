<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Bus;
use App\Models\Operator;
use App\Models\Payment;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Terminal;
use App\Models\User;
use Illuminate\Support\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $monthStart = Carbon::now()->startOfMonth();

        $stats = [
            'totalOperators' => Operator::count(),
            'totalBuses' => Bus::count(),
            'totalTerminals' => Terminal::count(),
            'totalRoutes' => Route::count(),
            'activeSchedules' => Schedule::where('status', 'aktif')->count(),
            'totalCustomers' => User::whereHas('role', fn ($q) => $q->where('slug', 'customer'))->count(),
            'totalBookings' => Booking::count(),
            'pendingBookings' => Booking::where('status_booking', 'pending')->count(),
            'confirmedBookings' => Booking::where('status_booking', 'confirmed')->count(),
            'todayRevenue' => Booking::where('status_pembayaran', 'paid')->whereDate('paid_at', today())->sum('total_harga'),
            'monthRevenue' => Booking::where('status_pembayaran', 'paid')->where('paid_at', '>=', $monthStart)->sum('total_harga'),
        ];

        // Grafik pendapatan & booking per bulan (12 bulan terakhir)
        $chart = $this->monthlyChart();

        // Rute paling banyak dipesan
        $topRoutes = Booking::where('status_pembayaran', 'paid')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
            ->join('routes', 'schedules.route_id', '=', 'routes.id')
            ->join('terminals as t1', 'routes.terminal_asal_id', '=', 't1.id')
            ->join('terminals as t2', 'routes.terminal_tujuan_id', '=', 't2.id')
            ->selectRaw('routes.id as route_id, t1.nama_terminal as asal, t2.nama_terminal as tujuan, COUNT(*) as total')
            ->groupBy('routes.id', 't1.nama_terminal', 't2.nama_terminal')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                $row->nama = $row->asal.' -> '.$row->tujuan;

                return $row;
            });

        // Operator dengan transaksi terbanyak
        $topOperators = Booking::where('status_pembayaran', 'paid')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
            ->join('buses', 'schedules.bus_id', '=', 'buses.id')
            ->join('operators', 'buses.operator_id', '=', 'operators.id')
            ->selectRaw('operators.id as operator_id, operators.nama_operator as nama, COUNT(*) as total')
            ->groupBy('operators.id', 'operators.nama_operator')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $recentBookings = Booking::with('user', 'schedule.bus.operator', 'schedule.route.terminalAsal', 'schedule.route.terminalTujuan')
            ->latest()
            ->limit(8)
            ->get();

        $recentPayments = Payment::with('booking.user')
            ->latest()
            ->limit(8)
            ->get();

        return view('pages.admin.dashboard', compact(
            'stats', 'chart', 'topRoutes', 'topOperators', 'recentBookings', 'recentPayments'
        ));
    }

    protected function monthlyChart(): array
    {
        $labels = [];
        $revenue = [];
        $bookings = [];

        for ($i = 11; $i >= 0; $i--) {
            $start = Carbon::now()->subMonths($i)->startOfMonth();
            $end = Carbon::now()->subMonths($i)->endOfMonth();
            $labels[] = $start->translatedFormat('M Y');
            $revenue[] = (float) Booking::where('status_pembayaran', 'paid')->whereBetween('paid_at', [$start, $end])->sum('total_harga');
            $bookings[] = Booking::whereBetween('tanggal_booking', [$start, $end])->count();
        }

        return ['labels' => $labels, 'revenue' => $revenue, 'bookings' => $bookings];
    }
}
