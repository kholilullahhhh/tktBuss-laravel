@extends('layouts/layoutMaster')

@section('title', 'Edit Jadwal')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="mb-4">
        <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">Master Data /</span> Edit Jadwal
        </h4>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Form Edit Jadwal</h5>
                    <a href="{{ route('admin.schedules.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ri-arrow-left-line me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.schedules.update', $data->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
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
                                <label class="form-label" for="route_id">Rute</label>
                                <select class="form-select @error('route_id') is-invalid @enderror" id="route_id" name="route_id">
                                    <option value="">-- Pilih Rute --</option>
                                    @foreach($routes as $route)
                                        <option value="{{ $route->id }}" {{ old('route_id', $data->route_id) == $route->id ? 'selected' : '' }}>{{ $route->terminalAsal->nama_terminal }} -> {{ $route->terminalTujuan->nama_terminal }}</option>
                                    @endforeach
                                </select>
                                @error('route_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="tanggal_keberangkatan">Tanggal Keberangkatan</label>
                                <input type="date" class="form-control @error('tanggal_keberangkatan') is-invalid @enderror" id="tanggal_keberangkatan" name="tanggal_keberangkatan" value="{{ old('tanggal_keberangkatan', optional($data->tanggal_keberangkatan)->format('Y-m-d')) }}">
                                @error('tanggal_keberangkatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label" for="jam_keberangkatan">Jam Berangkat</label>
                                <input type="time" class="form-control @error('jam_keberangkatan') is-invalid @enderror" id="jam_keberangkatan" name="jam_keberangkatan" value="{{ old('jam_keberangkatan', $data->jam_keberangkatan) }}">
                                @error('jam_keberangkatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label" for="jam_tiba">Jam Tiba</label>
                                <input type="time" class="form-control @error('jam_tiba') is-invalid @enderror" id="jam_tiba" name="jam_tiba" value="{{ old('jam_tiba', $data->jam_tiba) }}">
                                @error('jam_tiba')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="harga">Harga (Rp)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('harga') is-invalid @enderror" id="harga" name="harga" value="{{ old('harga', $data->harga) }}">
                                </div>
                                @error('harga')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
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
                            <a href="{{ route('admin.schedules.index') }}" class="btn btn-label-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection