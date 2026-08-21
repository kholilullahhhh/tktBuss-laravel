<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="utf-8">
   <title>{{ $title }}</title>
   <style>
      body {
         font-family: 'DejaVu Sans', sans-serif;
         font-size: 12px;
         color: #333;
      }
      .header {
         text-align: center;
         margin-bottom: 20px;
         border-bottom: 2px solid #333;
         padding-bottom: 10px;
      }
      .header h1 {
         font-size: 20px;
         margin: 0 0 5px;
      }
      table {
         width: 100%;
         border-collapse: collapse;
         margin-bottom: 20px;
      }
      table th, table td {
         border: 1px solid #333;
         padding: 6px 8px;
         text-align: left;
      }
      table th {
         background: #f0f0f0;
      }
      .text-right {
         text-align: right;
      }
      .footer {
         margin-top: 30px;
         text-align: right;
         font-size: 11px;
         color: #777;
      }
   </style>
</head>
<body>
   <div class="header">
      <h1>{{ $title }}</h1>
      <div>Dicetak pada: {{ now()->translatedFormat('d M Y H:i') }}</div>
   </div>

   <table>
      <thead>
         <tr>
            <th>Kode</th>
            <th>Customer</th>
            <th>Rute</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Kursi</th>
            <th>Total</th>
            <th>Status</th>
         </tr>
      </thead>
      <tbody>
         @forelse($bookings as $b)
            <tr>
               <td>{{ $b->kode_booking }}</td>
               <td>{{ $b->user->name }}</td>
               <td>{{ $b->schedule->route->terminalAsal->kota }} &rarr; {{ $b->schedule->route->terminalTujuan->kota }}</td>
               <td>{{ $b->schedule->tanggal_keberangkatan }}</td>
               <td>{{ $b->schedule->jam_keberangkatan }}</td>
               <td>{{ $b->seats->count() }}</td>
               <td class="text-right">Rp {{ number_format((float) $b->total_harga, 0, ',', '.') }}</td>
               <td>{{ ucfirst($b->status_booking) }}</td>
            </tr>
         @empty
            <tr>
               <td colspan="8" style="text-align: center">Tidak ada data.</td>
            </tr>
         @endforelse
      </tbody>
   </table>

   <div class="footer">
      &copy; {{ now()->year }} Sistem Pemesanan Tiket Bus
   </div>
</body>
</html>