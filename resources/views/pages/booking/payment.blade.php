@extends('layouts.layoutMaster')

@section('title', 'Pembayaran Booking')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h4 class="fw-bold mb-0">
      <span class="text-muted fw-light">Booking /</span> Pembayaran
    </h4>
    <a href="{{ route('booking.show', $booking->id) }}" class="btn btn-outline-secondary">
      <i class="ri-arrow-left-line me-1"></i> Kembali
    </a>
  </div>

  @if ($booking->isPaid())
    <div class="alert alert-success">
      <i class="ri-check-double-line me-1"></i> Pembayaran untuk <strong>{{ $booking->kode_booking }}</strong> sudah lunas.
      <a href="{{ route('booking.ticket', $booking->id) }}" class="alert-link">Lihat tiket Anda.</a>
    </div>
  @else
    <div class="row g-4">
      <div class="col-lg-7">
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0"><i class="ri-bank-card-line me-2 text-primary"></i>Ringkasan Pesanan</h5>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Kode Booking</span>
              <span class="fw-semibold">{{ $booking->kode_booking }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Rute</span>
              <span class="fw-semibold">{{ $booking->schedule?->route?->terminalAsal?->kota ?? '-' }} &rarr; {{ $booking->schedule?->route?->terminalTujuan?->kota ?? '-' }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Jadwal</span>
              <span class="fw-semibold">{{ $booking->schedule?->tanggal_keberangkatan?->translatedFormat('d M Y') ?? '-' }} {{ $booking->schedule?->jam_keberangkatan ?? '-' }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Jumlah Kursi</span>
              <span class="fw-semibold">{{ $booking->seats->count() }} kursi ({{ $booking->seats->map(fn ($s) => $s->seat?->nomor_kursi)->implode(', ') }})</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between align-items-center">
              <span class="fw-bold">Total Tagihan</span>
              <span class="fw-bold fs-4 text-heading">Rp {{ number_format((float) $booking->total_harga, 0, ',', '.') }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0"><i class="ri-wallet-3-line me-2 text-primary"></i>Metode Pembayaran</h5>
          </div>
          <div class="card-body">
            @if ($snapUrl)
              <div class="mb-3">
                <button type="button" class="btn btn-primary btn-lg w-100" id="pay-snap">
                  <i class="ri-bank-card-line me-2"></i>Bayar Sekarang (Midtrans)
                </button>
                <small class="text-muted d-block text-center mt-2">Transfer Bank, E-Wallet, QRIS, dan kartu kredit.</small>
              </div>
            @else
              <div class="mb-3">
                <div class="alert alert-info mb-3">
                  <i class="ri-information-line me-1"></i>
                  Pembayaran online sedang tidak tersedia. Silakan hubungi admin untuk konfirmasi pembayaran.
                </div>
                <div class="d-grid">
                  <button type="button" class="btn btn-primary btn-lg" id="pay-manual">
                    <i class="ri-hand-coin-line me-2"></i>Konfirmasi ke Admin
                  </button>
                </div>
                <small class="text-muted d-block text-center mt-2">
                  Hubungi {{ config('variables.contactPhone', '-') }} atau {{ config('variables.contactEmail', '-') }}
                </small>
              </div>
            @endif

            <hr>
            <div class="alert alert-warning mb-0 py-2 small">
              <i class="ri-time-line me-1"></i>
              Batas pembayaran: <strong>{{ $booking->expired_at?->translatedFormat('d M Y H:i') ?? '-' }}</strong>.
              Setelah itu booking otomatis dibatalkan.
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>
@endsection

@section('page-script')
@if ($snapUrl)
  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
  <script>
    $('#pay-snap').on('click', function () {
      snap.pay('{{ $snapUrl }}', {
        onSuccess: function () { window.location.href = '{{ route("booking.show", $booking->id) }}'; },
        onPending: function () { window.location.href = '{{ route("booking.show", $booking->id) }}'; },
        onError: function () { window.AlertHandler.showError('Pembayaran gagal. Silakan coba lagi.'); }
      });
    });
  </script>
@else
  <script>
    $('#pay-manual').on('click', function () {
      window.AlertHandler.showSuccess('Permintaan konfirmasi pembayaran akan diproses admin. Silakan hubungi admin.', true);
    });
  </script>
@endif
@endsection