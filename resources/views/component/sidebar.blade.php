<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="{{ url('/') }}" class="logo">
                <img src="{{ asset('admin/assets/img/kaiadmin/logo_light.svg') }}" alt="navbar brand" class="navbar-brand"
                    height="20" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Dashboard Menu</h4>
                </li>
                <li class="nav-item {{ Request::path() == 'admin/dashboard' ? 'active' : '' }}">
                    <a href="{{ url('admin/dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Main Menu</h4>
                </li>
                <li class="nav-item {{ Request::path() == 'admin/user' ? 'active' : '' }}">
                    <a href="{{ url('admin/user') }}">
                        <i class="fas fa-user"></i>
                        <p>User</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::path() == 'admin/kategori' ? 'active' : '' }}">
                    <a href="{{ url('admin/kategori') }}">
                        <i class="fas fa-list-ul"></i>
                        <p>Kategori</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::path() == 'admin/menu' ? 'active' : '' }}">
                    <a href="{{ url('admin/menu') }}">
                        <i class="fas fa-layer-group"></i>
                        <p>Menu</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Order Menu</h4>
                </li>
                <li class="nav-item {{ Request::path() == 'admin/pesanan' ? 'active' : '' }}">
                    <a href="{{ url('admin/pesanan') }}">
                        <i class="fas fa-shopping-cart"></i>
                        <p>Pesanan</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::path() == 'admin/riwayat' ? 'active' : '' }}">
                    <a href="{{ url('admin/riwayat') }}">
                        <i class="fas fa-book"></i>
                        <p>Riwayat</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('admin/assets/js/your-sidebar-script.js') }}"></script> <!-- Ensure you have your sidebar scripts included -->

<!-- Add your custom script -->
<script>
    $(document).ready(function() {
        $('.toggle-sidebar').on('click', function() {
            $('.sidebar').toggleClass('collapsed');
        });

        $('.sidenav-toggler').on('click', function() {
            $('.sidebar').toggleClass('collapsed');
        });

        $('.topbar-toggler').on('click', function() {
            $('.sidebar').toggleClass('collapsed');
        });
    });
</script>
