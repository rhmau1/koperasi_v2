<!--  BEGIN SIDEBAR  -->
<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            @foreach ($menus as $menu)
                <li class="menu">
                    <a href="#{{ $menu->page }}" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-home">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            <span>{{ $menu->nama_menu }}</span>
                        </div>
                        @if ($menu->subMenus->isNotEmpty())
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        @endif
                    </a>
                    @if ($menu->subMenus->isNotEmpty())
                        <ul class="collapse submenu list-unstyled" id="{{ $menu->page }}"
                            data-parent="#accordionExample">
                            @foreach ($menu->subMenus as $submenu)
                                <li>
                                    <a href="#{{ $menu->page }}"> {{ $submenu->nama_menu }} </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
        <!-- <div class="shadow-bottom"></div> -->

    </nav>

</div>
<!--  END SIDEBAR  -->
