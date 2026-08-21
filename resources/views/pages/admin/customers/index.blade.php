@extends('layouts/layoutMaster')

@section('title', 'Customer')

@section('content')
   <div class="container-xxl flex-grow-1 container-p-y">
      <div class="d-flex justify-content-between align-items-center mb-4">
         <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">Transaksi /</span> Customer
         </h4>
      </div>

      <div class="card">
         <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Daftar Customer</h5>
         </div>
         <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.customers.index') }}" class="row g-3 align-items-end">
               <div class="col-md-4">
                  <label class="form-label">Cari</label>
                  <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Nama atau email...">
               </div>
               <div class="col-md-2">
                  <button type="submit" class="btn btn-primary w-100"><i class="ri-search-line me-1"></i> Filter</button>
               </div>
            </form>
         </div>
         <div class="table-responsive">
            <table class="table table-hover">
               <thead>
                  <tr>
                     <th>Nama</th>
                     <th>Email</th>
                     <th>No. HP</th>
                     <th>Jumlah Booking</th>
                     <th>Terdaftar</th>
                     <th class="text-center">Aksi</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse($data as $c)
                     <tr>
                        <td><span class="fw-bold">{{ $c->name }}</span></td>
                        <td>{{ $c->email }}</td>
                        <td>{{ $c->phone ?? '-' }}</td>
                        <td><span class="badge bg-label-primary">{{ $c->bookings_count }}</span></td>
                        <td>{{ $c->created_at->translatedFormat('d M Y') }}</td>
                        <td class="text-center">
                           <a href="{{ route('admin.customers.show', $c->id) }}" class="btn btn-sm btn-outline-primary">
                              <i class="ri-eye-line"></i>
                           </a>
                        </td>
                     </tr>
                  @empty
                     <tr>
                        <td colspan="6" class="text-center py-5">
                           <div class="text-muted">
                              <i class="ri-file-search-line ri-3x mb-2"></i>
                              <p>Belum ada data customer.</p>
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
