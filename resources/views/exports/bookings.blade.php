<table>
   <thead>
      <tr>
         <th>Kode</th>
         <th>Customer</th>
         <th>Asal</th>
         <th>Tujuan</th>
         <th>Tanggal</th>
         <th>Jam</th>
         <th>Kursi</th>
         <th>Total</th>
         <th>Status Booking</th>
         <th>Status Pembayaran</th>
      </tr>
   </thead>
   <tbody>
      @foreach ($bookings as $b)
         <tr>
            <td>{{ $b->kode_booking }}</td>
            <td>{{ $b->user->name }}</td>
            <td>{{ $b->schedule->route->terminalAsal->kota }}</td>
            <td>{{ $b->schedule->route->terminalTujuan->kota }}</td>
            <td>{{ $b->schedule->tanggal_keberangkatan }}</td>
            <td>{{ $b->schedule->jam_keberangkatan }}</td>
            <td>{{ $b->seats->count() }}</td>
            <td>{{ number_format((float) $b->total_harga, 0, ',', '.') }}</td>
            <td>{{ ucfirst($b->status_booking) }}</td>
            <td>{{ ucfirst($b->status_pembayaran) }}</td>
         </tr>
      @endforeach
   </tbody>
</table>