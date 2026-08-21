<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleRequest;
use App\Services\BusService;
use App\Services\RouteService;
use App\Services\ScheduleService;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function __construct(
        protected ScheduleService $service,
        protected BusService $busService,
        protected RouteService $routeService
    ) {}

    public function index(Request $request)
    {
        $data = $this->service->allWithRelations();

        return view('pages.admin.schedules.index', compact('data'));
    }

    public function create()
    {
        $buses = $this->busService->activeBuses();
        $routes = $this->routeService->allWithRelations();

        return view('pages.admin.schedules.create', compact('buses', 'routes'));
    }

    public function store(ScheduleRequest $request)
    {
        $data = $request->validated();

        $this->service->create($data);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal keberangkatan berhasil ditambahkan!');
    }

    public function show(int $id)
    {
        $data = $this->service->find($id);

        return view('pages.admin.schedules.show', compact('data'));
    }

    public function edit(int $id)
    {
        $data = $this->service->find($id);
        $buses = $this->busService->activeBuses();
        $routes = $this->routeService->allWithRelations();

        return view('pages.admin.schedules.edit', compact('data', 'buses', 'routes'));
    }

    public function update(ScheduleRequest $request, int $id)
    {
        $data = $request->validated();

        $this->service->update($id, $data);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal keberangkatan berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        if (request()->wantsJson()) {
            return ResponseHelper::success(null, 'Jadwal berhasil dihapus!');
        }

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil dihapus!');
    }
}
