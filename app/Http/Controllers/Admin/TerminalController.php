<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\TerminalRequest;
use App\Services\TerminalService;

class TerminalController extends Controller
{
    public function __construct(protected TerminalService $service) {}

    public function index()
    {
        $data = $this->service->all();

        return view('pages.admin.terminals.index', compact('data'));
    }

    public function create()
    {
        return view('pages.admin.terminals.create');
    }

    public function store(TerminalRequest $request)
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status', true);

        $this->service->create($data);

        return redirect()->route('admin.terminals.index')->with('success', 'Terminal berhasil ditambahkan!');
    }

    public function show(int $id)
    {
        $data = $this->service->find($id);

        return view('pages.admin.terminals.show', compact('data'));
    }

    public function edit(int $id)
    {
        $data = $this->service->find($id);

        return view('pages.admin.terminals.edit', compact('data'));
    }

    public function update(TerminalRequest $request, int $id)
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status', true);

        $this->service->update($id, $data);

        return redirect()->route('admin.terminals.index')->with('success', 'Terminal berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        if (request()->wantsJson()) {
            return ResponseHelper::success(null, 'Terminal berhasil dihapus!');
        }

        return redirect()->route('admin.terminals.index')->with('success', 'Terminal berhasil dihapus!');
    }
}
