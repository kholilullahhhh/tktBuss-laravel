@extends('layouts/layoutMaster')

@section('title', 'Edit Rute')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="mb-4">
        <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">Master Data /</span> Edit Rute
        </h4>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Form Edit Rute</h5>
                    <a href="{{ route('admin.routes.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ri-arrow-left-line me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.routes.update', $data->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="terminal_asal_id">Terminal Asal</label>
                                <select class="form-select @error('terminal_asal_id') is-invalid @enderror" id="terminal_asal_id" name="terminal_asal_id">
                                    <option value="">-- Pilih Terminal Asal --</option>
                                    @foreach($terminals as $terminal)
                                        <option value="{{ $terminal->id }}" {{ old('terminal_asal_id', $data->terminal_asal_id) == $terminal->id ? 'selected' : '' }}>{{ $terminal->nama_terminal }}</option>
                                    @endforeach
                                </select>
                                @error('terminal_asal_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="terminal_tujuan_id">Terminal Tujuan</label>
                                <select class="form-select @error('terminal_tujuan_id') is-invalid @enderror" id="terminal_tujuan_id" name="terminal_tujuan_id">
                                    <option value="">-- Pilih Terminal Tujuan --</option>
                                    @foreach($terminals as $terminal)
                                        <option value="{{ $terminal->id }}" {{ old('terminal_tujuan_id', $data->terminal_tujuan_id) == $terminal->id ? 'selected' : '' }}>{{ $terminal->nama_terminal }}</option>
                                    @endforeach
                                </select>
                                @error('terminal_tujuan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="jarak">Jarak (km)</label>
                                <input type="number" step="0.01" class="form-control @error('jarak') is-invalid @enderror" id="jarak" name="jarak" value="{{ old('jarak', $data->jarak) }}">
                                @error('jarak')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="estimasi_durasi">Estimasi Durasi (menit)</label>
                                <input type="number" class="form-control @error('estimasi_durasi') is-invalid @enderror" id="estimasi_durasi" name="estimasi_durasi" value="{{ old('estimasi_durasi', $data->estimasi_durasi) }}">
                                @error('estimasi_durasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', $data->status) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">Aktifkan Rute</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                            <a href="{{ route('admin.routes.index') }}" class="btn btn-label-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
