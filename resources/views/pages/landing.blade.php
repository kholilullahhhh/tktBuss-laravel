@extends('layouts.publicLayout')

@section('title', 'Pesan Tiket Bus Online')

@section('content')
<!-- Hero Section -->
<section id="landingHero" class="section-py landing-hero position-relative">
  <div class="container">
    <div class="row align-items-center gy-5">
      <div class="col-lg-6 text-center text-lg-start">
        <span class="badge bg-label-primary mb-3"><i class="ri-bus-line me-1"></i>Booking Tiket Bus Online</span>
        <h1 class="text-heading display-4 fw-bold mb-3">
          Perjalanan Nyaman,<br>Tiket Mudah.
        </h1>
        <p class="text-muted mb-4">
          Cari jadwal, pilih kursi, dan bayar dalam hitungan menit. Dapatkan tiket digital
          ber-QR secara instan untuk puluhan operator bus di seluruh Indonesia.
        </p>
        <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
          <a href="#search-form" class="btn btn-primary btn-lg">
            <i class="ri-search-line me-2"></i>Cari Tiket Sekarang
          </a>
          @auth
            <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary btn-lg">
              <i class="ri-dashboard-line me-2"></i>Dashboard Saya
            </a>
          @else
            <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg">
              <i class="ri-user-add-line me-2"></i>Daftar Gratis
            </a>
          @endauth
        </div>
        <div class="d-flex flex-wrap gap-4 mt-4 justify-content-center justify-content-lg-start">
          <div class="text-center">
            <h3 class="mb-0 text-heading fw-bold">10+</h3>
            <small class="text-muted">Operator Bus</small>
          </div>
          <div class="text-center">
            <h3 class="mb-0 text-heading fw-bold">20+</h3>
            <small class="text-muted">Kota Tujuan</small>
          </div>
          <div class="text-center">
            <h3 class="mb-0 text-heading fw-bold">24/7</h3>
            <small class="text-muted">Layanan Pemesanan</small>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card shadow-lg border-0 landing-hero-card">
          <div class="card-body p-4 p-md-5">
            <div class="d-flex align-items-center mb-4">
              <span class="avatar avatar-sm me-3 flex-shrink-0"><span class="avatar-initial rounded bg-label-primary"><i class="ri-bus-2-line"></i></span></span>
              <div>
                <h5 class="mb-0">Cari Tiket Bus</h5>
                <small class="text-muted">Temukan jadwal terbaik untuk perjalanan Anda</small>
              </div>
            </div>
            <form id="search-form" action="{{ route('tickets.search') }}" method="GET">
              <div class="mb-3">
                <label class="form-label">Dari</label>
                <select class="form-select select2" name="terminal_asal_id" required>
                  <option value="">Pilih terminal asal</option>
                  @foreach ($terminals as $terminal)
                    <option value="{{ $terminal->id }}">{{ $terminal->nama_terminal }} ({{ $terminal->kota }})</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Tujuan</label>
                <select class="form-select select2" name="terminal_tujuan_id" required>
                  <option value="">Pilih terminal tujuan</option>
                  @foreach ($terminals as $terminal)
                    <option value="{{ $terminal->id }}">{{ $terminal->nama_terminal }} ({{ $terminal->kota }})</option>
                  @endforeach
                </select>
              </div>
              <div class="row">
                <div class="col-md-7 mb-3">
                  <label class="form-label">Tanggal Keberangkatan</label>
                  <input type="date" class="form-control" name="tanggal" min="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-5 mb-3">
                  <label class="form-label">Jumlah Penumpang</label>
                  <select class="form-select" name="penumpang">
                    @foreach (range(1, 8) as $p)
                      <option value="{{ $p }}">{{ $p }} Penumpang</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <button type="submit" class="btn btn-primary w-100 btn-lg">
                <i class="ri-search-line me-2"></i>Cari Tiket
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /Hero Section -->

<!-- Keunggulan -->
<section class="section-py">
  <div class="container">
    <div class="text-center mb-5">
      <span class="badge bg-label-primary mb-2">Kenapa BusGo?</span>
      <h2 class="text-heading fw-bold">Pesan Tiket Bus Semudah Senyuman</h2>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card h-100 shadow-none border">
          <div class="card-body text-center">
            <span class="avatar avatar-lg mb-3"><span class="avatar-initial rounded bg-label-primary"><i class="ri-shield-check-line ri-24px"></i></span></span>
            <h5 class="fw-semibold">Pembayaran Aman</h5>
            <p class="text-muted mb-0">Bayar melalui Midtrans, Transfer Bank, atau konfirmasi manual yang aman dan terverifikasi.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100 shadow-none border">
          <div class="card-body text-center">
            <span class="avatar avatar-lg mb-3"><span class="avatar-initial rounded bg-label-info"><i class="ri-ticket-2-line ri-24px"></i></span></span>
            <h5 class="fw-semibold">Tiket Digital QR</h5>
            <p class="text-muted mb-0">Tiket ber-QR langsung tersedia setelah pembayaran, bisa diunduh sebagai PDF.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100 shadow-none border">
          <div class="card-body text-center">
            <span class="avatar avatar-lg mb-3"><span class="avatar-initial rounded bg-label-success"><i class="ri-bus-line ri-24px"></i></span></span>
            <h5 class="fw-semibold">Pilihan Operator Lengkap</h5>
            <p class="text-muted mb-0">Bandingkan harga dan fasilitas dari puluhan operator bus terpercaya.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Rute Populer -->
@if ($popularRoutes->isNotEmpty())
<section class="section-py bg-body">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <span class="badge bg-label-primary mb-2">Rute Populer</span>
        <h2 class="text-heading fw-bold mb-0">Destinasi Favorit</h2>
      </div>
      <a href="{{ route('tickets.index') }}" class="btn btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="row g-4">
      @foreach ($popularRoutes as $route)
        <div class="col-md-4">
          <div class="card h-100 border shadow-none">
            <div class="card-body">
              <div class="d-flex align-items-center mb-2">
                <i class="ri-map-pin-2-line text-primary ri-22px me-2"></i>
                <h6 class="mb-0 text-truncate">{{ $route->terminalAsal->kota ?? '-' }}</h6>
                <i class="ri-arrow-right-line mx-2 text-muted"></i>
                <h6 class="mb-0 text-truncate">{{ $route->terminalTujuan->kota ?? '-' }}</h6>
              </div>
              <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted"><i class="ri-calendar-line me-1"></i>{{ $route->schedules_count }} jadwal tersedia</small>
                <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-primary">Pesan</a>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- CTA -->
<section class="section-py">
  <div class="container">
    <div class="card border-0 bg-primary text-white text-center">
      <div class="card-body p-5">
        <h2 class="fw-bold mb-2">Siap Berangkat?</h2>
        <p class="mb-4">Pesan tiket bus Anda sekarang dan nikmati perjalanan yang nyaman.</p>
        <a href="{{ route('tickets.index') }}" class="btn btn-light btn-lg">
          <i class="ri-bus-line me-2"></i>Mulai Pesan Tiket
        </a>
      </div>
    </div>
  </div>
</section>
@endsection

@section('page-script')
<script>
  $(document).ready(function () {
    $('.select2').select2();
  });
</script>
@endsection