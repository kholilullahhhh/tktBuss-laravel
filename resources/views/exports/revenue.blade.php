<table>
   <thead>
      <tr>
         <th>Tanggal</th>
         <th>Transaksi</th>
         <th>Pendapatan</th>
      </tr>
   </thead>
   <tbody>
      @foreach ($rows as $row)
         <tr>
            <td>{{ $row->tgl }}</td>
            <td>{{ $row->transaksi }}</td>
            <td>{{ number_format((float) $row->pendapatan, 0, ',', '.') }}</td>
         </tr>
      @endforeach
   </tbody>
   <tfoot>
      <tr>
         <td colspan="2"><strong>Total</strong></td>
         <td><strong>{{ number_format((float) $total, 0, ',', '.') }}</strong></td>
      </tr>
   </tfoot>
</table>