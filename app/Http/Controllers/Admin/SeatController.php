<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SeatRequest;
use App\Models\Seat;
use App\Services\BusService;
use App\Services\SeatService;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function __construct(
        protected SeatService $service,
        protected BusService $busService
    ) {}

    public function index(Request $request)
    {
        $buses = $this->busService->allWithRelations();

        $query = Seat::with('bus.operator')->orderBy('bus_id');

        if ($request->filled('bus_id')) {
            $query->where('bus_id', $request->bus_id);
        }

        $data = $query->get();

        return view('pages.admin.seats.index', compact('data', 'buses'));
    }

    public function create()
    {
        $buses = $this->busService->allWithRelations();

        return view('pages.admin.seats.create', compact('buses'));
    }

    public function store(SeatRequest $request)
    {
        $data = $request->validated();

        $exists = Seat::where('bus_id', $data['bus_id'])->where('nomor_kursi', $data['nomor_kursi'])->exists();
        if ($exists) {
            return back()->withInput()->withErrors(['nomor_kursi' => 'Nomor kursi sudah ada pada bus tersebut.']);
        }

        $this->service->create($data);

        return redirect()->route('admin.seats.index')->with('success', 'Kursi berhasil ditambahkan!');
    }

    public function edit(int $id)
    {
        $data = $this->service->find($id);
        $buses = $this->busService->allWithRelations();

        return view('pages.admin.seats.edit', compact('data', 'buses'));
    }

    public function update(SeatRequest $request, int $id)
    {
        $data = $request->validated();

        $exists = Seat::where('bus_id', $data['bus_id'])
            ->where('nomor_kursi', $data['nomor_kursi'])
            ->where('id', '!=', $id)
            ->exists();
        if ($exists) {
            return back()->withInput()->withErrors(['nomor_kursi' => 'Nomor kursi sudah ada pada bus tersebut.']);
        }

        $this->service->update($id, $data);

        return redirect()->route('admin.seats.index')->with('success', 'Kursi berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        if (request()->wantsJson()) {
            return ResponseHelper::success(null, 'Kursi berhasil dihapus!');
        }

        return redirect()->route('admin.seats.index')->with('success', 'Kursi berhasil dihapus!');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'bus_id' => 'required|exists:buses,id',
        ]);

        $bus = $this->busService->find($request->bus_id);
        $created = $this->service->generateForBus($bus->id, (int) $bus->kapasitas);

        return redirect()->route('admin.seats.index', ['bus_id' => $bus->id])
            ->with($created === 0 ? 'warning' : 'success', $created === 0 ? 'Kursi sudah lengkap sesuai kapasitas.' : "{$created} kursi berhasil dibuat untuk bus {$bus->nama_bus}.");
    }
}
