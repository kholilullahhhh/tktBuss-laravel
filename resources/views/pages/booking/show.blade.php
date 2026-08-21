@extends('layouts.layoutMaster')

@section('title', 'Detail Booking')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h4 class="fw-bold mb-0">
      <span class="text-muted fw-light">Booking /</span> {{ $booking->kode_booking }}
    </h4>
    <div class="d-flex gap-2">
      <a href="{{ route('customer.bookings') }}" class="btn btn-outline-secondary">
        <i class="ri-arrow-left-line me-1"></i> Kembali
      </a>
      @if ($booking->isPaid())
        <a href="{{ route('booking.ticket', $booking->id) }}" class="btn btn-primary">
          <i class="ri-ticket-2-line me-1"></i> Lihat Tiket
        </a>
        <a href="{{ route('booking.ticket.pdf', $booking->id) }}" class="btn btn-outline-primary">
          <i class="ri-file-pdf-line me-1"></i> PDF
        </a>
      @else
        <a href="{{ route('booking.pay', $booking->id) }}" class="btn btn-primary">
          <i class="ri-bank-card-line me-1"></i> Bayar Sekarang
        </a>
      @endif
    </div>
  </div>

  @if ($booking->status_booking === 'pending' && $booking->expired_at)
    <div class="alert alert-warning">
      <i class="ri-time-line me-1"></i>
      Selesaikan pembayaran sebelum
      <strong>{{ $booking->expired_at->translatedFormat('d M Y H:i') }}</strong> atau booking akan dibatalkan otomatis.
    </div>
  @endif

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0"><i class="ri-road-map-line me-2 text-primary"></i>Detail Perjalanan</h5>
          <span class="badge {{ $booking->status_booking === 'cancelled' ? 'bg-label-danger' : ($booking->status_booking === 'confirmed' || $booking->status_booking === 'completed' ? 'bg-label-success' : 'bg-label-warning') }}">
            {{ strtoupper($booking->status_booking) }}
          </span>
        </div>
        <div class="card-body">
          <div class="d-flex align-items-center gap-3 py-2">
            <div>
              <div class="text-muted small">Berangkat</div>
              <div class="fw-bold fs-4 text-heading">{{ $booking->schedule?->jam_keberangkatan ?? '-' }}</div>
              <div class="fw-semibold">{{ $booking->schedule?->route?->terminalAsal?->nama_terminal ?? '-' }}</div>
            </div>
            <div class="flex-grow-1 text-center text-muted">
              <i class="ri-arrow-right-line ri-2x"></i>
              <div class="small">{{ $booking->schedule?->durasiMenit ?? 0 }} menit</div>
            </div>
            <div class="text-end">
              <div class="text-muted small">Tiba</div>
              <div class="fw-bold fs-4 text-heading">{{ $booking->schedule?->jam_tiba ?? '-' }}</div>
              <div class="fw-semibold">{{ $booking->schedule?->route?->terminalTujuan?->nama_terminal ?? '-' }}</div>
            </div>
          </div>
          <hr>
          <div class="row g-3">
            <div class="col-md-4">
              <small class="text-muted d-block">Tanggal Keberangkatan</small>
              <span class="fw-semibold">{{ $booking->schedule?->tanggal_keberangkatan?->translatedFormat('l, d F Y') ?? '-' }}</span>
            </div>
            <div class="col-md-4">
              <small class="text-muted d-block">Operator / Bus</small>
              <span class="fw-semibold">{{ $booking->schedule?->bus?->operator?->nama_operator ?? '-' }} &middot; {{ $booking->schedule?->bus?->nama_bus ?? '-' }}</span>
            </div>
            <div class="col-md-4">
              <small class="text-muted d-block">Kelas</small>
              <span class="fw-semibold">{{ $booking->schedule?->bus?->kelas ?? '-' }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0"><i class="ri-user-2-line me-2 text-primary"></i>Data Penumpang &amp; Kursi</h5>
        </div>
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>Kursi</th>
                <th>Nama Penumpang</th>
                <th>NIK</th>
                <th>No. HP</th>
                <th class="text-end">Harga</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($booking->seats as $seat)
                <tr>
                  <td><span class="badge bg-label-primary">{{ $seat->seat?->nomor_kursi ?? '-' }}</span></td>
                  <td class="fw-semibold">{{ $seat->nama_penumpang }}</td>
                  <td>{{ $seat->nik ?: '-' }}</td>
                  <td>{{ $seat->no_hp ?: '-' }}</td>
                  <td class="text-end">Rp {{ number_format((float) $seat->harga, 0, ',', '.') }}</td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center py-4">Tidak ada data penumpang.</td></tr>
              @endforelse
              <tr class="table-light">
                <td colspan="4" class="text-end fw-bold">Total</td>
                <td class="text-end fw-bold">Rp {{ number_format((float) $booking->total_harga, 0, ',', '.') }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0"><i class="ri-information-line me-2 text-primary"></i>Status Pembayaran</h5>
        </div>
        <div class="card-body">
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Kode Booking</span>
            <span class="fw-semibold">{{ $booking->kode_booking }}</span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Status Booking</span>
            <span class="badge {{ $booking->status_booking === 'cancelled' ? 'bg-label-danger' : ($booking->status_booking === 'confirmed' || $booking->status_booking === 'completed' ? 'bg-label-success' : 'bg-label-warning') }}">
              {{ strtoupper($booking->status_booking) }}
            </span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Status Pembayaran</span>
            <span class="badge {{ $booking->isPaid() ? 'bg-label-success' : 'bg-label-warning' }}">
              {{ strtoupper($booking->status_pembayaran) }}
            </span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Metode</span>
            <span class="fw-semibold">{{ $booking->payment_method ? strtoupper($booking->payment_method) : '-' }}</span>
          </div>
          <div class="d-flex justify-content-between">
            <span class="text-muted">Tanggal Booking</span>
            <span class="fw-semibold">{{ $booking->tanggal_booking?->translatedFormat('d M Y') ?? '-' }}</span>
          </div>

          <hr>

          @if ($booking->isPaid())
            <div class="alert alert-success mb-0 py-2">
              <i class="ri-check-double-line me-1"></i> Pembayaran lunas. Tiket Anda aktif.
            </div>
          @elseif (in_array($booking->status_booking, ['pending', 'confirmed']) && ! $booking->isPaid())
            <div class="d-grid gap-2">
              <a href="{{ route('booking.pay', $booking->id) }}" class="btn btn-primary">
                <i class="ri-bank-card-line me-1"></i> Lanjutkan Pembayaran
              </a>
              <button type="button" class="btn btn-outline-danger" id="btn-cancel">
                <i class="ri-close-circle-line me-1"></i> Batalkan Booking
              </button>
              <form id="cancel-form" action="{{ route('booking.cancel', $booking->id) }}" method="POST" class="d-none">
                @csrf
              </form>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
  $('#btn-cancel').on('click', function () {
    window.AlertHandler.confirm(
      'Batalkan Booking?',
      'Booking akan dibatalkan dan kursi dilepas. Tindakan ini tidak dapat dibatalkan.',
      'Ya, Batalkan!',
      function () {
        $('#cancel-form').submit();
      }
    );
  });
</script>
@endsection