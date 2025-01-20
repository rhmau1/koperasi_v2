@extends('template.main')
@section('content')
    <div class="layout-px-spacing">

        <div id="flHorizontalForm" class="col-lg-12 layout-top-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Tambah pegawai form</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <form action="{{ route('inputPegawai.store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <label for="namaPegawai" class="col-sm-2 col-form-label">Nama pegawai</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="namaPegawai" name="nama_pegawai">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="emailPegawai" class="col-sm-2 col-form-label">Email pegawai</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="emailPegawai" name="email_pegawai">
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
                                <input type="number" class="form-control" id="HpPegawai" name="hp_pegawai">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="status" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                                <select class="form-control basic" name="status">
                                    <option value="1" selected="selected">Aktif</option>
                                    <option value="0">Tidak aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="level" class="col-sm-2 col-form-label">Level</label>
                            <div class="col-sm-10">
                                <select class="form-control basic" name="level">
                                    @foreach ($userLevels as $level)
                                        <option value="{{ $level->id_level }}"
                                            {{ $level->id_level == 3 ? 'selected' : '' }}>{{ $level->nama_level }}</option>
                                    @endforeach
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
