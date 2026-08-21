@extends('layouts/layoutMaster')

@section('title', 'Detail Pembayaran')

@section('content')
   <div class="container-xxl flex-grow-1 container-p-y">
      <div class="d-flex justify-content-between align-items-center mb-4">
         <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">Transaksi / Pembayaran /</span> {{ $payment->order_id }}
         </h4>
         <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i> Kembali
         </a>
      </div>

      <div class="row">
         <div class="col-xl-7 col-lg-6">
            <div class="card mb-4">
               <div class="card-header border-bottom">
                  <h5 class="card-title mb-0">Detail Pembayaran</h5>
               </div>
               <div class="card-body">
                  <div class="row mb-3">
                     <div class="col-md-6">
                        <small class="text-muted d-block">Order ID</small>
                        <span class="fw-bold">{{ $payment->order_id }}</span>
                     </div>
                     <div class="col-md-6">
                        <small class="text-muted d-block">Kode Booking</small>
                        <span class="fw-bold">{{ $payment->booking->kode_booking }}</span>
                     </div>
                  </div>
                  <div class="row mb-3">
                     <div class="col-md-6">
                        <small class="text-muted d-block">Metode</small>
                        <span class="fw-bold">{{ $payment->payment_type ? strtoupper($payment->payment_type) : '-' }}</span>
                     </div>
                     <div class="col-md-6">
                        <small class="text-muted d-block">Transaction ID</small>
                        <span class="fw-bold">{{ $payment->transaction_id ?? '-' }}</span>
                     </div>
                  </div>
                  <div class="row mb-3">
                     <div class="col-md-6">
                        <small class="text-muted d-block">Gross Amount</small>
                        <span class="fw-bold">Rp {{ number_format((float) $payment->gross_amount, 0, ',', '.') }}</span>
                     </div>
                     <div class="col-md-6">
                        <small class="text-muted d-block">Transaction Status</small>
                        <span class="fw-bold">{{ ucfirst($payment->transaction_status) }}</span>
                     </div>
                  </div>
                  <div class="row mb-3">
                     <div class="col-md-6">
                        <small class="text-muted d-block">Payment Status</small>
                        @if ($payment->payment_status == 'paid')
                           <span class="badge bg-label-success">Paid</span>
                        @elseif ($payment->payment_status == 'failed' || $payment->payment_status == 'expired')
                           <span class="badge bg-label-danger">{{ ucfirst($payment->payment_status) }}</span>
                        @else
                           <span class="badge bg-label-warning">{{ ucfirst($payment->payment_status) }}</span>
                        @endif
                     </div>
                     <div class="col-md-6">
                        <small class="text-muted d-block">Paid At</small>
                        <span class="fw-bold">{{ $payment->paid_at?->translatedFormat('d M Y H:i') ?? '-' }}</span>
                     </div>
                  </div>
                  <div class="row mb-3">
                     <div class="col-md-6">
                        <small class="text-muted d-block">Tanggal Booking</small>
                        <span class="fw-bold">{{ $payment->booking->tanggal_booking?->translatedFormat('d M Y H:i') }}</span>
                     </div>
                  </div>
                  <div class="d-flex gap-2">
                     @if ($payment->payment_status != 'paid')
                        <form action="{{ route('admin.payments.mark-paid', $payment->id) }}" method="POST">
                           @csrf
                           <button type="submit" class="btn btn-success">
                              <i class="ri-check-line me-1"></i> Konfirmasi Lunas
                           </button>
                        </form>
                     @endif
                     <form action="{{ route('admin.payments.mark-failed', $payment->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                           <i class="ri-close-line me-1"></i> Tandai Gagal
                        </button>
                     </form>
                  </div>
               </div>
            </div>
         </div>

         <div class="col-xl-5 col-lg-6">
            <div class="card mb-4">
               <div class="card-header border-bottom">
                  <h5 class="card-title mb-0">Customer</h5>
               </div>
               <div class="card-body">
                  <div class="d-flex align-items-center mb-3">
                     <div class="avatar avatar-lg me-3">
                        <span class="avatar-initial rounded-circle bg-label-primary">
                           {{ strtoupper(substr($payment->booking->user->name, 0, 1)) }}
                        </span>
                     </div>
                     <div>
                        <h6 class="mb-0">{{ $payment->booking->user->name }}</h6>
                        <small class="text-muted">{{ $payment->booking->user->email }}</small>
                     </div>
                  </div>
                  <hr>
                  <small class="text-muted d-block mb-1">No. HP</small>
                  <span class="fw-bold">{{ $payment->booking->user->phone ?? '-' }}</span>
                  <hr>
                  <small class="text-muted d-block mb-1">Rute</small>
                  <span class="fw-bold">
                     {{ $payment->booking->schedule->route->terminalAsal->kota }}
                     &rarr; {{ $payment->booking->schedule->route->terminalTujuan->kota }}
                  </span>
               </div>
            </div>
         </div>
      </div>
   </div>
@endsection
