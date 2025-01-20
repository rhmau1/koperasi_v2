{{-- @extends('layouts.app') --}}

{{-- @section('content') --}}

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Login</title>

        <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
        <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

        <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/authentication/form-2.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/forms/theme-checkbox-radio.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/forms/switches.css') }}">
        <style>
            .form-form .form-form-wrap form .field-wrapper svg.feather-eye {
                top: 46px;
            }
        </style>
    </head>

    <body>

        <div class="form-container outer">
            <div class="form-form">
                <div class="form-form-wrap">
                    <div class="form-container">
                        <div class="form-content">

                            <h1 class="">Pilih Role</h1>
                            <p class="">Pilih role yang ingin anda aktifkan.</p>

                            <div class="col-lg-12">
                                <div class="statbox widget box box-shadow">
                                    <div class="widget-content widget-content-area">
                                        <div class="table-responsive mb-4">
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
                                                            <td class="checkbox-column text-center">
                                                                {{ $level->level->id_level }} </td>
                                                            <td>{{ $level->level->nama_level }}</td>
                                                            @if ($level->status == 1)
                                                                <td class="text-center">
                                                                    <span
                                                                        class="shadow-none badge badge-primary">Aktif</span>
                                                                </td>
                                                            @else
                                                                <td class="text-center">
                                                                    <span class="shadow-none badge badge-danger">Tidak
                                                                        Aktif</span>
                                                                </td>
                                                            @endif
                                                            <td class="text-center">
                                                                <div class="btn-group" role="group">
                                                                    <!-- Tombol Pilih -->
                                                                    <form
                                                                        action="{{ route('pilihRole.update', $level->id_level) }}"
                                                                        method="POST" style="display:inline;">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <button type="submit"
                                                                            class="btn btn-primary btn-sm bs-tooltip"
                                                                            data-toggle="tooltip" data-placement="top"
                                                                            title="Pilih">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="16" height="16"
                                                                                viewBox="0 0 24 24" fill="none"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script src="{{ asset('assets/js/libs/jquery-3.1.1.min.js') }}"></script>
        <script src="{{ asset('bootstrap/js/popper.min.js') }}"></script>
        <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/authentication/form-2.js') }}"></script>
    </body>

</html>



{{-- @endsection --}}
