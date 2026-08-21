<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Tiket {{ $ticket['kode_booking'] }}</title>
  <style>
    * { box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; color: #333; margin: 0; }
    .ticket { border: 2px solid #666cff; border-radius: 12px; max-width: 100%; overflow: hidden; }
    .ticket-header { background: #666cff; color: #fff; padding: 16px 20px; }
    .ticket-header h2 { margin: 0; font-size: 18px; }
    .ticket-header p { margin: 2px 0 0; font-size: 11px; opacity: .9; }
    .ticket-body { padding: 20px; }
    table { width: 100%; border-collapse: collapse; }
    .info-row { margin-bottom: 12px; }
    .label { font-size: 10px; color: #888; text-transform: uppercase; }
    .value { font-size: 13px; font-weight: bold; color: #222; }
    .route-box { border: 1px solid #eee; background: #f8f9fb; border-radius: 8px; padding: 12px 16px; margin-bottom: 16px; }
    .time { font-size: 20px; font-weight: bold; color: #222; }
    .qr-box { text-align: center; margin-top: 16px; padding-top: 16px; border-top: 1px dashed #ddd; }
    .footer-note { text-align: center; font-size: 10px; color: #999; margin-top: 6px; }
    .badge { background: #eaeaff; color: #666cff; padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; }
  </style>
</head>
<body>
  <div class="ticket">
    <div class="ticket-header">
      <table>
        <tr>
          <td>
            <h2>{{ config('variables.templateName') }} &middot; E-Ticket</h2>
            <p>Boarding Pass Bus Antar Kota</p>
          </td>
          <td style="text-align:right">
            <span class="badge" style="background:#fff">{{ $ticket['kelas'] }}</span>
          </td>
        </tr>
      </table>
    </div>
    <div class="ticket-body">
      <table>
        <tr>
          <td>
            <div class="label">Kode Booking</div>
            <div class="value" style="font-size:16px">{{ $ticket['kode_booking'] }}</div>
          </td>
          <td style="text-align:right">
            <div class="label">Status</div>
            <span class="badge" style="background:#e9f6ec;color:#2e7d32">{{ $ticket['status'] }}</span>
          </td>
        </tr>
      </table>

      <div class="route-box" style="margin-top:16px">
        <table>
          <tr>
            <td>
              <div class="label">Berangkat</div>
              <div class="time">{{ $ticket['jam_berangkat'] }}</div>
              <div class="value" style="font-weight:600">{{ $ticket['asal'] }}</div>
            </td>
            <td style="text-align:center;color:#aaa">
              &rarr;<br>
              <div class="label">{{ $ticket['tanggal'] }}</div>
            </td>
            <td style="text-align:right">
              <div class="label">Tiba</div>
              <div class="time">{{ $ticket['jam_tiba'] }}</div>
              <div class="value" style="font-weight:600">{{ $ticket['tujuan'] }}</div>
            </td>
          </tr>
        </table>
      </div>

      <table>
        <tr>
          <td>
            <div class="label">Operator</div>
            <div class="value">{{ $ticket['operator'] }}</div>
          </td>
          <td>
            <div class="label">Bus</div>
            <div class="value">{{ $ticket['bus'] }} ({{ $ticket['nomor_polisi'] }})</div>
          </td>
        </tr>
        <tr>
          <td style="padding-top:12px">
            <div class="label">Kursi</div>
            <span class="badge">{{ $ticket['kursi'] }}</span>
          </td>
          <td style="padding-top:12px">
            <div class="label">Total Dibayar</div>
            <div class="value" style="color:#2e7d32">Rp {{ number_format((float) $ticket['total_harga'], 0, ',', '.') }}</div>
          </td>
        </tr>
      </table>

      <div class="qr-box">
        {!! $qr !!}
      </div>
      <div class="footer-note">Tunjukkan kode QR ini kepada petugas saat boarding. Pastikan membawa identitas yang berlaku.</div>
    </div>
  </div>
</body>
</html>