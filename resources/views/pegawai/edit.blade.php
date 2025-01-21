@extends('template.main')
@section('content')
    <div class="layout-px-spacing">

        <div id="flHorizontalForm" class="col-lg-12 layout-top-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Edit pegawai form</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <form action="{{ route('inputPegawai.update', $pegawai->id_pegawai) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="row mb-3">
                            <label for="namaPegawai" class="col-sm-2 col-form-label">Nama pegawai</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="namaPegawai" name="nama_pegawai"
                                    value="{{ $pegawai->nama_pegawai }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="emailPegawai" class="col-sm-2 col-form-label">Email pegawai</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="emailPegawai" name="email_pegawai"
                                    value="{{ $pegawai->email_pegawai }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="passwordPegawai" class="col-sm-2 col-form-label">Password pegawai</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="passwordPegawai" name="password_pegawai">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="HpPegawai" class="col-sm-2 col-form-label">HP pegawai</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="HpPegawai" name="hp_pegawai"
                                    value="{{ $pegawai->hp_pegawai }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="status" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                                <select class="form-control basic" name="status">
                                    <option value="1" {{ $pegawai->status == 1 ? 'selected' : '' }}>Aktif</option>
                                    <option value="0"{{ $pegawai->status == 0 ? 'selected' : '' }}>Tidak aktif</option>
                                </select>
                            </div>
                        </div>
                        <a href="{{ route('inputPegawai') }}" type="button" class="btn btn-primary">Kembali</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
