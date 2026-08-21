@extends('layouts/layoutMaster')

@section('title', 'Laporan Pendapatan')

@section('content')
   <div class="container-xxl flex-grow-1 container-p-y">
      <div class="d-flex justify-content-between align-items-center mb-4">
         <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">Laporan /</span> Laporan Pendapatan
         </h4>
         <div class="d-flex gap-2">
            <a href="{{ route('admin.reports.export-revenue') }}?{{ http_build_query(array_filter($filters)) }}"
               class="btn btn-outline-success">
               <i class="ri-file-excel-line me-1"></i> Excel
            </a>
            <a href="{{ route('admin.reports.print', 'revenue') }}?{{ http_build_query(array_filter($filters)) }}"
               class="btn btn-outline-danger">
               <i class="ri-file-pdf-line me-1"></i> PDF
            </a>
         </div>
      </div>

      <div class="card mb-4">
         <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Filter</h5>
         </div>
         <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.revenue') }}" class="row g-3 align-items-end">
               <div class="col-md-3">
                  <label class="form-label">Tanggal Mulai</label>
                  <input type="date" name="tanggal_mulai" class="form-control" value="{{ $filters['tanggal_mulai'] ?? '' }}">
               </div>
               <div class="col-md-3">
                  <label class="form-label">Tanggal Akhir</label>
                  <input type="date" name="tanggal_akhir" class="form-control" value="{{ $filters['tanggal_akhir'] ?? '' }}">
               </div>
               <div class="col-md-2">
                  <label class="form-label">Operator</label>
                  <select name="operator_id" class="form-select">
                     <option value="">Semua Operator</option>
                     @foreach ($operators as $op)
                        <option value="{{ $op->id }}" {{ ($filters['operator_id'] ?? '') == $op->id ? 'selected' : '' }}>
                           {{ $op->nama_operator }}
                        </option>
                     @endforeach
                  </select>
               </div>
               <div class="col-md-2">
                  <label class="form-label">Bus</label>
                  <select name="bus_id" class="form-select">
                     <option value="">Semua Bus</option>
                     @foreach ($buses as $bus)
                        <option value="{{ $bus->id }}" {{ ($filters['bus_id'] ?? '') == $bus->id ? 'selected' : '' }}>
                           {{ $bus->nama_bus }}
                        </option>
                     @endforeach
                  </select>
               </div>
               <div class="col-md-2">
                  <button type="submit" class="btn btn-primary w-100"><i class="ri-search-line me-1"></i> Filter</button>
               </div>
               <div class="col-md-2">
                  <a href="{{ route('admin.reports.revenue') }}" class="btn btn-outline-secondary w-100">Reset</a>
               </div>
            </form>
         </div>
      </div>

      <div class="card mb-4">
         <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
               <div>
                  <span class="fw-semibold d-block">Total Pendapatan</span>
                  <small class="text-muted">Akumulasi seluruh transaksi pada periode filter</small>
               </div>
               <h4 class="mb-0 text-success">
                  Rp {{ number_format((float) $total, 0, ',', '.') }}
               </h4>
            </div>
         </div>
      </div>

      <div class="card">
         <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Hasil Laporan</h5>
         </div>
         <div class="table-responsive">
            <table class="table table-hover">
               <thead>
                  <tr>
                     <th>Tanggal</th>
                     <th>Transaksi</th>
                     <th>Pendapatan</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse($rows as $row)
                     <tr>
                        <td>{{ $row->tgl }}</td>
                        <td>{{ $row->transaksi }}</td>
                        <td>Rp {{ number_format((float) $row->pendapatan, 0, ',', '.') }}</td>
                     </tr>
                  @empty
                     <tr>
                        <td colspan="3" class="text-center py-5">
                           <div class="text-muted">
                              <i class="ri-file-search-line ri-3x mb-2"></i>
                              <p>Tidak ada data untuk filter tersebut.</p>
                           </div>
                        </td>
                     </tr>
                  @endforelse
               </tbody>
            </table>
         </div>
      </div>
   </div>
@endsection