@extends('layouts.layoutMaster')

@section('title', 'Tiket Digital')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h4 class="fw-bold mb-0">
      <span class="text-muted fw-light">Tiket /</span> {{ $ticket['kode_booking'] }}
    </h4>
    <div class="d-flex gap-2">
      <a href="{{ route('booking.show', $booking->id) }}" class="btn btn-outline-secondary">
        <i class="ri-arrow-left-line me-1"></i> Kembali
      </a>
      <a href="{{ route('booking.ticket.pdf', $booking->id) }}" class="btn btn-outline-primary">
        <i class="ri-file-pdf-line me-1"></i> Unduh PDF
      </a>
    </div>
  </div>

  <div class="card ticket-card border-0 shadow-lg mx-auto" style="max-width: 760px">
    <div class="card-header bg-primary text-white">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h5 class="mb-0 fw-bold">{{ config('variables.templateName') }} &middot; E-Ticket</h5>
          <small>Boarding Pass Bus Antar Kota</small>
        </div>
        <span class="badge bg-white text-primary">{{ $ticket['kelas'] }}</span>
      </div>
    </div>
    <div class="card-body p-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <small class="text-muted d-block">Kode Booking</small>
          <span class="fw-bold fs-5 text-heading">{{ $ticket['kode_booking'] }}</span>
        </div>
        <div class="text-end">
          <small class="text-muted d-block">Status</small>
          <span class="badge bg-label-success">{{ $ticket['status'] }}</span>
        </div>
      </div>

      <div class="d-flex align-items-center gap-3 py-3 border rounded-3 bg-body px-3 mb-4">
        <div>
          <div class="text-muted small">Berangkat</div>
          <div class="fw-bold fs-4 text-heading">{{ $ticket['jam_berangkat'] }}</div>
          <div class="fw-semibold small">{{ $ticket['asal'] }}</div>
        </div>
        <div class="flex-grow-1 text-center text-muted">
          <i class="ri-arrow-right-line ri-2x"></i>
          <div class="small">{{ $ticket['tanggal'] }}</div>
        </div>
        <div class="text-end">
          <div class="text-muted small">Tiba</div>
          <div class="fw-bold fs-4 text-heading">{{ $ticket['jam_tiba'] }}</div>
          <div class="fw-semibold small">{{ $ticket['tujuan'] }}</div>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <small class="text-muted d-block">Operator</small>
          <span class="fw-semibold">{{ $ticket['operator'] }}</span>
        </div>
        <div class="col-md-6">
          <small class="text-muted d-block">Bus</small>
          <span class="fw-semibold">{{ $ticket['bus'] }} ({{ $ticket['nomor_polisi'] }})</span>
        </div>
        <div class="col-md-6">
          <small class="text-muted d-block">Kursi</small>
          <span class="badge bg-label-primary">{{ $ticket['kursi'] }}</span>
        </div>
        <div class="col-md-6">
          <small class="text-muted d-block">Total Dibayar</small>
          <span class="fw-bold text-success">Rp {{ number_format((float) $ticket['total_harga'], 0, ',', '.') }}</span>
        </div>
      </div>

      <div class="d-flex justify-content-center border-top pt-4">
        <div class="text-center">
          <div class="ticket-qr mb-2">
            {!! $qr !!}
          </div>
          <small class="text-muted">Tunjukkan kode QR ini saat boarding</small>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection