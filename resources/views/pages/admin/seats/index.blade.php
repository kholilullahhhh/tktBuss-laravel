@extends('layouts/layoutMaster')

@section('title', 'Manajemen Kursi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
   <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold mb-0">
         <span class="text-muted fw-light">Master Data /</span> Daftar Kursi
      </h4>
      <a href="{{ route('admin.seats.create') }}" class="btn btn-primary">
         <i class="ri-add-line me-1"></i> Tambah Kursi
      </a>
   </div>

   <div class="card mb-4">
      <div class="card-body d-flex gap-2 align-items-center flex-wrap">
         <form action="{{ route('admin.seats.index') }}" method="GET" class="d-flex gap-2 align-items-center mb-0">
            <select name="bus_id" class="form-select" style="width: 250px">
               <option value="">-- Semua Bus --</option>
               @foreach($buses as $bus)
                  <option value="{{ $bus->id }}" {{ request('bus_id') == $bus->id ? 'selected' : '' }}>{{ $bus->nama_bus }}</option>
               @endforeach
            </select>
            <button type="submit" class="btn btn-outline-primary">Filter</button>
         </form>
         <form action="{{ route('admin.seats.generate') }}" method="POST" class="d-flex gap-2 align-items-center mb-0 ms-lg-3">
            @csrf
            <select name="bus_id" class="form-select" style="width: 250px">
               <option value="">-- Pilih Bus --</option>
               @foreach($buses as $bus)
                  <option value="{{ $bus->id }}">{{ $bus->nama_bus }}</option>
               @endforeach
            </select>
            <button type="submit" class="btn btn-primary">
               <i class="ri-settings-3-line me-1"></i> Generate
            </button>
         </form>
      </div>
   </div>

   <div class="card">
      <div class="card-header border-bottom">
         <h5 class="card-title mb-0">Daftar Kursi</h5>
      </div>
      <div class="table-responsive">
         <table class="table table-hover">
            <thead>
               <tr>
                  <th style="width: 50px">#</th>
                  <th>Bus</th>
                  <th>Operator</th>
                  <th>Nomor Kursi</th>
                  <th>Posisi</th>
                  <th>Status</th>
                  <th class="text-center">Aksi</th>
               </tr>
            </thead>
            <tbody>
               @forelse($data as $index => $item)
                  <tr>
                     <td>{{ $index + 1 }}</td>
                     <td class="fw-bold">{{ $item->bus->nama_bus }}</td>
                     <td>{{ $item->bus->operator->nama_operator }}</td>
                     <td>{{ $item->nomor_kursi }}</td>
                     <td>{{ $item->posisi }}</td>
                     <td>
                        @if ($item->status == 'aktif')
                           <span class="badge bg-label-success">Aktif</span>
                        @else
                           <span class="badge bg-label-danger">Non-Aktif</span>
                        @endif
                     </td>
                     <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                           <a href="{{ route('admin.seats.edit', $item->id) }}" class="btn btn-sm btn-outline-primary">
                              <i class="ri-pencil-line"></i>
                           </a>
                           <button type="button" class="btn btn-sm btn-outline-danger delete-record"
                              data-id="{{ $item->id }}" data-name="{{ $item->nomor_kursi }}">
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
                           <p>Belum ada kursi yang ditambahkan.</p>
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
            let url = "{{ route('admin.seats.destroy', ':id') }}".replace(':id', id);

            window.AlertHandler.confirm(
               'Hapus Kursi?',
               `Apakah Anda yakin ingin menghapus kursi "${name}"? Data yang dihapus tidak dapat dikembalikan.`,
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
