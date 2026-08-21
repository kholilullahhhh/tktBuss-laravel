@extends('layouts.publicLayout')

@section('title', 'Detail Jadwal')

@section('content')
<section class="section-py bg-primary bg-opacity-10">
  <div class="container">
    <div class="mb-3">
      <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary"><i class="ri-arrow-left-line me-1"></i>Kembali</a>
    </div>
    <div class="card border-0 shadow-lg">
      <div class="card-body p-4 p-md-5">
        <div class="row g-4">
          <div class="col-lg-7">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
              <span class="badge bg-primary">{{ $schedule->bus?->kelas ?? '-' }}</span>
              <span class="fw-semibold fs-5 text-heading">{{ $schedule->bus?->nama_bus ?? '-' }}</span>
              <span class="text-muted"><i class="ri-bus-2-line me-1"></i>{{ $schedule->bus?->operator?->nama_operator ?? '-' }}</span>
            </div>
            <div class="d-flex align-items-center gap-3 py-3 border-bottom">
              <div>
                <div class="text-muted small">Berangkat</div>
                <div class="fw-bold fs-3 text-heading">{{ $schedule->jam_keberangkatan }}</div>
                <div class="text-muted small">{{ $schedule->route?->terminalAsal?->nama_terminal ?? '-' }}</div>
              </div>
              <div class="flex-grow-1 text-center text-muted">
                <i class="ri-arrow-right-line ri-2x"></i>
                <div class="small">{{ $schedule->durasiMenit }} menit</div>
              </div>
              <div class="text-end">
                <div class="text-muted small">Tiba</div>
                <div class="fw-bold fs-3 text-heading">{{ $schedule->jam_tiba }}</div>
                <div class="text-muted small">{{ $schedule->route?->terminalTujuan?->nama_terminal ?? '-' }}</div>
              </div>
            </div>
            <div class="d-flex flex-wrap gap-4 py-3">
              <div>
                <small class="text-muted d-block">Tanggal Keberangkatan</small>
                <span class="fw-semibold">{{ $schedule->tanggal_keberangkatan?->translatedFormat('l, d F Y') ?? '-' }}</span>
              </div>
              <div>
                <small class="text-muted d-block">Kursi Tersisa</small>
                <span class="fw-semibold {{ $availableSeats > 0 ? 'text-success' : 'text-danger' }}">{{ $availableSeats }} kursi</span>
              </div>
              <div>
                <small class="text-muted d-block">Nomor Polisi</small>
                <span class="fw-semibold">{{ $schedule->bus?->nomor_polisi ?? '-' }}</span>
              </div>
            </div>
            @if ($schedule->bus?->fasilitas)
              <div class="py-3">
                <small class="text-muted d-block mb-2">Fasilitas</small>
                <div class="d-flex flex-wrap gap-2">
                  @foreach (explode(',', $schedule->bus->fasilitas) as $fasilitas)
                    <span class="badge bg-label-secondary"><i class="ri-check-line me-1"></i>{{ trim($fasilitas) }}</span>
                  @endforeach
                </div>
              </div>
            @endif
          </div>
          <div class="col-lg-5">
            <div class="card border bg-body">
              <div class="card-body">
                <small class="text-muted d-block">Harga per kursi</small>
                <h3 class="text-heading fw-bold mb-3">Rp {{ number_format((float) $schedule->harga, 0, ',', '.') }}</h3>
                @auth
                  @if ($availableSeats > 0)
                    <a href="{{ route('tickets.seats', ['schedule' => $schedule->id, 'penumpang' => 1]) }}" class="btn btn-primary w-100 btn-lg">
                      <i class="ri-seat-line me-1"></i>Pilih Kursi
                    </a>
                  @else
                    <button class="btn btn-secondary w-100 btn-lg" disabled>Kursi Habis</button>
                  @endif
                @else
                  <a href="{{ route('login') }}" class="btn btn-primary w-100 btn-lg">
                    <i class="ri-login-circle-line me-1"></i>Masuk untuk Memesan
                  </a>
                  <div class="text-center small text-muted mt-2">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a> / <a href="{{ route('register') }}">Daftar</a></div>
                @endauth
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection