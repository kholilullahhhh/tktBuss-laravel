@extends('layouts/layoutMaster')

@section('title', 'Detail Customer')

@section('content')
   <div class="container-xxl flex-grow-1 container-p-y">
      <div class="d-flex justify-content-between align-items-center mb-4">
         <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">Transaksi / Customer /</span> {{ $customer->name }}
         </h4>
         <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i> Kembali
         </a>
      </div>

      <div class="card mb-4">
         <div class="card-body">
            <div class="d-flex align-items-center">
               <div class="avatar avatar-lg me-3">
                  <span class="avatar-initial rounded-circle bg-label-primary">
                     {{ strtoupper(substr($customer->name, 0, 1)) }}
                  </span>
               </div>
               <div>
                  <h5 class="mb-1">{{ $customer->name }}</h5>
                  <div class="text-muted small">
                     {{ $customer->email }} &bull; {{ $customer->phone ?? '-' }}
                  </div>
               </div>
            </div>
            <hr>
            <div class="row">
               <div class="col-md-4">
                  <small class="text-muted d-block">Role</small>
                  <span class="fw-bold">{{ $customer->role?->name ?? '-' }}</span>
               </div>
               <div class="col-md-4">
                  <small class="text-muted d-block">Email</small>
                  <span class="fw-bold">{{ $customer->email }}</span>
               </div>
               <div class="col-md-4">
                  <small class="text-muted d-block">No. HP</small>
                  <span class="fw-bold">{{ $customer->phone ?? '-' }}</span>
               </div>
            </div>
         </div>
      </div>

      <div class="card">
         <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Riwayat Booking</h5>
         </div>
         <div class="table-responsive">
            <table class="table table-hover">
               <thead>
                  <tr>
                     <th>Kode</th>
                     <th>Rute</th>
                     <th>Jadwal</th>
                     <th>Total</th>
                     <th>Status</th>
                     <th class="text-center">Aksi</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse($bookings as $b)
                     <tr>
                        <td><span class="fw-bold">{{ $b->kode_booking }}</span></td>
                        <td>
                           {{ $b->schedule->route->terminalAsal->kota }} &rarr; {{ $b->schedule->route->terminalTujuan->kota }}
                        </td>
                        <td>
                           {{ $b->schedule->tanggal_keberangkatan }} <br>
                           <small class="text-muted">{{ $b->schedule->jam_keberangkatan }}</small>
                        </td>
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
                        <td class="text-center">
                           <a href="{{ route('admin.bookings.show', $b->id) }}" class="btn btn-sm btn-outline-primary">
                              <i class="ri-eye-line"></i>
                           </a>
                        </td>
                     </tr>
                  @empty
                     <tr>
                        <td colspan="6" class="text-center py-5">
                           <div class="text-muted">
                              <i class="ri-file-search-line ri-3x mb-2"></i>
                              <p>Belum ada booking untuk customer ini.</p>
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
