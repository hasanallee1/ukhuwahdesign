<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name', 'Laravel') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('storage/profile-image/' . Auth::user()->image) }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"> {{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
        with font-awesome or any other icon font library -->

                <li class="nav-item">
                    <a href="{{ route('admin.dashboard.index') }}" class="nav-link {{ setActive('admin/dashboard') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>


                @if (auth()->user()->can('posts.index') ||
                        auth()->user()->can('testimonies.index') ||
                        auth()->user()->can('categories.index'))
                    <li class="nav-header">Manajemen Konten</li>
                @endif

                @can('categories.index')
                    <li class="nav-item">
                        <a href="{{ route('admin.category.index') }}" class="nav-link {{ setActive('admin/category') }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Kategori
                            </p>
                        </a>
                    </li>
                @endcan

                @can('testimonies.index')
                    <li class="nav-item">
                        <a href="{{ route('admin.testimony.index') }}" class="nav-link {{ setActive('admin/testimony') }}">
                            <i class="nav-icon fas fa-comment"></i>
                            <p>
                                Testimoni
                            </p>
                        </a>
                    </li>
                @endcan

                @can('posts.index')
                    <li class="nav-item">
                        <a href="{{ route('admin.post.index') }}" class="nav-link {{ setActive('admin/post') }}">
                            <i class="nav-icon fas fa-edit"></i>
                            <p>
                                Posts
                            </p>
                        </a>
                    </li>
                @endcan


                @if (auth()->user()->can('users.index') ||
                        auth()->user()->can('roles.index') ||
                        auth()->user()->can('permission.index'))
                    <li class="nav-header">PENGATURAN</li>

                    <li class="nav-item" id="menu-open">
                        <a href=""
                            class="nav-link {{ setActive('admin/permission') . setActive('admin/role') . setActive('admin.user') }}"
                            id="open">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Manajemen User
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('permissions.index')
                                <li class="nav-item">
                                    <a href="{{ route('admin.permission.index') }}"
                                        class="nav-link {{ setActive('admin/permission') }}">
                                        <i class="far fa-eye nav-icon"></i>
                                        <p>Permission</p>
                                    </a>
                                </li>
                            @endcan
                            @can('roles.index')
                                <li class="nav-item">
                                    <a href="{{ route('admin.role.index') }}"
                                        class="nav-link {{ setActive('admin/role') }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Role</p>
                                    </a>
                                </li>
                            @endcan
                            @can('users.index')
                                <li class="nav-item">
                                    <a href="{{ route('admin.user.index') }}"
                                        class="nav-link {{ setActive('admin/user') }}">
                                        <i class="far fa-user nav-icon"></i>
                                        <p>User</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

@push('scripts')
    <script>
        $(document).ready(function() {
            if ($('#open').hasClass('active')) {
                $('#menu-open').addClass('menu-open');
            };
        });
    </script>
@endpush
