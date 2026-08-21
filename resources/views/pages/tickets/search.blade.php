@extends('layouts.publicLayout')

@section('title', 'Hasil Pencarian Tiket')

@section('content')
<section class="section-py bg-primary bg-opacity-10">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
      <div>
        <h4 class="text-heading fw-bold mb-0">
          <i class="ri-arrow-right-circle-line me-2 text-primary"></i>
          {{ $schedules->first()?->route?->terminalAsal?->kota ?? '-' }}
          <i class="ri-arrow-right-line mx-2 text-muted"></i>
          {{ $schedules->first()?->route?->terminalTujuan?->kota ?? '-' }}
        </h4>
        <small class="text-muted">{{ \Carbon\Carbon::parse($filters['tanggal'])->translatedFormat('l, d F Y') }} &middot; {{ $penumpang }} penumpang</small>
      </div>
      <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary"><i class="ri-arrow-left-line me-1"></i>Ubah Pencarian</a>
    </div>

    <div class="row g-4">
      <div class="col-lg-3">
        <div class="card border shadow-none mb-4">
          <div class="card-header"><h6 class="mb-0">Filter</h6></div>
          <div class="card-body">
            <label class="form-label small fw-semibold">Operator</label>
            <div class="d-flex flex-column gap-2 mb-3" id="operator-filter">
              <label class="form-check">
                <input class="form-check-input filter-operator" type="checkbox" value="all" checked>
                <span class="form-check-label">Semua</span>
              </label>
              @foreach ($operators as $operator)
                <label class="form-check">
                  <input class="form-check-input filter-operator" type="checkbox" value="{{ $operator->id }}">
                  <span class="form-check-label">{{ $operator->nama_operator }}</span>
                </label>
              @endforeach
            </div>
            <label class="form-label small fw-semibold">Kelas</label>
            <div class="d-flex flex-column gap-2" id="kelas-filter">
              <label class="form-check">
                <input class="form-check-input filter-kelas" type="checkbox" value="all" checked>
                <span class="form-check-label">Semua</span>
              </label>
              @foreach ($schedules->pluck('bus.kelas')->unique()->filter() as $kelas)
                <label class="form-check">
                  <input class="form-check-input filter-kelas" type="checkbox" value="{{ $kelas }}">
                  <span class="form-check-label">{{ $kelas }}</span>
                </label>
              @endforeach
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-9">
        @if ($schedules->isEmpty())
          <div class="card border shadow-none">
            <div class="card-body text-center py-5">
              <i class="ri-search-eye-line ri-3x text-muted mb-3"></i>
              <h5 class="text-heading">Tidak ada jadwal tersedia</h5>
              <p class="text-muted mb-0">Tidak ditemukan jadwal untuk rute dan tanggal tersebut. Coba ubah pencarian Anda.</p>
            </div>
          </div>
        @else
          <div class="d-flex flex-column gap-3" id="schedule-list">
            @foreach ($schedules as $schedule)
              @php
                $sisa = $schedule->availableSeatsCount();
                $tersedia = $sisa >= $penumpang;
              @endphp
              <div class="card border shadow-none schedule-card"
                data-operator="{{ $schedule->bus?->operator?->id ?? '' }}"
                data-kelas="{{ $schedule->bus?->kelas ?? '' }}">
                <div class="card-body">
                  <div class="row align-items-center g-3">
                    <div class="col-lg-5">
                      <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-label-primary me-2">{{ $schedule->bus?->kelas ?? '-' }}</span>
                        <span class="fw-semibold text-heading">{{ $schedule->bus?->nama_bus ?? '-' }}</span>
                      </div>
                      <div class="d-flex align-items-center gap-2 text-muted small mb-1">
                        <i class="ri-bus-2-line"></i>{{ $schedule->bus?->operator?->nama_operator ?? '-' }}
                      </div>
                      <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold fs-5 text-heading">{{ $schedule->jam_keberangkatan }}</span>
                        <i class="ri-arrow-right-line text-muted"></i>
                        <span class="fw-bold fs-5 text-heading">{{ $schedule->jam_tiba }}</span>
                        <small class="text-muted">({{ $schedule->durasiMenit }} menit)</small>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="small">
                        <div class="fw-semibold text-heading">{{ $schedule->route?->terminalAsal?->nama_terminal ?? '-' }}</div>
                        <div class="text-muted">{{ $schedule->route?->terminalTujuan?->nama_terminal ?? '-' }}</div>
                        <div class="text-muted mt-1"><i class="ri-seat-line me-1"></i>Sisa {{ $sisa }} kursi</div>
                      </div>
                    </div>
                    <div class="col-lg-3 text-lg-end">
                      <div class="mb-2">
                        <small class="text-muted">Harga per kursi</small>
                        <h4 class="mb-0 text-heading fw-bold">Rp {{ number_format((float) $schedule->harga, 0, ',', '.') }}</h4>
                      </div>
                      @if ($tersedia)
                        <a href="{{ route('tickets.seats', [$schedule->id, 'penumpang' => $penumpang]) }}" class="btn btn-primary">
                          Pilih Kursi <i class="ri-arrow-right-line ms-1"></i>
                        </a>
                      @else
                        <span class="badge bg-label-danger">Kursi Habis</span>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
@endsection

@section('page-script')
<script>
  $(document).ready(function () {
    function applyFilter() {
      const op = $('.filter-operator:checked').map(function () { return $(this).val(); }).get();
      const kelas = $('.filter-kelas:checked').map(function () { return $(this).val(); }).get();

      $('.schedule-card').each(function () {
        const $card = $(this);
        const opOk = op.includes('all') || op.includes($card.data('operator'));
        const kelasOk = kelas.includes('all') || kelas.includes($card.data('kelas'));
        $card.toggle(opOk && kelasOk);
      });
    }

    $('.filter-operator').on('change', function () {
      if ($(this).val() === 'all' && $(this).is(':checked')) {
        $('#operator-filter input[value!="all"]').prop('checked', false);
      } else if ($(this).val() !== 'all') {
        $('#operator-filter input[value="all"]').prop('checked', false);
      }
      applyFilter();
    });

    $('.filter-kelas').on('change', function () {
      if ($(this).val() === 'all' && $(this).is(':checked')) {
        $('#kelas-filter input[value!="all"]').prop('checked', false);
      } else if ($(this).val() !== 'all') {
        $('#kelas-filter input[value="all"]').prop('checked', false);
      }
      applyFilter();
    });
  });
</script>
@endsection