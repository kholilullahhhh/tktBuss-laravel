@extends('layouts/layoutMaster')

@section('title', 'Edit Bus')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="mb-4">
        <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">Master Data /</span> Edit Bus
        </h4>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Form Edit Bus</h5>
                    <a href="{{ route('admin.buses.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ri-arrow-left-line me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.buses.update', $data->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="operator_id">Operator Bus</label>
                                <select class="form-select @error('operator_id') is-invalid @enderror" id="operator_id" name="operator_id">
                                    <option value="">-- Pilih Operator --</option>
                                    @foreach($operators as $operator)
                                        <option value="{{ $operator->id }}" {{ old('operator_id', $data->operator_id) == $operator->id ? 'selected' : '' }}>{{ $operator->nama_operator }}</option>
                                    @endforeach
                                </select>
                                @error('operator_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="kode_bus">Kode Bus</label>
                                <input type="text" class="form-control @error('kode_bus') is-invalid @enderror" id="kode_bus" name="kode_bus" value="{{ old('kode_bus', $data->kode_bus) }}">
                                @error('kode_bus')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="nama_bus">Nama Bus</label>
                                <input type="text" class="form-control @error('nama_bus') is-invalid @enderror" id="nama_bus" name="nama_bus" value="{{ old('nama_bus', $data->nama_bus) }}">
                                @error('nama_bus')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="nomor_polisi">Nomor Polisi</label>
                                <input type="text" class="form-control @error('nomor_polisi') is-invalid @enderror" id="nomor_polisi" name="nomor_polisi" value="{{ old('nomor_polisi', $data->nomor_polisi) }}">
                                @error('nomor_polisi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="kelas">Kelas</label>
                                <select class="form-select @error('kelas') is-invalid @enderror" id="kelas" name="kelas">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach(['Ekonomi', 'Bisnis', 'Executive', 'Sleeper'] as $kelasOption)
                                        <option value="{{ $kelasOption }}" {{ old('kelas', $data->kelas) == $kelasOption ? 'selected' : '' }}>{{ $kelasOption }}</option>
                                    @endforeach
                                </select>
                                @error('kelas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="kapasitas">Kapasitas</label>
                                <input type="number" class="form-control @error('kapasitas') is-invalid @enderror" id="kapasitas" name="kapasitas" value="{{ old('kapasitas', $data->kapasitas) }}">
                                @error('kapasitas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label" for="fasilitas">Fasilitas</label>
                                <textarea class="form-control @error('fasilitas') is-invalid @enderror" id="fasilitas" name="fasilitas" rows="3">{{ old('fasilitas', $data->fasilitas) }}</textarea>
                                <small class="text-muted">Pisahkan dengan koma.</small>
                                @error('fasilitas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', $data->status) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">Aktifkan Bus</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                            <a href="{{ route('admin.buses.index') }}" class="btn btn-label-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
