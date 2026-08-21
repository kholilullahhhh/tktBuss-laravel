@extends('layouts.publicLayout')

@section('title', 'Pilih Kursi')

@section('content')
<section class="section-py bg-primary bg-opacity-10">
  <div class="container">
    <div class="mb-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
      <a href="{{ route('tickets.search', ['terminal_asal_id' => $schedule->route?->terminal_asal_id, 'terminal_tujuan_id' => $schedule->route?->terminal_tujuan_id, 'tanggal' => $schedule->tanggal_keberangkatan?->toDateString(), 'penumpang' => $penumpang]) }}" class="btn btn-sm btn-outline-secondary"><i class="ri-arrow-left-line me-1"></i>Ubah Pencarian</a>
      <span class="badge bg-primary">{{ $schedule->bus?->nama_bus ?? '-' }} &middot; {{ $schedule->bus?->kelas ?? '-' }}</span>
    </div>

    <div class="card border-0 shadow-lg mb-4">
      <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
          <div>
            <div class="fw-semibold fs-5 text-heading">
              {{ $schedule->route?->terminalAsal?->kota ?? '-' }} <i class="ri-arrow-right-line mx-2 text-muted"></i> {{ $schedule->route?->terminalTujuan?->kota ?? '-' }}
            </div>
            <div class="text-muted small">
              {{ $schedule->tanggal_keberangkatan?->translatedFormat('l, d F Y') ?? '-' }} &middot;
              {{ $schedule->jam_keberangkatan }} - {{ $schedule->jam_tiba }} &middot;
              {{ $schedule->bus?->operator?->nama_operator ?? '-' }}
            </div>
          </div>
          <div class="text-end">
            <small class="text-muted d-block">Harga per kursi</small>
            <span class="fw-bold fs-4 text-heading">Rp {{ number_format((float) $schedule->harga, 0, ',', '.') }}</span>
          </div>
        </div>
      </div>
    </div>

    <form id="booking-form" action="{{ route('tickets.store', $schedule->id) }}" method="POST">
      @csrf
      <div class="row g-4">
        <div class="col-lg-7">
          <div class="card border-0 shadow-lg">
            <div class="card-header">
              <h5 class="mb-0"><i class="ri-seat-line me-2 text-primary"></i>Pilih Kursi (maks. {{ $penumpang }})</h5>
            </div>
            <div class="card-body">
              <div class="d-flex flex-wrap gap-3 mb-4">
                <span class="badge bg-label-success">Pilih</span>
                <span class="badge bg-label-secondary">Tersedia</span>
                <span class="badge bg-label-danger">Terisi</span>
              </div>

              @php
                $seats = collect($seatAvailability);
                $rows = $seats->groupBy(fn ($s) => (int) preg_replace('/\D/', '', $s['nomor_kursi']) ?: 0);
              @endphp

              <div class="text-center mb-3">
                <div class="badge bg-label-info"><i class="ri-steering-line me-1"></i>Depan Bus</div>
              </div>

              <div class="d-flex flex-column gap-2 seat-map">
                @foreach ($rows as $rowSeats)
                  <div class="d-flex justify-content-center align-items-center gap-2">
                    @php
                      $sorted = $rowSeats->sortBy('nomor_kursi')->values();
                      $left = $sorted->filter(fn ($s) => $s['posisi'] === 'kiri')->values();
                      $right = $sorted->filter(fn ($s) => $s['posisi'] === 'kanan')->values();
                    @endphp
                    <span class="seat-row-label text-muted small fw-semibold" style="width: 24px">{{ $left->first()['nomor_kursi'] ?? '' ? (int) preg_replace('/\D/', '', $left->first()['nomor_kursi']) : '' }}</span>
                    <div class="d-flex gap-2 me-2">
                      @foreach ($left as $seat)
                        <button type="button" class="btn btn-sm btn-outline-secondary seat-btn {{ $seat['status'] !== 'aktif' || $seat['is_booked'] ? 'disabled' : '' }} {{ $seat['is_booked'] ? 'seat-booked' : '' }}"
                          data-id="{{ $seat['id'] }}" data-nomor="{{ $seat['nomor_kursi'] }}"
                          {{ $seat['status'] !== 'aktif' || $seat['is_booked'] ? 'disabled' : '' }}>
                          {{ $seat['nomor_kursi'] }}
                        </button>
                      @endforeach
                    </div>
                    <div class="d-flex gap-2 ms-2">
                      @foreach ($right as $seat)
                        <button type="button" class="btn btn-sm btn-outline-secondary seat-btn {{ $seat['status'] !== 'aktif' || $seat['is_booked'] ? 'disabled' : '' }} {{ $seat['is_booked'] ? 'seat-booked' : '' }}"
                          data-id="{{ $seat['id'] }}" data-nomor="{{ $seat['nomor_kursi'] }}"
                          {{ $seat['status'] !== 'aktif' || $seat['is_booked'] ? 'disabled' : '' }}>
                          {{ $seat['nomor_kursi'] }}
                        </button>
                      @endforeach
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="card border-0 shadow-lg sticky-lg-top" style="top: 90px">
            <div class="card-header">
              <h5 class="mb-0"><i class="ri-user-2-line me-2 text-primary"></i>Data Penumpang</h5>
            </div>
            <div class="card-body">
              <div id="passenger-forms">
                <div class="text-center text-muted py-5">
                  <i class="ri-hand-heart-line ri-3x mb-2"></i>
                  <p class="mb-0">Silakan pilih kursi terlebih dahulu untuk mengisi data penumpang.</p>
                </div>
              </div>

              <template id="passenger-template">
                <div class="passenger-form card border mb-3">
                  <div class="card-header py-2">
                    <h6 class="mb-0 passenger-title">Penumpang</h6>
                  </div>
                  <div class="card-body">
                    <input type="hidden" name="seats[]" class="seat-id-input">
                    <div class="mb-2">
                      <label class="form-label small">Nama Lengkap</label>
                      <input type="text" class="form-control form-control-sm nama-penumpang" name="passengers[0][nama_penumpang]" required>
                    </div>
                    <div class="mb-2">
                      <label class="form-label small">NIK <span class="text-muted">(opsional)</span></label>
                      <input type="text" class="form-control form-control-sm" name="passengers[0][nik]" maxlength="16" pattern="[0-9]{15,16}">
                    </div>
                    <div class="row">
                      <div class="col-7 mb-2">
                        <label class="form-label small">No. HP</label>
                        <input type="text" class="form-control form-control-sm" name="passengers[0][no_hp]">
                      </div>
                      <div class="col-5 mb-2">
                        <label class="form-label small">Jenis Kelamin</label>
                        <select class="form-select form-select-sm" name="passengers[0][jenis_kelamin]">
                          <option value="L">Laki-laki</option>
                          <option value="P">Perempuan</option>
                        </select>
                      </div>
                    </div>
                    <div class="mb-2">
                      <label class="form-label small">Tanggal Lahir</label>
                      <input type="date" class="form-control form-control-sm" name="passengers[0][tanggal_lahir]" max="{{ date('Y-m-d', strtotime('-1 day')) }}">
                    </div>
                  </div>
                </div>
              </template>

              <div class="d-flex justify-content-between align-items-center border-top pt-3 mb-3">
                <div>
                  <small class="text-muted d-block">Total</small>
                  <span class="fw-bold fs-4 text-heading" id="total-price">Rp 0</span>
                </div>
                <div class="text-end">
                  <small class="text-muted d-block">Terpilih</small>
                  <span class="fw-bold" id="selected-count">0 / {{ $penumpang }}</span>
                </div>
              </div>

              <button type="submit" class="btn btn-primary w-100 btn-lg" id="submit-booking">
                <i class="ri-shopping-basket-line me-2"></i>Lanjutkan ke Pembayaran
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</section>
@endsection

