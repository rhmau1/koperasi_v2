@extends('template.main')
@section('content')
    {{-- Table Datatable Custom --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/forms/theme-checkbox-radio.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/dt-global_style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/custom_dt_custom.css') }}">

    <div class="layout-px-spacing">

        <div class="row layout-top-spacing layout-spacing">
            <div class="col-lg-12">
                <div class="statbox widget box box-shadow">
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive mb-4">
                            @foreach ($dataAkses as $akses)
                                @if ($akses->hak_add == 1)
                                    <a href="{{ route('admin.levelUser.create') }}" class="btn btn-primary ml-3 mb-4">+
                                        Tambah
                                        Data</a>
                                @endif
                                <table id="style-3" class="table style-3  table-hover">
                                    <thead>
                                        <tr>
                                            <th class="checkbox-column text-center"> No </th>
                                            <th>Nama level</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($userLevels as $key => $level)
                                            <tr>
                                                <td class="checkbox-column text-center"> {{ $key + 1 }} </td>
                                                <td>{{ $level->nama_level }}</td>

                                                @if ($level->status == 1)
                                                    <td class="text-center">
                                                        <span class="shadow-none badge badge-primary">Aktif</span>
                                                    </td>
                                                @else
                                                    <td class="text-center">
                                                        <span class="shadow-none badge badge-danger">Tidak
                                                            Aktif</span>
                                                    </td>
                                                @endif
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <!-- Tombol Edit -->
                                                        @if ($akses->hak_edit == 1)
                                                            <a href="{{ route('admin.levelUser.edit', $level->id_level) }}"
                                                                class="btn btn-success btn-sm bs-tooltip"
                                                                data-toggle="tooltip" data-placement="top" title="Edit">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                    <path
                                                                        d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                                    </path>
                                                                </svg>
                                                                Edit
                                                            </a>
                                                        @endif

                                                        <!-- Tombol Delete -->
                                                        @if ($level->id_level > 4 || $level->id_level < 1)
                                                            @if ($akses->hak_delete == 1)
                                                                <form
                                                                    action="{{ route('admin.levelUser.delete', $level->id_level) }}"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-danger btn-sm bs-tooltip"
                                                                        data-toggle="tooltip" data-placement="top"
                                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus level {{ $level->nama_level }} ini?');"
                                                                        title="Delete">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="16" height="16"
                                                                            viewBox="0 0 24 24" fill="none"
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
                                                        @endif
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
