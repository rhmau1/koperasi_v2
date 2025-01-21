@extends('template.main')
@section('content')
    <div class="layout-px-spacing">

        <div id="flHorizontalForm" class="col-lg-12 layout-top-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Edit anggota form</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <form action="{{ route('inputAnggota.update', $anggota->id_anggota) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="row mb-3">
                            <label for="namaAnggota" class="col-sm-2 col-form-label">Nama anggota</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="namaAnggota" name="nama_anggota"
                                    value="{{ $anggota->nama_anggota }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="emailAnggota" class="col-sm-2 col-form-label">Email anggota</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="emailAnggota" name="email_anggota"
                                    value="{{ $anggota->email_anggota }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="passwordAnggota" class="col-sm-2 col-form-label">Password anggota</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="passwordAnggota" name="password_anggota">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="HpAnggota" class="col-sm-2 col-form-label">HP anggota</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="HpAnggota" name="hp_anggota"
                                    value="{{ $anggota->hp_anggota }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="status" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                                <select class="form-control basic" name="status">
                                    <option value="1" {{ $anggota->status == 1 ? 'selected' : '' }}>Aktif</option>
                                    <option value="0"{{ $anggota->status == 0 ? 'selected' : '' }}>Tidak aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="level" class="col-sm-2 col-form-label">Level</label>
                            <div class="col-sm-10">
                                <select class="form-control basic" name="level">
                                    @foreach ($userLevels as $level)
                                        <option value="{{ $level->id_level }}"
                                            {{ $anggota->levelAkses->id_level == $level->id_level ? 'selected' : '' }}>
                                            {{ $level->nama_level }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <a href="{{ route('inputAnggota') }}" type="button" class="btn btn-primary">Kembali</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
