@extends('layouts/layoutMaster')

@section('title', 'Pembayaran')

@section('content')
   <div class="container-xxl flex-grow-1 container-p-y">
      <div class="d-flex justify-content-between align-items-center mb-4">
         <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">Transaksi /</span> Pembayaran
         </h4>
      </div>

      <div class="card">
         <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Daftar Pembayaran</h5>
         </div>
         <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.payments.index') }}" class="row g-3 align-items-end">
               <div class="col-md-3">
                  <label class="form-label">Status</label>
                  <select name="status" class="form-select">
                     <option value="">Semua Status</option>
                     <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                     <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                     <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                     <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                  </select>
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
                     <th>Order ID</th>
                     <th>Kode Booking</th>
                     <th>Customer</th>
                     <th>Metode</th>
                     <th>Jumlah</th>
                     <th>Status</th>
                     <th class="text-center">Aksi</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse($data as $p)
                     <tr>
                        <td><span class="fw-bold">{{ $p->order_id }}</span></td>
                        <td>{{ $p->booking->kode_booking }}</td>
                        <td>{{ $p->booking->user->name }}</td>
                        <td>{{ $p->payment_type ? strtoupper($p->payment_type) : '-' }}</td>
                        <td>Rp {{ number_format((float) $p->gross_amount, 0, ',', '.') }}</td>
                        <td>
                           @if ($p->payment_status == 'paid')
                              <span class="badge bg-label-success">Paid</span>
                           @elseif ($p->payment_status == 'failed' || $p->payment_status == 'expired')
                              <span class="badge bg-label-danger">{{ ucfirst($p->payment_status) }}</span>
                           @else
                              <span class="badge bg-label-warning">{{ ucfirst($p->payment_status) }}</span>
                           @endif
                        </td>
                        <td class="text-center">
                           <a href="{{ route('admin.payments.show', $p->id) }}" class="btn btn-sm btn-outline-primary">
                              <i class="ri-eye-line"></i>
                           </a>
                        </td>
                     </tr>
                  @empty
                     <tr>
                        <td colspan="7" class="text-center py-5">
                           <div class="text-muted">
                              <i class="ri-file-search-line ri-3x mb-2"></i>
                              <p>Belum ada data pembayaran.</p>
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
