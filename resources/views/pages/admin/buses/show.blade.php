@extends('layouts/layoutMaster')

@section('title', 'Detail Bus')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="mb-4">
        <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">Master Data /</span> Detail Bus
        </h4>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Informasi Bus</h5>
                    <a href="{{ route('admin.buses.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ri-arrow-left-line me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 200px">Kode</th>
                                <td>{{ $data->kode_bus }}</td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td>{{ $data->nama_bus }}</td>
                            </tr>
                            <tr>
                                <th>Nomor Polisi</th>
                                <td>{{ $data->nomor_polisi }}</td>
                            </tr>
                            <tr>
                                <th>Operator</th>
                                <td>{{ $data->operator->nama_operator }}</td>
                            </tr>
                            <tr>
                                <th>Kelas</th>
                                <td>{{ $data->kelas }}</td>
                            </tr>
                            <tr>
                                <th>Kapasitas</th>
                                <td>{{ $data->kapasitas }}</td>
                            </tr>
                            <tr>
                                <th>Fasilitas</th>
                                <td>{{ $data->fasilitas }}</td>
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

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Daftar Kursi</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 50px">#</th>
                                <th>Nomor Kursi</th>
                                <th>Posisi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($seats as $index => $seat)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-bold">{{ $seat->nomor_kursi }}</td>
                                    <td>{{ $seat->posisi }}</td>
                                    <td>
                                        @if ($seat->status == 'aktif')
                                            <span class="badge bg-label-success">Aktif</span>
                                        @else
                                            <span class="badge bg-label-danger">Non-Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="ri-file-search-line ri-3x mb-2"></i>
                                            <p>Belum ada kursi yang tersedia.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
