@extends('layouts/layoutMaster')

@section('title', 'Edit Kursi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="mb-4">
        <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">Master Data /</span> Edit Kursi
        </h4>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Form Edit Kursi</h5>
                    <a href="{{ route('admin.seats.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ri-arrow-left-line me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.seats.update', $data->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label" for="bus_id">Bus</label>
                                <select class="form-select @error('bus_id') is-invalid @enderror" id="bus_id" name="bus_id">
                                    <option value="">-- Pilih Bus --</option>
                                    @foreach($buses as $bus)
                                        <option value="{{ $bus->id }}" {{ old('bus_id', $data->bus_id) == $bus->id ? 'selected' : '' }}>{{ $bus->nama_bus }}</option>
                                    @endforeach
                                </select>
                                @error('bus_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="nomor_kursi">Nomor Kursi</label>
                                <input type="text" class="form-control @error('nomor_kursi') is-invalid @enderror" id="nomor_kursi" name="nomor_kursi" value="{{ old('nomor_kursi', $data->nomor_kursi) }}">
                                @error('nomor_kursi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="posisi">Posisi</label>
                                <select class="form-select @error('posisi') is-invalid @enderror" id="posisi" name="posisi">
                                    <option value="">-- Pilih Posisi --</option>
                                    <option value="kiri" {{ old('posisi', $data->posisi) == 'kiri' ? 'selected' : '' }}>Kiri</option>
                                    <option value="kanan" {{ old('posisi', $data->posisi) == 'kanan' ? 'selected' : '' }}>Kanan</option>
                                </select>
                                @error('posisi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label" for="status">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                    <option value="aktif" {{ old('status', $data->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status', $data->status) == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                            <a href="{{ route('admin.seats.index') }}" class="btn btn-label-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
