@extends('layouts.layoutMaster')

@section('title', 'Dashboard Customer')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <small class="text-muted">Tiket Aktif</small>
              <h4 class="mb-0 text-heading fw-bold">{{ $stats['tiketAktif'] }}</h4>
            </div>
            <span class="avatar avatar-sm"><span class="avatar-initial rounded bg-label-success"><i class="ri-ticket-2-line"></i></span></span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <small class="text-muted">Booking Pending</small>
              <h4 class="mb-0 text-heading fw-bold">{{ $stats['pending'] }}</h4>
            </div>
            <span class="avatar avatar-sm"><span class="avatar-initial rounded bg-label-warning"><i class="ri-time-line"></i></span></span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <small class="text-muted">Total Perjalanan</small>
              <h4 class="mb-0 text-heading fw-bold">{{ $stats['totalPerjalanan'] }}</h4>
            </div>
            <span class="avatar avatar-sm"><span class="avatar-initial rounded bg-label-info"><i class="ri-bus-line"></i></span></span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <small class="text-muted">Total Belanja</small>
              <h4 class="mb-0 text-heading fw-bold">Rp {{ number_format((float) $stats['totalBelanja'], 0, ',', '.') }}</h4>
            </div>
            <span class="avatar avatar-sm"><span class="avatar-initial rounded bg-label-primary"><i class="ri-wallet-3-line"></i></span></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Booking Terbaru</h5>
      <div class="d-flex gap-2">
        <a href="{{ route('customer.bookings') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
        <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-primary"><i class="ri-search-line me-1"></i>Cari Tiket</a>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-hover mb-0">
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
          @forelse ($recentBookings as $booking)
            <tr>
              <td class="fw-semibold">{{ $booking->kode_booking }}</td>
              <td>{{ $booking->schedule?->route?->terminalAsal?->kota ?? '-' }} &rarr; {{ $booking->schedule?->route?->terminalTujuan?->kota ?? '-' }}</td>
              <td>
                {{ $booking->schedule?->tanggal_keberangkatan?->translatedFormat('d M Y') ?? '-' }}
                <br><small class="text-muted">{{ $booking->schedule?->jam_keberangkatan ?? '-' }}</small>
              </td>
              <td>Rp {{ number_format((float) $booking->total_harga, 0, ',', '.') }}</td>
              <td>
                <span class="badge {{ $booking->status_booking === 'cancelled' ? 'bg-label-danger' : ($booking->status_booking === 'confirmed' || $booking->status_booking === 'completed' ? 'bg-label-success' : 'bg-label-warning') }}">
                  {{ strtoupper($booking->status_booking) }}
                </span>
              </td>
              <td class="text-center">
                <a href="{{ route('booking.show', $booking->id) }}" class="btn btn-sm btn-outline-primary"><i class="ri-eye-line"></i></a>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center py-5">
              <i class="ri-shopping-basket-line ri-3x text-muted mb-2"></i>
              <p class="mb-2">Belum ada booking.</p>
              <a href="{{ route('tickets.index') }}" class="btn btn-primary btn-sm">Cari Tiket Sekarang</a>
            </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection