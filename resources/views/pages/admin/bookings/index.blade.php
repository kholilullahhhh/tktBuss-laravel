@extends('layouts/layoutMaster')

@section('title', 'Booking')

@section('content')
   <div class="container-xxl flex-grow-1 container-p-y">
      <div class="d-flex justify-content-between align-items-center mb-4">
         <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">Transaksi /</span> Booking
         </h4>
      </div>

      <div class="card">
         <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Daftar Booking</h5>
         </div>
         <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.bookings.index') }}" class="row g-3 align-items-end">
               <div class="col-md-3">
                  <label class="form-label">Status Booking</label>
                  <select name="status" class="form-select">
                     <option value="">Semua Status</option>
                     <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                     <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                     <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                     <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                  </select>
               </div>
               <div class="col-md-4">
                  <label class="form-label">Cari Kode</label>
                  <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Kode booking...">
               </div>
               <div class="col-md-2">
                  <button type="submit" class="btn btn-primary w-100"><i class="ri-search-line me-1"></i> Filter</button>
               </div>
            </form>
         </div>
         <div class="table-responsive">
            <table class="table table-hover">
               <thead>
                  <tr>
                     <th>Kode</th>
                     <th>Customer</th>
                     <th>Rute</th>
                     <th>Jadwal</th>
                     <th>Total</th>
                     <th>Status Booking</th>
                     <th>Status Pembayaran</th>
                     <th class="text-center">Aksi</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse($data as $b)
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
                           @else
                              <span class="badge bg-label-warning">{{ ucfirst($b->status_pembayaran) }}</span>
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
                        <td colspan="8" class="text-center py-5">
                           <div class="text-muted">
                              <i class="ri-file-search-line ri-3x mb-2"></i>
                              <p>Belum ada data booking.</p>
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
