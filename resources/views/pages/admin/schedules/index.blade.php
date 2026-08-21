@extends('layouts/layoutMaster')

@section('title', 'Manajemen Jadwal')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
   <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold mb-0">
         <span class="text-muted fw-light">Master Data /</span> Daftar Jadwal
      </h4>
      <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
         <i class="ri-add-line me-1"></i> Tambah Jadwal
      </a>
   </div>

   <div class="card">
      <div class="card-header border-bottom">
         <h5 class="card-title mb-0">Daftar Jadwal</h5>
      </div>
      <div class="table-responsive">
         <table class="table table-hover">
            <thead>
               <tr>
                  <th style="width: 50px">#</th>
                  <th>Tanggal</th>
                  <th>Jam Berangkat</th>
                  <th>Jam Tiba</th>
                  <th>Bus</th>
                  <th>Rute</th>
                  <th>Harga</th>
                  <th>Status</th>
                  <th class="text-center">Aksi</th>
               </tr>
            </thead>
            <tbody>
               @forelse($data as $index => $item)
                  <tr>
                     <td>{{ $index + 1 }}</td>
                     <td>{{ $item->tanggal_keberangkatan->translatedFormat('d M Y') }}</td>
                     <td>{{ $item->jam_keberangkatan }}</td>
                     <td>{{ $item->jam_tiba }}</td>
                     <td class="fw-bold">{{ $item->bus->nama_bus }}</td>
                     <td>{{ $item->route->terminalAsal->nama_terminal }} -> {{ $item->route->terminalTujuan->nama_terminal }}</td>
                     <td>Rp {{ number_format((float) $item->harga, 0, ',', '.') }}</td>
                     <td>
                        @if ($item->status == 'aktif')
                           <span class="badge bg-label-success">Aktif</span>
                        @else
                           <span class="badge bg-label-danger">Non-Aktif</span>
                        @endif
                     </td>
                     <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                           <a href="{{ route('admin.schedules.edit', $item->id) }}" class="btn btn-sm btn-outline-primary">
                              <i class="ri-pencil-line"></i>
                           </a>
                           <button type="button" class="btn btn-sm btn-outline-danger delete-record"
                              data-id="{{ $item->id }}" data-name="{{ $item->bus->nama_bus }} - {{ $item->tanggal_keberangkatan->translatedFormat('d M Y') }}">
                              <i class="ri-delete-bin-line"></i>
                           </button>
                        </div>
                     </td>
                  </tr>
               @empty
                  <tr>
                     <td colspan="9" class="text-center py-5">
                        <div class="text-muted">
                           <i class="ri-file-search-line ri-3x mb-2"></i>
                           <p>Belum ada jadwal yang ditambahkan.</p>
                        </div>
                     </td>
                  </tr>
               @endforelse
            </tbody>
         </table>
      </div>
   </div>
</div>
@endsection

@section('page-script')
   <script>
      document.addEventListener('DOMContentLoaded', function() {
         $('.delete-record').on('click', function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let url = "{{ route('admin.schedules.destroy', ':id') }}".replace(':id', id);

            window.AlertHandler.confirm(
               'Hapus Jadwal?',
               `Apakah Anda yakin ingin menghapus jadwal "${name}"? Data yang dihapus tidak dapat dikembalikan.`,
               'Ya, Hapus!',
               function() {
                  $.ajax({
                     url: url,
                     method: 'DELETE',
                     dataType: 'json',
                     headers: {
                        'Accept': 'application/json'
                     },
                     data: {
                        _token: '{{ csrf_token() }}'
                     },
                     success: function(response) {
                        window.AlertHandler.handle(response);
                        setTimeout(() => {
                           window.location.reload();
                        }, 1500);
                     },
                     error: function(xhr) {
                        window.AlertHandler.handle(xhr.responseJSON);
                     }
                  });
               }
            );
         });
      });
   </script>
@endsection