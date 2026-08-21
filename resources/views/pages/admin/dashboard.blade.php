@extends('layouts/layoutMaster')

@section('title', 'Dashboard Admin')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.scss') }}">
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <small class="text-muted">Pendapatan Bulan Ini</small>
              <h4 class="mb-0 text-heading fw-bold">Rp {{ number_format((float) $stats['monthRevenue'], 0, ',', '.') }}</h4>
              <small class="text-success"><i class="ri-arrow-up-line"></i> Rp {{ number_format((float) $stats['todayRevenue'], 0, ',', '.') }} hari ini</small>
            </div>
            <span class="avatar avatar-sm"><span class="avatar-initial rounded bg-label-success"><i class="ri-money-dollar-circle-line"></i></span></span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <small class="text-muted">Total Booking</small>
              <h4 class="mb-0 text-heading fw-bold">{{ $stats['totalBookings'] }}</h4>
              <small class="text-warning"><i class="ri-time-line"></i> {{ $stats['pendingBookings'] }} pending</small>
            </div>
            <span class="avatar avatar-sm"><span class="avatar-initial rounded bg-label-primary"><i class="ri-shopping-basket-line"></i></span></span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <small class="text-muted">Bus Aktif</small>
              <h4 class="mb-0 text-heading fw-bold">{{ $stats['totalBuses'] }}</h4>
              <small class="text-muted">{{ $stats['activeSchedules'] }} jadwal aktif</small>
            </div>
            <span class="avatar avatar-sm"><span class="avatar-initial rounded bg-label-info"><i class="ri-bus-line"></i></span></span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <small class="text-muted">Jaringan</small>
              <h4 class="mb-0 text-heading fw-bold">{{ $stats['totalTerminals'] }} <small class="fs-6 text-muted">terminal</small></h4>
              <small class="text-muted">{{ $stats['totalRoutes'] }} rute &middot; {{ $stats['totalOperators'] }} operator &middot; {{ $stats['totalCustomers'] }} customer</small>
            </div>
            <span class="avatar avatar-sm"><span class="avatar-initial rounded bg-label-warning"><i class="ri-road-map-line"></i></span></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-header">
          <h5 class="mb-0">Grafik Pendapatan &amp; Booking (12 Bulan)</h5>
        </div>
        <div class="card-body">
          <div id="revenueChart"></div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header"><h6 class="mb-0">Rute Terlaris</h6></div>
        <div class="card-body">
          @forelse ($topRoutes as $route)
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="small text-truncate me-2">{{ $route->nama }}</span>
              <span class="badge bg-label-primary">{{ $route->total }}</span>
            </div>
          @empty
            <div class="text-muted small text-center py-3">Belum ada transaksi.</div>
          @endforelse
        </div>
      </div>
      <div class="card border-0 shadow-sm">
        <div class="card-header"><h6 class="mb-0">Operator Terlaris</h6></div>
        <div class="card-body">
          @forelse ($topOperators as $operator)
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="small text-truncate me-2">{{ $operator->nama }}</span>
              <span class="badge bg-label-info">{{ $operator->total }}</span>
            </div>
          @empty
            <div class="text-muted small text-center py-3">Belum ada transaksi.</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-7">
      <div class="card border-0 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Booking Terbaru</h5>
          <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
        </div>
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Customer</th>
                <th>Rute</th>
                <th>Status</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($recentBookings as $booking)
                <tr>
                  <td class="fw-semibold">{{ $booking->kode_booking }}</td>
                  <td>{{ $booking->user?->name ?? '-' }}</td>
                  <td class="small">{{ $booking->schedule?->route?->terminalAsal?->kota ?? '-' }} &rarr; {{ $booking->schedule?->route?->terminalTujuan?->kota ?? '-' }}</td>
                  <td>
                    <span class="badge {{ $booking->status_booking === 'cancelled' ? 'bg-label-danger' : ($booking->status_booking === 'confirmed' || $booking->status_booking === 'completed' ? 'bg-label-success' : 'bg-label-warning') }}">
                      {{ strtoupper($booking->status_booking) }}
                    </span>
                  </td>
                  <td class="text-center">
                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary"><i class="ri-eye-line"></i></a>
                  </td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada booking.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="card border-0 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Pembayaran Terbaru</h5>
          <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
        </div>
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>Order</th>
                <th>Customer</th>
                <th>Metode</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($recentPayments as $payment)
                <tr>
                  <td class="fw-semibold small">{{ $payment->order_id }}</td>
                  <td class="small">{{ $payment->booking?->user?->name ?? '-' }}</td>
                  <td class="small">{{ $payment->payment_type ? strtoupper($payment->payment_type) : '-' }}</td>
                  <td>
                    <span class="badge {{ $payment->payment_status === 'paid' ? 'bg-label-success' : ($payment->payment_status === 'failed' || $payment->payment_status === 'expired' ? 'bg-label-danger' : 'bg-label-warning') }}">
                      {{ strtoupper($payment->payment_status) }}
                    </span>
                  </td>
                </tr>
              @empty
                <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada pembayaran.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const labels = @json($chart['labels']);
    const revenue = @json($chart['revenue']);
    const bookings = @json($chart['bookings']);

    new ApexCharts(document.querySelector('#revenueChart'), {
      chart: { type: 'line', height: 320, toolbar: { show: false } },
      series: [
        { name: 'Pendapatan', type: 'line', data: revenue },
        { name: 'Booking', type: 'column', data: bookings }
      ],
      xaxis: { categories: labels },
      stroke: { width: 3 },
      colors: ['#666cff', '#26d07c'],
      yaxis: { labels: { formatter: function (v) { return 'Rp ' + Number(v).toLocaleString('id-ID'); } } },
      legend: { position: 'top' },
      grid: { borderColor: '#eceef1' }
    }).render();
  });
</script>
@endsection