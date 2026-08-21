@extends('layouts.publicLayout')

@section('title', 'Cari Tiket Bus')

@section('content')
<section class="section-py bg-primary bg-opacity-10">
  <div class="container">
    <div class="text-center mb-4">
      <h1 class="text-heading fw-bold">Cari Tiket Bus</h1>
      <p class="text-muted mb-0">Masukkan kota asal, tujuan, dan tanggal keberangkatan.</p>
    </div>
    <div class="card shadow-lg border-0">
      <div class="card-body p-4 p-md-5">
        <form action="{{ route('tickets.search') }}" method="GET">
          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label">Dari</label>
              <select class="form-select select2" name="terminal_asal_id" required>
                <option value="">Pilih asal</option>
                @foreach ($terminals as $terminal)
                  <option value="{{ $terminal->id }}">{{ $terminal->nama_terminal }} ({{ $terminal->kota }})</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Tujuan</label>
              <select class="form-select select2" name="terminal_tujuan_id" required>
                <option value="">Pilih tujuan</option>
                @foreach ($terminals as $terminal)
                  <option value="{{ $terminal->id }}">{{ $terminal->nama_terminal }} ({{ $terminal->kota }})</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Tanggal</label>
              <input type="date" class="form-control" name="tanggal" min="{{ date('Y-m-d') }}" value="{{ old('tanggal', date('Y-m-d')) }}" required>
            </div>
            <div class="col-md-2">
              <label class="form-label">Penumpang</label>
              <select class="form-select" name="penumpang">
                @foreach (range(1, 8) as $p)
                  <option value="{{ $p }}" {{ old('penumpang', 1) == $p ? 'selected' : '' }}>{{ $p }} Orang</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
              <button type="submit" class="btn btn-primary w-100"><i class="ri-search-line"></i></button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<section class="section-py">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-md-4 text-center">
        <span class="avatar avatar-xl mb-3"><span class="avatar-initial rounded-circle bg-label-primary"><i class="ri-ticket-2-line ri-32px"></i></span></span>
        <h5 class="fw-semibold">1. Cari Jadwal</h5>
        <p class="text-muted mb-0">Pilih rute dan tanggal keberangkatan sesuai rencana Anda.</p>
      </div>
      <div class="col-md-4 text-center">
        <span class="avatar avatar-xl mb-3"><span class="avatar-initial rounded-circle bg-label-info"><i class="ri-seat-line ri-32px"></i></span></span>
        <h5 class="fw-semibold">2. Pilih Kursi</h5>
        <p class="text-muted mb-0">Pilih kursi favorit dan isi data penumpang dengan mudah.</p>
      </div>
      <div class="col-md-4 text-center">
        <span class="avatar avatar-xl mb-3"><span class="avatar-initial rounded-circle bg-label-success"><i class="ri-bank-card-line ri-32px"></i></span></span>
        <h5 class="fw-semibold">3. Bayar & Naik</h5>
        <p class="text-muted mb-0">Selesaikan pembayaran dan tunjukkan tiket QR saat boarding.</p>
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