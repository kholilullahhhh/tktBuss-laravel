<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OperatorRequest;
use App\Services\OperatorService;

class OperatorController extends Controller
{
    public function __construct(protected OperatorService $service) {}

    public function index()
    {
        $data = $this->service->all();

        return view('pages.admin.operators.index', compact('data'));
    }

    public function create()
    {
        return view('pages.admin.operators.create');
    }

    public function store(OperatorRequest $request)
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status', true);

        $this->service->create($data);

        return redirect()->route('admin.operators.index')->with('success', 'Operator bus berhasil ditambahkan!');
    }

    public function show(int $id)
    {
        $data = $this->service->find($id);

        return view('pages.admin.operators.show', compact('data'));
    }

    public function edit(int $id)
    {
        $data = $this->service->find($id);

        return view('pages.admin.operators.edit', compact('data'));
    }

    public function update(OperatorRequest $request, int $id)
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status', true);

        $this->service->update($id, $data);

        return redirect()->route('admin.operators.index')->with('success', 'Operator bus berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        if (request()->wantsJson()) {
            return ResponseHelper::success(null, 'Operator bus berhasil dihapus!');
        }

        return redirect()->route('admin.operators.index')->with('success', 'Operator bus berhasil dihapus!');
    }
}
