@extends('layouts.layoutMaster')

@section('title', 'Booking Saya')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h4 class="fw-bold mb-0">
      <span class="text-muted fw-light">Customer /</span> Booking Saya
    </h4>
    <a href="{{ route('tickets.index') }}" class="btn btn-primary"><i class="ri-search-line me-1"></i> Cari Tiket</a>
  </div>

  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
      <form method="GET">
        <div class="d-flex gap-2 flex-wrap">
          <select name="status" class="form-select" style="max-width: 200px">
            <option value="">Semua Status</option>
            @foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $status)
              <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
            @endforeach
          </select>
          <button type="submit" class="btn btn-outline-primary"><i class="ri-filter-line me-1"></i>Filter</button>
          @if (request('status'))
            <a href="{{ route('customer.bookings') }}" class="btn btn-outline-secondary">Reset</a>
          @endif
        </div>
      </form>
    </div>
  </div>

  <div class="d-flex flex-column gap-3">
    @forelse ($data as $booking)
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <div class="row align-items-center g-3">
            <div class="col-lg-3">
              <small class="text-muted d-block">Kode Booking</small>
              <span class="fw-bold text-heading">{{ $booking->kode_booking }}</span>
              <div>
                <span class="badge {{ $booking->status_booking === 'cancelled' ? 'bg-label-danger' : ($booking->status_booking === 'confirmed' || $booking->status_booking === 'completed' ? 'bg-label-success' : 'bg-label-warning') }}">
                  {{ strtoupper($booking->status_booking) }}
                </span>
                <span class="badge {{ $booking->isPaid() ? 'bg-label-success' : 'bg-label-warning' }}">PAY: {{ strtoupper($booking->status_pembayaran) }}</span>
              </div>
            </div>
            <div class="col-lg-5">
              <div class="fw-semibold text-heading">
                {{ $booking->schedule?->route?->terminalAsal?->kota ?? '-' }} <i class="ri-arrow-right-line mx-1 text-muted"></i> {{ $booking->schedule?->route?->terminalTujuan?->kota ?? '-' }}
              </div>
              <div class="text-muted small">
                {{ $booking->schedule?->tanggal_keberangkatan?->translatedFormat('l, d M Y') ?? '-' }} &middot;
                {{ $booking->schedule?->jam_keberangkatan ?? '-' }} &middot;
                {{ $booking->schedule?->bus?->operator?->nama_operator ?? '-' }}
              </div>
              <div class="small">
                <span class="badge bg-label-primary">{{ $booking->seats->count() }} kursi</span>
              </div>
            </div>
            <div class="col-lg-2 text-lg-end">
              <small class="text-muted d-block">Total</small>
              <span class="fw-bold text-heading">Rp {{ number_format((float) $booking->total_harga, 0, ',', '.') }}</span>
            </div>
            <div class="col-lg-2 text-lg-end">
              <a href="{{ route('booking.show', $booking->id) }}" class="btn btn-sm btn-outline-primary mb-1 w-100">Detail</a>
              @if ($booking->isPaid())
                <a href="{{ route('booking.ticket', $booking->id) }}" class="btn btn-sm btn-primary w-100">Tiket</a>
              @elseif (in_array($booking->status_booking, ['pending', 'confirmed']))
                <a href="{{ route('booking.pay', $booking->id) }}" class="btn btn-sm btn-primary w-100">Bayar</a>
              @endif
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
          <i class="ri-shopping-basket-line ri-3x text-muted mb-2"></i>
          <p class="mb-2">Belum ada booking. Ayo pesan tiket bus pertama Anda!</p>
          <a href="{{ route('tickets.index') }}" class="btn btn-primary">Cari Tiket Sekarang</a>
        </div>
      </div>
    @endforelse
  </div>
</div>
@endsection