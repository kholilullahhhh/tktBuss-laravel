@extends('layouts/layoutMaster')

@section('title', 'Manajemen Operator Bus')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
   <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold mb-0">
         <span class="text-muted fw-light">Master Data /</span> Daftar Operator Bus
      </h4>
      <a href="{{ route('admin.operators.create') }}" class="btn btn-primary">
         <i class="ri-add-line me-1"></i> Tambah Operator
      </a>
   </div>

   <div class="card">
      <div class="card-header border-bottom">
         <h5 class="card-title mb-0">Daftar Operator Bus</h5>
      </div>
      <div class="table-responsive">
         <table class="table table-hover">
            <thead>
               <tr>
                  <th style="width: 50px">#</th>
                  <th>Kode</th>
                  <th>Nama</th>
                  <th>Telepon</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th class="text-center">Aksi</th>
               </tr>
            </thead>
            <tbody>
               @forelse($data as $index => $item)
                  <tr>
                     <td>{{ $index + 1 }}</td>
                     <td>{{ $item->kode_operator }}</td>
                     <td><span class="fw-bold">{{ $item->nama_operator }}</span></td>
                     <td>{{ $item->telepon }}</td>
                     <td>{{ $item->email }}</td>
                     <td>
                        @if ($item->status)
                           <span class="badge bg-success">Aktif</span>
                        @else
                           <span class="badge bg-secondary">Non-Aktif</span>
                        @endif
                     </td>
                     <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                           <a href="{{ route('admin.operators.edit', $item->id) }}" class="btn btn-sm btn-outline-primary">
                              <i class="ri-pencil-line"></i>
                           </a>
                           <button type="button" class="btn btn-sm btn-outline-danger delete-record"
                              data-id="{{ $item->id }}" data-name="{{ $item->nama_operator }}">
                              <i class="ri-delete-bin-line"></i>
                           </button>
                        </div>
                     </td>
                  </tr>
               @empty
                  <tr>
                     <td colspan="7" class="text-center py-5">
                        <div class="text-muted">
                           <i class="ri-file-search-line ri-3x mb-2"></i>
                           <p>Belum ada operator bus yang ditambahkan.</p>
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
            let url = "{{ route('admin.operators.destroy', ':id') }}".replace(':id', id);

            window.AlertHandler.confirm(
               'Hapus Operator Bus?',
               `Apakah Anda yakin ingin menghapus "${name}"? Data yang dihapus tidak dapat dikembalikan.`,
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
