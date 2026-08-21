@extends('layouts/layoutMaster')

@section('title', 'Detail Operator Bus')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="mb-4">
        <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">Master Data /</span> Detail Operator Bus
        </h4>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Informasi Operator Bus</h5>
                    <a href="{{ route('admin.operators.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ri-arrow-left-line me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 200px">Kode</th>
                                <td>{{ $data->kode_operator }}</td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td>{{ $data->nama_operator }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $data->alamat }}</td>
                            </tr>
                            <tr>
                                <th>Telepon</th>
                                <td>{{ $data->telepon }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $data->email }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if ($data->status)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Non-Aktif</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
