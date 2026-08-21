<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RouteRequest;
use App\Models\Route;
use App\Services\RouteService;
use App\Services\TerminalService;

class RouteController extends Controller
{
    public function __construct(
        protected RouteService $service,
        protected TerminalService $terminalService
    ) {}

    public function index()
    {
        $data = $this->service->allWithRelations();

        return view('pages.admin.routes.index', compact('data'));
    }

    public function create()
    {
        $terminals = $this->terminalService->activeTerminals();

        return view('pages.admin.routes.create', compact('terminals'));
    }

    public function store(RouteRequest $request)
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status', true);

        $exists = Route::where('terminal_asal_id', $data['terminal_asal_id'])
            ->where('terminal_tujuan_id', $data['terminal_tujuan_id'])
            ->exists();
        if ($exists) {
            return back()->withInput()->withErrors(['terminal_asal_id' => 'Rute dengan asal dan tujuan yang sama sudah ada.']);
        }

        $this->service->create($data);

        return redirect()->route('admin.routes.index')->with('success', 'Rute berhasil ditambahkan!');
    }

    public function show(int $id)
    {
        $data = $this->service->find($id);

        return view('pages.admin.routes.show', compact('data'));
    }

    public function edit(int $id)
    {
        $data = $this->service->find($id);
        $terminals = $this->terminalService->activeTerminals();

        return view('pages.admin.routes.edit', compact('data', 'terminals'));
    }

    public function update(RouteRequest $request, int $id)
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status', true);

        $exists = Route::where('terminal_asal_id', $data['terminal_asal_id'])
            ->where('terminal_tujuan_id', $data['terminal_tujuan_id'])
            ->where('id', '!=', $id)
            ->exists();
        if ($exists) {
            return back()->withInput()->withErrors(['terminal_asal_id' => 'Rute dengan asal dan tujuan yang sama sudah ada.']);
        }

        $this->service->update($id, $data);

        return redirect()->route('admin.routes.index')->with('success', 'Rute berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        if (request()->wantsJson()) {
            return ResponseHelper::success(null, 'Rute berhasil dihapus!');
        }

        return redirect()->route('admin.routes.index')->with('success', 'Rute berhasil dihapus!');
    }
}
