@extends('layouts.layoutMaster')

@section('title', 'Tiket Saya')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h4 class="fw-bold mb-0">
      <span class="text-muted fw-light">Customer /</span> Tiket Saya
    </h4>
    <a href="{{ route('tickets.index') }}" class="btn btn-primary"><i class="ri-search-line me-1"></i> Cari Tiket</a>
  </div>

  <div class="d-flex flex-column gap-3">
    @forelse ($data as $booking)
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <div class="row align-items-center g-3">
            <div class="col-lg-3">
              <small class="text-muted d-block">Kode Booking</small>
              <span class="fw-bold text-heading">{{ $booking->kode_booking }}</span>
              <div class="small text-muted">
                {{ $booking->seats->map(fn ($s) => $s->seat?->nomor_kursi)->implode(', ') }}
              </div>
            </div>
            <div class="col-lg-5">
              <div class="fw-semibold text-heading">
                {{ $booking->schedule?->route?->terminalAsal?->kota ?? '-' }} <i class="ri-arrow-right-line mx-1 text-muted"></i> {{ $booking->schedule?->route?->terminalTujuan?->kota ?? '-' }}
              </div>
              <div class="text-muted small">
                {{ $booking->schedule?->tanggal_keberangkatan?->translatedFormat('l, d M Y') ?? '-' }} &middot;
                {{ $booking->schedule?->jam_keberangkatan ?? '-' }} &middot; {{ $booking->schedule?->bus?->kelas ?? '-' }}
              </div>
            </div>
            <div class="col-lg-2 text-lg-end">
              <small class="text-muted d-block">Total</small>
              <span class="fw-bold text-heading">Rp {{ number_format((float) $booking->total_harga, 0, ',', '.') }}</span>
            </div>
            <div class="col-lg-2 text-lg-end">
              <a href="{{ route('booking.ticket', $booking->id) }}" class="btn btn-sm btn-primary w-100 mb-1"><i class="ri-ticket-2-line me-1"></i>Lihat</a>
              <a href="{{ route('booking.ticket.pdf', $booking->id) }}" class="btn btn-sm btn-outline-primary w-100"><i class="ri-file-pdf-line me-1"></i>PDF</a>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
          <i class="ri-ticket-2-line ri-3x text-muted mb-2"></i>
          <p class="mb-2">Belum ada tiket aktif. Tiket muncul setelah pembayaran Anda lunas.</p>
          <a href="{{ route('tickets.index') }}" class="btn btn-primary">Cari Tiket Sekarang</a>
        </div>
      </div>
    @endforelse
  </div>
</div>
@endsection