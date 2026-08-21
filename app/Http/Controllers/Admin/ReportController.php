<?php

namespace App\Http\Controllers\Admin;

use App\Exports\BookingsExport;
use App\Exports\RevenueExport;
use App\Exports\TravelExport;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Bus;
use App\Models\Operator;
use App\Models\Schedule;
use App\Models\Terminal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Query booking bersama filter laporan.
     */
    protected function bookingQuery(Request $request)
    {
        $query = Booking::with(
            'user',
            'seats',
            'schedule.bus.operator',
            'schedule.route.terminalAsal',
            'schedule.route.terminalTujuan'
        )->latest();

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_booking', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_booking', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('operator_id')) {
            $query->whereHas('schedule.bus', fn ($q) => $q->where('operator_id', $request->operator_id));
        }
        if ($request->filled('bus_id')) {
            $query->whereHas('schedule', fn ($q) => $q->where('bus_id', $request->bus_id));
        }
        if ($request->filled('terminal_asal_id')) {
            $query->whereHas('schedule.route', fn ($q) => $q->where('terminal_asal_id', $request->terminal_asal_id));
        }
        if ($request->filled('terminal_tujuan_id')) {
            $query->whereHas('schedule.route', fn ($q) => $q->where('terminal_tujuan_id', $request->terminal_tujuan_id));
        }
        if ($request->filled('status_booking')) {
            $query->where('status_booking', $request->status_booking);
        }
        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        return $query;
    }

    public function booking(Request $request)
    {
        $bookings = $this->bookingQuery($request)->get();

        $summary = [
            'total_transaksi' => $bookings->count(),
            'total_tiket' => $bookings->sum(fn ($b) => $b->seats->count()),
            'total_pendapatan' => $bookings->where('status_pembayaran', 'paid')->sum('total_harga'),
        ];

        return view('pages.admin.reports.booking', [
            'bookings' => $bookings,
            'summary' => $summary,
            'operators' => Operator::orderBy('nama_operator')->get(),
            'buses' => Bus::with('operator')->orderBy('nama_bus')->get(),
            'terminals' => Terminal::orderBy('nama_terminal')->get(),
            'filters' => $request->only(['tanggal_mulai', 'tanggal_akhir', 'operator_id', 'bus_id', 'terminal_asal_id', 'terminal_tujuan_id', 'status_booking', 'status_pembayaran']),
        ]);
    }

    public function revenue(Request $request)
    {
        $query = Booking::where('status_pembayaran', 'paid')->whereNotNull('paid_at');

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('paid_at', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('paid_at', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('operator_id')) {
            $query->whereHas('schedule.bus', fn ($q) => $q->where('operator_id', $request->operator_id));
        }
        if ($request->filled('bus_id')) {
            $query->whereHas('schedule', fn ($q) => $q->where('bus_id', $request->bus_id));
        }

        $rows = $query
            ->selectRaw('DATE(paid_at) as tgl, COUNT(*) as transaksi, SUM(total_harga) as pendapatan')
            ->groupBy('tgl')
            ->orderByDesc('tgl')
            ->get();

        $total = $query->sum('total_harga');

        return view('pages.admin.reports.revenue', [
            'rows' => $rows,
            'total' => $total,
            'operators' => Operator::orderBy('nama_operator')->get(),
            'buses' => Bus::with('operator')->orderBy('nama_bus')->get(),
            'filters' => $request->only(['tanggal_mulai', 'tanggal_akhir', 'operator_id', 'bus_id']),
        ]);
    }

    public function travel(Request $request)
    {
        $query = Schedule::with('bus.operator', 'route.terminalAsal', 'route.terminalTujuan');

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_keberangkatan', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_keberangkatan', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('operator_id')) {
            $query->whereHas('bus', fn ($q) => $q->where('operator_id', $request->operator_id));
        }
        if ($request->filled('bus_id')) {
            $query->where('bus_id', $request->bus_id);
        }

        $rows = $query->orderBy('tanggal_keberangkatan')->orderBy('jam_keberangkatan')->get()
            ->map(function (Schedule $schedule) {
                $booked = $schedule->bookedSeatsCount();
                $revenue = $schedule->bookings()->where('status_pembayaran', 'paid')->sum('total_harga');

                return [
                    'id' => $schedule->id,
                    'tanggal' => $schedule->tanggal_keberangkatan->format('d-m-Y'),
                    'jam_berangkat' => $schedule->jam_keberangkatan,
                    'jam_tiba' => $schedule->jam_tiba,
                    'operator' => $schedule->bus?->operator?->nama_operator ?? '-',
                    'bus' => $schedule->bus?->nama_bus ?? '-',
                    'kelas' => $schedule->bus?->kelas ?? '-',
                    'asal' => $schedule->route?->terminalAsal?->nama_terminal ?? '-',
                    'tujuan' => $schedule->route?->terminalTujuan?->nama_terminal ?? '-',
                    'harga' => $schedule->harga,
                    'kapasitas' => $schedule->bus?->kapasitas ?? 0,
                    'terjual' => $booked,
                    'revenue' => (float) $revenue,
                ];
            });

        return view('pages.admin.reports.travel', [
            'rows' => $rows,
            'operators' => Operator::orderBy('nama_operator')->get(),
            'buses' => Bus::with('operator')->orderBy('nama_bus')->get(),
            'filters' => $request->only(['tanggal_mulai', 'tanggal_akhir', 'operator_id', 'bus_id']),
        ]);
    }

    public function exportBooking(Request $request)
    {
        $bookings = $this->bookingQuery($request)->get();

        return Excel::download(new BookingsExport($bookings), 'laporan-booking-'.date('Y-m-d').'.xlsx');
    }

    public function exportRevenue(Request $request)
    {
        $query = Booking::where('status_pembayaran', 'paid')->whereNotNull('paid_at');
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('paid_at', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('paid_at', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('operator_id')) {
            $query->whereHas('schedule.bus', fn ($q) => $q->where('operator_id', $request->operator_id));
        }
        if ($request->filled('bus_id')) {
            $query->whereHas('schedule', fn ($q) => $q->where('bus_id', $request->bus_id));
        }

        $rows = $query->selectRaw('DATE(paid_at) as tgl, COUNT(*) as transaksi, SUM(total_harga) as pendapatan')
            ->groupBy('tgl')->orderByDesc('tgl')->get();
        $total = $query->sum('total_harga');

        return Excel::download(new RevenueExport($rows, $total), 'laporan-pendapatan-'.date('Y-m-d').'.xlsx');
    }

    public function exportTravel(Request $request)
    {
        $query = Schedule::with('bus.operator', 'route.terminalAsal', 'route.terminalTujuan');
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_keberangkatan', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_keberangkatan', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('operator_id')) {
            $query->whereHas('bus', fn ($q) => $q->where('operator_id', $request->operator_id));
        }
        if ($request->filled('bus_id')) {
            $query->where('bus_id', $request->bus_id);
        }

        $rows = $query->get()->map(fn (Schedule $s) => [
            'tanggal' => $s->tanggal_keberangkatan->format('d-m-Y'),
            'operator' => $s->bus?->operator?->nama_operator ?? '-',
            'bus' => $s->bus?->nama_bus ?? '-',
            'asal' => $s->route?->terminalAsal?->nama_terminal ?? '-',
            'tujuan' => $s->route?->terminalTujuan?->nama_terminal ?? '-',
            'berangkat' => $s->jam_keberangkatan,
            'tiba' => $s->jam_tiba,
            'terjual' => $s->bookedSeatsCount(),
            'kapasitas' => $s->bus?->kapasitas ?? 0,
        ]);

        return Excel::download(new TravelExport($rows), 'laporan-perjalanan-'.date('Y-m-d').'.xlsx');
    }

    public function print(Request $request, string $type)
    {
        $title = match ($type) {
            'booking' => 'Laporan Booking',
            'revenue' => 'Laporan Pendapatan',
            'travel' => 'Laporan Perjalanan',
            default => 'Laporan',
        };

        if ($type === 'revenue') {
            $query = Booking::where('status_pembayaran', 'paid')->whereNotNull('paid_at');
            if ($request->filled('tanggal_mulai')) {
                $query->whereDate('paid_at', '>=', $request->tanggal_mulai);
            }
            if ($request->filled('tanggal_akhir')) {
                $query->whereDate('paid_at', '<=', $request->tanggal_akhir);
            }
            $rows = $query->selectRaw('DATE(paid_at) as tgl, COUNT(*) as transaksi, SUM(total_harga) as pendapatan')
                ->groupBy('tgl')->orderByDesc('tgl')->get();
            $total = $query->sum('total_harga');
            $pdf = Pdf::loadView('pages.admin.reports.print-revenue', compact('rows', 'total', 'title'));
        } elseif ($type === 'travel') {
            $schedules = Schedule::with('bus.operator', 'route.terminalAsal', 'route.terminalTujuan')->get();
            $rows = $schedules->map(fn (Schedule $s) => (object) [
                'tanggal' => $s->tanggal_keberangkatan->format('d-m-Y'),
                'operator' => $s->bus?->operator?->nama_operator ?? '-',
                'bus' => $s->bus?->nama_bus ?? '-',
                'asal' => $s->route?->terminalAsal?->nama_terminal ?? '-',
                'tujuan' => $s->route?->terminalTujuan?->nama_terminal ?? '-',
                'berangkat' => $s->jam_keberangkatan,
                'terjual' => $s->bookedSeatsCount(),
                'kapasitas' => $s->bus?->kapasitas ?? 0,
            ]);
            $pdf = Pdf::loadView('pages.admin.reports.print-travel', compact('rows', 'title'));
        } else {
            $bookings = $this->bookingQuery($request)->get();
            $pdf = Pdf::loadView('pages.admin.reports.print-booking', compact('bookings', 'title'));
        }

        return $pdf->stream('laporan-'.$type.'-'.date('Y-m-d').'.pdf');
    }
}
