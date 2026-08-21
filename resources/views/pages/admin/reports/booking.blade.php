@extends('layouts/layoutMaster')

@section('title', 'Laporan Booking')

@section('content')
   <div class="container-xxl flex-grow-1 container-p-y">
      <div class="d-flex justify-content-between align-items-center mb-4">
         <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">Laporan /</span> Laporan Booking
         </h4>
         <div class="d-flex gap-2">
            <a href="{{ route('admin.reports.export-booking') }}?{{ http_build_query(array_filter($filters)) }}"
               class="btn btn-outline-success">
               <i class="ri-file-excel-line me-1"></i> Excel
            </a>
            <a href="{{ route('admin.reports.print', 'booking') }}?{{ http_build_query(array_filter($filters)) }}"
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
            <form method="GET" action="{{ route('admin.reports.booking') }}" class="row g-3 align-items-end">
               <div class="col-md-2">
                  <label class="form-label">Tanggal Mulai</label>
                  <input type="date" name="tanggal_mulai" class="form-control" value="{{ $filters['tanggal_mulai'] ?? '' }}">
               </div>
               <div class="col-md-2">
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
                  <label class="form-label">Terminal Asal</label>
                  <select name="terminal_asal_id" class="form-select">
                     <option value="">Semua Terminal</option>
                     @foreach ($terminals as $terminal)
                        <option value="{{ $terminal->id }}" {{ ($filters['terminal_asal_id'] ?? '') == $terminal->id ? 'selected' : '' }}>
                           {{ $terminal->kota }}
                        </option>
                     @endforeach
                  </select>
               </div>
               <div class="col-md-2">
                  <label class="form-label">Terminal Tujuan</label>
                  <select name="terminal_tujuan_id" class="form-select">
                     <option value="">Semua Terminal</option>
                     @foreach ($terminals as $terminal)
                        <option value="{{ $terminal->id }}" {{ ($filters['terminal_tujuan_id'] ?? '') == $terminal->id ? 'selected' : '' }}>
                           {{ $terminal->kota }}
                        </option>
                     @endforeach
                  </select>
               </div>
               <div class="col-md-2">
                  <label class="form-label">Status Booking</label>
                  <select name="status_booking" class="form-select">
                     <option value="">Semua Status</option>
                     <option value="pending" {{ ($filters['status_booking'] ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                     <option value="confirmed" {{ ($filters['status_booking'] ?? '') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                     <option value="completed" {{ ($filters['status_booking'] ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                     <option value="cancelled" {{ ($filters['status_booking'] ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                  </select>
               </div>
               <div class="col-md-2">
                  <label class="form-label">Status Pembayaran</label>
                  <select name="status_pembayaran" class="form-select">
                     <option value="">Semua Status</option>
                     <option value="unpaid" {{ ($filters['status_pembayaran'] ?? '') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                     <option value="pending" {{ ($filters['status_pembayaran'] ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                     <option value="paid" {{ ($filters['status_pembayaran'] ?? '') == 'paid' ? 'selected' : '' }}>Paid</option>
                     <option value="failed" {{ ($filters['status_pembayaran'] ?? '') == 'failed' ? 'selected' : '' }}>Failed</option>
                     <option value="expired" {{ ($filters['status_pembayaran'] ?? '') == 'expired' ? 'selected' : '' }}>Expired</option>
                  </select>
               </div>
               <div class="col-md-2">
                  <button type="submit" class="btn btn-primary w-100"><i class="ri-search-line me-1"></i> Filter</button>
               </div>
               <div class="col-md-2">
                  <a href="{{ route('admin.reports.booking') }}" class="btn btn-outline-secondary w-100">Reset</a>
               </div>
            </form>
         </div>
      </div>

      <div class="row mb-4">
         <div class="col-lg-4 col-md-12 mb-3">
            <div class="card">
               <div class="card-body">
                  <div class="d-flex justify-content-between">
                     <div>
                        <span class="fw-semibold d-block">Total Transaksi</span>
                        <small class="text-muted">Jumlah booking</small>
                     </div>
                     <div class="badge bg-label-primary rounded p-2">
                        <i class="ri-shopping-cart-line"></i>
                     </div>
                  </div>
                  <h4 class="mb-0 mt-2">{{ number_format((float) $summary['total_transaksi'], 0, ',', '.') }}</h4>
               </div>
            </div>
         </div>
         <div class="col-lg-4 col-md-12 mb-3">
            <div class="card">
               <div class="card-body">
                  <div class="d-flex justify-content-between">
                     <div>
                        <span class="fw-semibold d-block">Total Tiket</span>
                        <small class="text-muted">Jumlah kursi terjual</small>
                     </div>
                     <div class="badge bg-label-info rounded p-2">
                        <i class="ri-ticket-line"></i>
                     </div>
                  </div>
                  <h4 class="mb-0 mt-2">{{ number_format((float) $summary['total_tiket'], 0, ',', '.') }}</h4>
               </div>
            </div>
         </div>
         <div class="col-lg-4 col-md-12 mb-3">
            <div class="card">
               <div class="card-body">
                  <div class="d-flex justify-content-between">
                     <div>
                        <span class="fw-semibold d-block">Total Pendapatan</span>
                        <small class="text-muted">Total nilai transaksi</small>
                     </div>
                     <div class="badge bg-label-success rounded p-2">
                        <i class="ri-money-dollar-circle-line"></i>
                     </div>
                  </div>
                  <h4 class="mb-0 mt-2">Rp {{ number_format((float) $summary['total_pendapatan'], 0, ',', '.') }}</h4>
               </div>
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
                     <th>Kode</th>
                     <th>Customer</th>
                     <th>Rute</th>
                     <th>Jadwal</th>
                     <th>Jumlah Kursi</th>
                     <th>Total</th>
                     <th>Status Booking</th>
                     <th>Status Pembayaran</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse($bookings as $b)
                     <tr>
                        <td><span class="fw-bold">{{ $b->kode_booking }}</span></td>
                        <td>{{ $b->user->name }}</td>
                        <td>
                           {{ $b->schedule->route->terminalAsal->kota }} &rarr; {{ $b->schedule->route->terminalTujuan->kota }}
                        </td>
                        <td>
                           {{ $b->schedule->tanggal_keberangkatan }} <br>
                           <small class="text-muted">{{ $b->schedule->jam_keberangkatan }}</small>
                        </td>
                        <td>{{ $b->seats->count() }}</td>
                        <td>Rp {{ number_format((float) $b->total_harga, 0, ',', '.') }}</td>
                        <td>
                           @if ($b->status_booking == 'cancelled')
                              <span class="badge bg-label-danger">Cancelled</span>
                           @elseif ($b->status_booking == 'pending')
                              <span class="badge bg-label-warning">Pending</span>
                           @else
                              <span class="badge bg-label-success">{{ ucfirst($b->status_booking) }}</span>
                           @endif
                        </td>
                        <td>
                           @if ($b->status_pembayaran == 'paid')
                              <span class="badge bg-label-success">Paid</span>
                           @elseif ($b->status_pembayaran == 'failed' || $b->status_pembayaran == 'expired')
                              <span class="badge bg-label-danger">{{ ucfirst($b->status_pembayaran) }}</span>
                           @else
                              <span class="badge bg-label-warning">{{ ucfirst($b->status_pembayaran) }}</span>
                           @endif
                        </td>
                     </tr>
                  @empty
                     <tr>
                        <td colspan="8" class="text-center py-5">
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