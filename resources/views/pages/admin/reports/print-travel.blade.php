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
            <th>Tanggal</th>
            <th>Operator</th>
            <th>Bus</th>
            <th>Asal</th>
            <th>Tujuan</th>
            <th>Berangkat</th>
            <th>Terjual</th>
            <th>Kapasitas</th>
         </tr>
      </thead>
      <tbody>
         @forelse($rows as $row)
            <tr>
               <td>{{ $row['tanggal'] }}</td>
               <td>{{ $row['operator'] }}</td>
               <td>{{ $row['bus'] }}</td>
               <td>{{ $row['asal'] }}</td>
               <td>{{ $row['tujuan'] }}</td>
               <td>{{ $row['berangkat'] }}</td>
               <td>{{ $row['terjual'] }}</td>
               <td>{{ $row['kapasitas'] }}</td>
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