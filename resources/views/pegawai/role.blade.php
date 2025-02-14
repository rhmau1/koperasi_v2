@extends('template.main')
@section('content')
    {{-- Table Datatable Custom --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/forms/theme-checkbox-radio.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/dt-global_style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/custom_dt_custom.css') }}">

    <div class="layout-px-spacing">

        <div class="row layout-top-spacing layout-spacing">
            <div class="col-lg-6">
                <div class="statbox widget box box-shadow">
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive mb-4">
                            <table id="style-3" class="table style-3  table-hover">
                                <thead>
                                    <tr>
                                        <th class="checkbox-column text-center"> No </th>
                                        <th>Nama level</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($allLevels as $key => $level)
                                        <tr>
                                            <td class="checkbox-column text-center"> {{ $key + 1 }} </td>
                                            <td>{{ $level->nama_level }}</td>

                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <!-- Tombol Pilih -->
                                                    <form action="{{ route('rolePegawai.store', $pegawaiId) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        <input type="hidden" name="id_level"
                                                            value="{{ $level->id_level }}">
                                                        <button type="submit" class="btn btn-primary btn-sm bs-tooltip"
                                                            data-toggle="tooltip" data-placement="top" title="Pilih">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path
                                                                    d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                                </path>
                                                            </svg>
                                                            Pilih
                                                        </button>
                                                    </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="statbox widget box box-shadow">
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive mb-4">
                            <table id="style-3" class="table style-3  table-hover">
                                <thead>
                                    <tr>
                                        <th class="checkbox-column text-center"> No </th>
                                        <th>Nama level</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($userLevels as $key => $level)
                                        <tr>
                                            <td class="checkbox-column text-center"> {{ $key + 1 }} </td>
                                            <td>{{ $level->level->nama_level }}</td>

                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <!-- Tombol Hapus -->
                                                    @if ($level->status == 0)
                                                        <form action="{{ route('rolePegawai.delete', $pegawaiId) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="id_level"
                                                                value="{{ $level->level->id_level }}">
                                                            <button type="submit" class="btn btn-danger btn-sm bs-tooltip"
                                                                data-toggle="tooltip" data-placement="top"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus level {{ $level->level->nama_level }} ini?');"
                                                                title="Delete">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                                    <path
                                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                                    </path>
                                                                </svg>
                                                                Delete
                                                            </button>
                                                        </form>
                                                    @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