@section('page-script')
<script>
  $(document).ready(function () {
    const maxSeats = {{ $penumpang }};
    const harga = {{ (float) $schedule->harga }};
    const selected = new Map();

    function reindex() {
      let idx = 0;
      $('#passenger-forms .passenger-form').each(function () {
        $(this).find('.nama-penumpang, input[type="text"], input[type="date"], select').each(function () {
          const name = $(this).attr('name');
          if (name && name.startsWith('passengers[')) {
            $(this).attr('name', name.replace(/passengers\[\d+\]/, 'passengers[' + idx + ']'));
          }
        });
        $(this).find('.seat-id-input').attr('name', 'seats[]');
        $(this).find('.passenger-title').text('Penumpang ' + (idx + 1) + ' - Kursi ' + $(this).data('nomor'));
        idx++;
      });
    }

    function updateSummary() {
      const count = selected.size;
      $('#selected-count').text(count + ' / ' + maxSeats);
      $('#total-price').text('Rp ' + (count * harga).toLocaleString('id-ID'));

      if (count === 0) {
        $('#passenger-forms').html('<div class="text-center text-muted py-5"><i class="ri-hand-heart-line ri-3x mb-2"></i><p class="mb-0">Silakan pilih kursi terlebih dahulu untuk mengisi data penumpang.</p></div>');
        return;
      }

      // Tambahkan form penumpang untuk kursi yang belum punya form
      selected.forEach(function (nomor, id) {
        let $existing = $('#passenger-forms .passenger-form').filter(function () {
          return $(this).data('seat-id') == id;
        });
        if ($existing.length === 0) {
          const $clone = $($('#passenger-template').html());
          $clone.data('seat-id', id);
          $clone.data('nomor', nomor);
          $clone.find('.seat-id-input').val(id);
          $clone.find('.passenger-title').text('Penumpang - Kursi ' + nomor);
          $('#passenger-forms').append($clone);
        }
      });

      // Hapus form untuk kursi yang tidak lagi dipilih
      $('#passenger-forms .passenger-form').each(function () {
        const id = $(this).data('seat-id');
        if (!selected.has(id)) {
          $(this).remove();
        }
      });

      reindex();
    }

    $('.seat-btn:not(.disabled)').on('click', function () {
      const $btn = $(this);
      const id = $btn.data('id');
      const nomor = $btn.data('nomor');

      if ($btn.hasClass('btn-primary')) {
        // Deselect
        $btn.removeClass('btn-primary').addClass('btn-outline-secondary');
        selected.delete(id);
      } else {
        if (selected.size >= maxSeats) {
          window.AlertHandler.showError('Maksimal ' + maxSeats + ' kursi dapat dipilih.');
          return;
        }
        $btn.removeClass('btn-outline-secondary').addClass('btn-primary');
        selected.set(id, nomor);
      }

      updateSummary();
    });

    // Validasi jumlah kursi = jumlah form penumpang sebelum submit
    $('#booking-form').on('submit', function (e) {
      if (selected.size === 0) {
        e.preventDefault();
        window.AlertHandler.showError('Silakan pilih kursi terlebih dahulu.');
        return;
      }
      const filled = $('#passenger-forms .nama-penumpang').filter(function () {
        return $(this).val().trim() !== '';
      }).length;
      if (filled < selected.size) {
        e.preventDefault();
        window.AlertHandler.showError('Lengkapi nama seluruh penumpang terlebih dahulu.');
      }
    });
  });
</script>
@endsection