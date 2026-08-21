<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Terminal;
use App\Services\BookingService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct(protected BookingService $bookingService) {}

    public function index()
    {
        $terminals = Terminal::where('status', true)->orderBy('nama_terminal')->get();

        return view('pages.tickets.index', compact('terminals'));
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'terminal_asal_id' => ['required', 'exists:terminals,id'],
            'terminal_tujuan_id' => ['required', 'exists:terminals,id', 'different:terminal_asal_id'],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'penumpang' => ['required', 'integer', 'min:1', 'max:8'],
        ], [
            'terminal_asal_id.required' => 'Terminal asal wajib dipilih.',
            'terminal_tujuan_id.required' => 'Terminal tujuan wajib dipilih.',
            'terminal_tujuan_id.different' => 'Asal dan tujuan tidak boleh sama.',
            'tanggal.required' => 'Tanggal keberangkatan wajib diisi.',
            'tanggal.after_or_equal' => 'Tanggal tidak boleh sebelum hari ini.',
            'penumpang.min' => 'Jumlah penumpang minimal 1.',
        ]);

        $tanggal = $request->tanggal;
        $penumpang = (int) $request->penumpang;

        $schedules = Schedule::with('bus.operator', 'bus.seats', 'route.terminalAsal', 'route.terminalTujuan')
            ->where('status', 'aktif')
            ->whereDate('tanggal_keberangkatan', $tanggal)
            ->whereHas('route', function ($q) use ($request) {
                $q->where('terminal_asal_id', $request->terminal_asal_id)
                    ->where('terminal_tujuan_id', $request->terminal_tujuan_id);
            })
            ->orderBy('jam_keberangkatan')
            ->get()
            ->filter(function (Schedule $schedule) use ($penumpang) {
                return $schedule->availableSeatsCount() >= $penumpang;
            })
            ->values();

        $terminals = Terminal::where('status', true)->orderBy('nama_terminal')->get();

        // Filter (klien-side)
        $filters = $request->only(['terminal_asal_id', 'terminal_tujuan_id', 'tanggal', 'penumpang']);
        $operators = $schedules->pluck('bus.operator')->unique('id')->filter()->values();

        return view('pages.tickets.search', compact('schedules', 'terminals', 'filters', 'penumpang', 'operators'));
    }

    public function show(Schedule $schedule)
    {
        $schedule->load('bus.operator', 'bus.seats', 'route.terminalAsal', 'route.terminalTujuan');

        $availableSeats = $schedule->availableSeatsCount();

        return view('pages.tickets.show', compact('schedule', 'availableSeats'));
    }

    public function seats(Request $request, Schedule $schedule)
    {
        $schedule->load('bus', 'bus.seats', 'bus.operator', 'route.terminalAsal', 'route.terminalTujuan');

        $penumpang = max(1, min(8, (int) $request->query('penumpang', 1)));
        $seatAvailability = $this->bookingService->seatAvailability($schedule);

        return view('pages.tickets.seats', compact('schedule', 'penumpang', 'seatAvailability'));
    }

    public function store(Request $request, Schedule $schedule)
    {
        $schedule->load('bus');

        $validated = $request->validate([
            'seats' => ['required', 'array', 'min:1'],
            'seats.*' => ['integer'],
            'passengers' => ['required', 'array', 'min:1'],
            'passengers.*.nama_penumpang' => ['required', 'string', 'max:150'],
            'passengers.*.nik' => ['nullable', 'digits_between:15,16'],
            'passengers.*.no_hp' => ['nullable', 'string', 'max:30'],
            'passengers.*.jenis_kelamin' => ['nullable', 'in:L,P'],
            'passengers.*.tanggal_lahir' => ['nullable', 'date', 'before:today'],
        ], [
            'seats.required' => 'Silakan pilih kursi terlebih dahulu.',
            'seats.min' => 'Minimal satu kursi harus dipilih.',
            'passengers.required' => 'Data penumpang wajib diisi.',
            'passengers.*.nama_penumpang.required' => 'Nama penumpang wajib diisi.',
        ]);

        try {
            $booking = $this->bookingService->book(auth()->user(), $schedule, $validated['seats'], array_values($validated['passengers']));
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('booking.show', $booking->id)->with('success', 'Booking berhasil dibuat! Silakan selesaikan pembayaran.');
    }
}
