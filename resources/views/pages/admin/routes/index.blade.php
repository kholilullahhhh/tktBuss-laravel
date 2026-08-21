@extends('layouts/layoutMaster')

@section('title', 'Manajemen Rute')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
   <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold mb-0">
         <span class="text-muted fw-light">Master Data /</span> Daftar Rute
      </h4>
      <a href="{{ route('admin.routes.create') }}" class="btn btn-primary">
         <i class="ri-add-line me-1"></i> Tambah Rute
      </a>
   </div>

   <div class="card">
      <div class="card-header border-bottom">
         <h5 class="card-title mb-0">Daftar Rute</h5>
      </div>
      <div class="table-responsive">
         <table class="table table-hover">
            <thead>
               <tr>
                  <th style="width: 50px">#</th>
                  <th>Asal</th>
                  <th>Tujuan</th>
                  <th>Jarak</th>
                  <th>Durasi</th>
                  <th>Jumlah Jadwal</th>
                  <th>Status</th>
                  <th class="text-center">Aksi</th>
               </tr>
            </thead>
            <tbody>
               @forelse($data as $index => $item)
                  <tr>
                     <td>{{ $index + 1 }}</td>
                     <td class="fw-bold">{{ $item->terminalAsal->nama_terminal }}</td>
                     <td>{{ $item->terminalTujuan->nama_terminal }}</td>
                     <td>{{ $item->jarak }} km</td>
                     <td>{{ $item->estimasi_durasi }} menit</td>
                     <td><span class="badge bg-label-primary">{{ $item->schedules->count() }}</span></td>
                     <td>
                        @if ($item->status)
                           <span class="badge bg-success">Aktif</span>
                        @else
                           <span class="badge bg-secondary">Non-Aktif</span>
                        @endif
                     </td>
                     <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                           <a href="{{ route('admin.routes.show', $item->id) }}" class="btn btn-sm btn-outline-secondary" title="Detail">
                              <i class="ri-eye-line"></i>
                           </a>
                           <a href="{{ route('admin.routes.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                              <i class="ri-pencil-line"></i>
                           </a>
                           <button type="button" class="btn btn-sm btn-outline-danger delete-record"
                              data-id="{{ $item->id }}" data-name="{{ $item->terminalAsal->nama_terminal }} - {{ $item->terminalTujuan->nama_terminal }}">
                              <i class="ri-delete-bin-line"></i>
                           </button>
                        </div>
                     </td>
                  </tr>
               @empty
                  <tr>
                     <td colspan="8" class="text-center py-5">
                        <div class="text-muted">
                           <i class="ri-file-search-line ri-3x mb-2"></i>
                           <p>Belum ada rute yang ditambahkan.</p>
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
            let url = "{{ route('admin.routes.destroy', ':id') }}".replace(':id', id);

            window.AlertHandler.confirm(
               'Hapus Rute?',
               `Apakah Anda yakin ingin menghapus rute "${name}"? Data yang dihapus tidak dapat dikembalikan.`,
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
