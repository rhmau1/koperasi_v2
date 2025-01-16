@extends('template.main')
@section('content')
    <div class="layout-px-spacing">

        <div id="flHorizontalForm" class="col-lg-12 layout-top-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Tambah level form</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <form action="{{ route('admin.levelUser.store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <label for="namaLevel" class="col-sm-2 col-form-label">Nama Level</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="namaLevel" name="nama_level">
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
                            <label for="Menu Akses" class="col-sm-2 col-form-label">Menu Akses</label>
                            <div class="col-sm-10">
                                <table id="style-2" class="table style-2  table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama Menu</th>
                                            <th class="text-center">Melihat</th>
                                            <th class="text-center">Tambah</th>
                                            <th class="text-center">Ubah</th>
                                            <th class="text-center">Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($menus as $menu)
                                            <tr>
                                                <td>{{ $menu->nama_menu }}</td>
                                                @if ($menu->subMenus->isEmpty())
                                                    <td class="text-center">
                                                        <input type="checkbox" name="melihat[{{ $menu->id_menu }}]"
                                                            id="melihat_{{ $menu->id_menu }}">
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="hak_add[{{ $menu->id_menu }}]"
                                                            id="hak_add_{{ $menu->id_menu }}">
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="hak_edit[{{ $menu->id_menu }}]"
                                                            id="hak_edit_{{ $menu->id_menu }}">
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="hak_delete[{{ $menu->id_menu }}]"
                                                            id="hak_delete_{{ $menu->id_menu }}">
                                                    </td>
                                                @else
                                                    <td class="text-center"></td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center"></td>
                                                @endif
                                            </tr>
                                            @if ($menu->subMenus->isNotEmpty())
                                                @foreach ($menu->subMenus as $submenu)
                                                    <tr>
                                                        <td>&nbsp;&nbsp;&nbsp; > {{ $submenu->nama_menu }}</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" name="melihat[{{ $submenu->id_menu }}]"
                                                                id="melihat_{{ $submenu->id_menu }}">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="checkbox" name="hak_add[{{ $submenu->id_menu }}]"
                                                                id="hak_add_{{ $submenu->id_menu }}">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="checkbox" name="hak_edit[{{ $submenu->id_menu }}]"
                                                                id="hak_edit_{{ $submenu->id_menu }}">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="checkbox"
                                                                name="hak_delete[{{ $submenu->id_menu }}]"
                                                                id="hak_delete_{{ $submenu->id_menu }}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <a href="{{ route('admin.levelUser') }}" type="button" class="btn btn-primary">Kembali</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
