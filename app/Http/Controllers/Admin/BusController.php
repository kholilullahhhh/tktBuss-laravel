<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\BusRequest;
use App\Services\BusService;
use App\Services\OperatorService;
use App\Services\SeatService;
use Illuminate\Http\Request;

class BusController extends Controller
{
    public function __construct(
        protected BusService $service,
        protected OperatorService $operatorService,
        protected SeatService $seatService
    ) {}

    public function index()
    {
        $data = $this->service->allWithRelations();

        return view('pages.admin.buses.index', compact('data'));
    }

    public function create()
    {
        $operators = $this->operatorService->activeOperators();

        return view('pages.admin.buses.create', compact('operators'));
    }

    public function store(BusRequest $request)
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status', true);

        $bus = $this->service->create($data);

        // Generate kursi otomatis sesuai kapasitas
        $this->seatService->generateForBus($bus->id, (int) $bus->kapasitas);

        return redirect()->route('admin.buses.index')->with('success', 'Bus berhasil ditambahkan beserta kursinya!');
    }

    public function show(int $id)
    {
        $data = $this->service->find($id);
        $seats = $this->seatService->seatsForBus($id);

        return view('pages.admin.buses.show', compact('data', 'seats'));
    }

    public function edit(int $id)
    {
        $data = $this->service->find($id);
        $operators = $this->operatorService->activeOperators();

        return view('pages.admin.buses.edit', compact('data', 'operators'));
    }

    public function update(BusRequest $request, int $id)
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status', true);

        $this->service->update($id, $data);

        return redirect()->route('admin.buses.index')->with('success', 'Bus berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        if (request()->wantsJson()) {
            return ResponseHelper::success(null, 'Bus berhasil dihapus!');
        }

        return redirect()->route('admin.buses.index')->with('success', 'Bus berhasil dihapus!');
    }

    public function generateSeats(Request $request, int $id)
    {
        $bus = $this->service->find($id);
        $created = $this->seatService->generateForBus($bus->id, (int) $bus->kapasitas);

        if ($created === 0) {
            return redirect()->back()->with('warning', 'Kursi sudah lengkap sesuai kapasitas.');
        }

        return redirect()->back()->with('success', "{$created} kursi berhasil ditambahkan untuk bus {$bus->nama_bus}.");
    }
}
