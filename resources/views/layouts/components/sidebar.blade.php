@php

    $menu = [
        (object) [
            'title' => 'Dashboard',
            'path' => '/dashboard',
            'icon' => 'fa fa-tachometer-alt',
            'allowed_roles' => ['admin'],
        ],

        (object) [
            'title' => 'Rekap Absensi',
            'path' => '/halamanRekap',
            'icon' => 'fas fa-edit',
            'allowed_roles' => ['admin', 'karyawan'],
        ],
        (object) [
            'title' => 'Table Referensi',
            'path' => '/#',
            'header' => 'MASTER',
            'icon' => 'fas fa-tasks',
            'sub_menu' => [
                (object) [
                    'title' => 'Tambah & Edit Maskapai',
                    'path' => '/items/menegItems',
                    'allowed_roles' => ['admin'],
                ],
                (object) [
                    'title' => 'Tambah & Edit Rute',
                    'path' => '/items/Kategori',
                    'allowed_roles' => ['admin'],
                ],
                (object) [
                    'title' => 'Tambah & Edit Mitra / Customer',
                    'path' => '/items/Kategori',
                    'allowed_roles' => ['admin'],
                ],
            ],
            'allowed_roles' => ['admin'],
        ],
        // (object) [
        // 'title' => 'Pesanan',
        // 'path' => '/report/pesanan',
        // 'icon' => 'fas fa-file-alt',
        // 'allowed_roles' => ['admin'],
        // ],
        // (object) [
        // 'title' => 'Pembukuan',
        // 'path' => '/report/pembukuan',
        // 'icon' => 'fas fa-book',
        // 'allowed_roles' => ['admin'],
        // ],
        (object) [
            'title' => 'Manajemen User',
            'path' => '/users',
            'icon' => 'fa fa-user',
            'allowed_roles' => ['admin'],
        ],
    ];
@endphp

<aside class="main-sidebar sidebar-light-success elevation-4">
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('templates/dist/img/profile.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">SIREKTA</span>
    </a>
    <!-- Sidebar -->

    <div class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('templates/dist/img/home2.webp') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="ml-2 d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
       with font-awesome or any other icon font library -->


                @php
                    // Ambil peran pengguna saat ini
                    $currentUserRole = auth()->user()->role; // Misalnya, 'admin' atau 'karyawan'
                @endphp

                @foreach ($menu as $item)
                    @if (isset($item->header))
                        <li class="nav-header">{{ $item->header }}</li>
                    @endif

                    @if (isset($item->allowed_roles) && in_array($currentUserRole, $item->allowed_roles))
                        <li
                            class="nav-item {{ request()->is(trim($item->path, '/')) || (isset($item->sub_menu) && collect($item->sub_menu)->contains(fn($sub) => request()->is(trim($sub->path, '/')))) ? 'menu-open' : '' }}">
                            <a href="{{ $item->path }}"
                                class="nav-link {{ request()->is(trim($item->path, '/')) || (isset($item->sub_menu) && collect($item->sub_menu)->contains(fn($sub) => request()->is(trim($sub->path, '/')))) ? 'active' : '' }}">
                                <i class="nav-icon {{ $item->icon }}"></i>
                                <p>{{ $item->title }}</p>
                                @if (isset($item->sub_menu))
                                    <i class="fas fa-angle-left right"></i>
                                @endif
                            </a>
                            @if (isset($item->sub_menu))
                                <ul class="nav nav-treeview">
                                    @foreach ($item->sub_menu as $subItem)
                                        @if (in_array($currentUserRole, $subItem->allowed_roles ?? []))
                                            <li class="nav-item">
                                                <a href="{{ $subItem->path }}"
                                                    class="nav-link {{ request()->is(trim($subItem->path, '/')) ? 'active' : '' }}">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>{{ $subItem->title }}</p>
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endif
                @endforeach




            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
