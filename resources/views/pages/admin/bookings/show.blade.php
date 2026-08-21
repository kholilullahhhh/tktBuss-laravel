@extends('layouts/layoutMaster')

@section('title', 'Detail Booking')

@section('content')
   <div class="container-xxl flex-grow-1 container-p-y">
      <div class="d-flex justify-content-between align-items-center mb-4">
         <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">Transaksi / Booking /</span> {{ $booking->kode_booking }}
         </h4>
         <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i> Kembali
         </a>
      </div>

      @if ($booking->status_booking == 'cancelled')
         <div class="alert alert-danger" role="alert">
            Booking ini telah <b>dibatalkan</b>.
         </div>
      @endif

      <div class="row">
         <div class="col-xl-8 col-lg-7">
            <div class="card mb-4">
               <div class="card-header border-bottom">
                  <h5 class="card-title mb-0">Detail Perjalanan</h5>
               </div>
               <div class="card-body">
                  <div class="row mb-3">
                     <div class="col-md-6">
                        <small class="text-muted d-block">Jam Berangkat</small>
                        <span class="fw-bold">{{ $booking->schedule->jam_keberangkatan }}</span>
                     </div>
                     <div class="col-md-6">
                        <small class="text-muted d-block">Jam Tiba</small>
                        <span class="fw-bold">{{ $booking->schedule->jam_kedatangan ?? '-' }}</span>
                     </div>
                  </div>
                  <div class="row mb-3">
                     <div class="col-md-6">
                        <small class="text-muted d-block">Tanggal</small>
                        <span class="fw-bold">{{ $booking->schedule->tanggal_keberangkatan }}</span>
                     </div>
                     <div class="col-md-6">
                        <small class="text-muted d-block">Kelas</small>
                        <span class="fw-bold">{{ $booking->schedule->kelas ?? '-' }}</span>
                     </div>
                  </div>
                  <div class="row mb-3">
                     <div class="col-md-6">
                        <small class="text-muted d-block">Operator</small>
                        <span class="fw-bold">{{ $booking->schedule->bus->operator->nama_operator }}</span>
                     </div>
                     <div class="col-md-6">
                        <small class="text-muted d-block">Bus</small>
                        <span class="fw-bold">{{ $booking->schedule->bus->nama_bus }}</span>
                     </div>
                  </div>
                  <div class="alert alert-primary mb-0" role="alert">
                     <i class="ri-roadster-line me-1"></i>
                     {{ $booking->schedule->route->terminalAsal->kota }}
                     &rarr; {{ $booking->schedule->route->terminalTujuan->kota }}
                  </div>
               </div>
            </div>

            <div class="card mb-4">
               <div class="card-header border-bottom">
                  <h5 class="card-title mb-0">Penumpang &amp; Kursi</h5>
               </div>
               <div class="table-responsive">
                  <table class="table table-hover">
                     <thead>
                        <tr>
                           <th>Kursi</th>
                           <th>Nama Penumpang</th>
                           <th>NIK</th>
                           <th>No. HP</th>
                           <th>Harga</th>
                        </tr>
                     </thead>
                     <tbody>
                        @forelse($booking->seats as $seat)
                           <tr>
                              <td><span class="fw-bold">{{ $seat->seat->nomor_kursi }}</span></td>
                              <td>{{ $seat->nama_penumpang }}</td>
                              <td>{{ $seat->nik }}</td>
                              <td>{{ $seat->no_hp }}</td>
                              <td>Rp {{ number_format((float) $seat->harga, 0, ',', '.') }}</td>
                           </tr>
                        @empty
                           <tr>
                              <td colspan="5" class="text-center py-4">Tidak ada penumpang.</td>
                           </tr>
                        @endforelse
                     </tbody>
                     <tfoot>
                        <tr class="table-light fw-bold">
                           <td colspan="4" class="text-end">Total</td>
                           <td>Rp {{ number_format((float) $booking->total_harga, 0, ',', '.') }}</td>
                        </tr>
                     </tfoot>
                  </table>
               </div>
            </div>

            <div class="card mb-4">
               <div class="card-header border-bottom">
                  <h5 class="card-title mb-0">Info Pembayaran</h5>
               </div>
               <div class="card-body">
                  <div class="row mb-3">
                     <div class="col-md-6">
                        <small class="text-muted d-block">Status Pembayaran</small>
                        @if ($booking->status_pembayaran == 'paid')
                           <span class="badge bg-label-success">Paid</span>
                        @elseif ($booking->status_pembayaran == 'pending')
                           <span class="badge bg-label-warning">Pending</span>
                        @elseif ($booking->status_pembayaran == 'failed')
                           <span class="badge bg-label-danger">Failed</span>
                        @else
                           <span class="badge bg-label-secondary">{{ ucfirst($booking->status_pembayaran) }}</span>
                        @endif
                     </div>
                     <div class="col-md-6">
                        <small class="text-muted d-block">Metode</small>
                        <span class="fw-bold">{{ $booking->payment_method ?? '-' }}</span>
                     </div>
                  </div>
                  <div class="row mb-3">
                     <div class="col-md-6">
                        <small class="text-muted d-block">Order ID</small>
                        <span class="fw-bold">{{ $booking->payment?->order_id ?? '-' }}</span>
                     </div>
                     <div class="col-md-6">
                        <small class="text-muted d-block">Tanggal Booking</small>
                        <span class="fw-bold">{{ $booking->tanggal_booking }}</span>
                     </div>
                  </div>
                  <div class="d-flex gap-2">
                     @if ($booking->isPaid())
                        <a href="{{ route('booking.ticket', $booking->id) }}" class="btn btn-outline-primary">
                           <i class="ri-ticket-line me-1"></i> Lihat Tiket
                        </a>
                        <a href="{{ route('booking.ticket.pdf', $booking->id) }}" class="btn btn-outline-danger">
                           <i class="ri-file-pdf-line me-1"></i> PDF
                        </a>
                     @endif
                  </div>
               </div>
            </div>
         </div>

         <div class="col-xl-4 col-lg-5">
            <div class="card">
               <div class="card-header border-bottom">
                  <h5 class="card-title mb-0">Ubah Status</h5>
               </div>
               <div class="card-body">
                  <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST">
                     @csrf
                     @method('PUT')
                     <div class="mb-3">
                        <label class="form-label">Status Booking</label>
                        <select name="status_booking" class="form-select">
                           <option value="pending" {{ $booking->status_booking == 'pending' ? 'selected' : '' }}>Pending</option>
                           <option value="confirmed" {{ $booking->status_booking == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                           <option value="completed" {{ $booking->status_booking == 'completed' ? 'selected' : '' }}>Completed</option>
                           <option value="cancelled" {{ $booking->status_booking == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                     </div>
                     <div class="mb-3">
                        <label class="form-label">Status Pembayaran</label>
                        <select name="status_pembayaran" class="form-select">
                           <option value="">Tidak Diubah</option>
                           <option value="unpaid" {{ $booking->status_pembayaran == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                           <option value="pending" {{ $booking->status_pembayaran == 'pending' ? 'selected' : '' }}>Pending</option>
                           <option value="paid" {{ $booking->status_pembayaran == 'paid' ? 'selected' : '' }}>Paid</option>
                           <option value="failed" {{ $booking->status_pembayaran == 'failed' ? 'selected' : '' }}>Failed</option>
                           <option value="expired" {{ $booking->status_pembayaran == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                     </div>
                     <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
@endsection
