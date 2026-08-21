<table>
   <thead>
      <tr>
         <th>Tanggal</th>
         <th>Operator</th>
         <th>Bus</th>
         <th>Asal</th>
         <th>Tujuan</th>
         <th>Berangkat</th>
         <th>Tiba</th>
         <th>Terjual</th>
         <th>Kapasitas</th>
      </tr>
   </thead>
   <tbody>
      @foreach ($rows as $row)
         <tr>
            <td>{{ $row['tanggal'] }}</td>
            <td>{{ $row['operator'] }}</td>
            <td>{{ $row['bus'] }}</td>
            <td>{{ $row['asal'] }}</td>
            <td>{{ $row['tujuan'] }}</td>
            <td>{{ $row['berangkat'] }}</td>
            <td>{{ $row['tiba'] }}</td>
            <td>{{ $row['terjual'] }}</td>
            <td>{{ $row['kapasitas'] }}</td>
         </tr>
      @endforeach
   </tbody>
</table>