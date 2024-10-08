<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Smoothies by Vie</title>
    <link rel="icon" href="{{ asset('landing/foto/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('landing/style.css') }}">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('landing/foto/logo.png') }}" alt="Smoothie Icon" width="40" height="40">
                <span class="brand-text">Smoothies by Vie</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/#beranda">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/#tentang">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('menu') }}">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('keranjang') }}">Keranjang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('riwayat') }}">Riwayat</a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->username }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                </li>
                            </ul>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')
    <footer class="footer py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Smoothie by Vie</h5>
                    <p>Menyajikan smoothie segar dan sehat untuk Anda.</p>
                </div>
                <div class="col-md-3">
                    <h5>Tautan Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="#beranda" class="text-light">Beranda</a></li>
                        <li><a href="#tentang" class="text-light">Tentang Kami</a></li>
                        <li><a href="#menu" class="text-light">Menu</a></li>
                        <li><a href="#keranjang" class="text-light">Keranjang</a></li>
                        <li><a href="#riwayat" class="text-light">Riwayat</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Hubungi Kami</h5>
                    <p><i class="fas fa-envelope me-2"></i> info@smoothiehaven.com<br>
                        <i class="fas fa-phone me-2"></i> (021) 1234-5678
                    </p>
                </div>
            </div>
            <hr class="mt-4 mb-3">
            <div class="text-center">
                <p>&copy; 2024 Smoothie By Vie. Hak Cipta @codingin.</p>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.varian-btn').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.varian-options').querySelectorAll('.varian-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
            });
        });
        let selectedVariant = {
            jenis: '',
            harga: 0
        };

        function selectVariant(button, menuId, menuNama, varianJenis, varianHarga) {
            selectedVariant = {
                jenis: varianJenis,
                harga: varianHarga
            };
            button.closest('.varian-options').querySelectorAll('.varian-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            button.classList.add('active');
        }

        function addToCart(menuId, menuNama) {
            if (selectedVariant.jenis === '') {
                alert('Silakan pilih varian terlebih dahulu!');
                return;
            }

            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            let item = cart.find(i => i.id === menuId && i.varian === selectedVariant.jenis);

            if (item) {
                item.quantity += 1;
            } else {
                cart.push({
                    id: menuId,
                    nama: menuNama,
                    varian: selectedVariant.jenis,
                    harga: selectedVariant.harga,
                    quantity: 1
                });
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            alert('Item berhasil ditambahkan ke keranjang!');
            updateCartBadge();

            selectedVariant = {
                jenis: '',
                harga: 0
            };
        }

        function updateCartBadge() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            let totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            let cartBadge = document.getElementById('cartBadge');
            if (cartBadge) {
                cartBadge.textContent = totalItems;
            }
        }

        document.addEventListener('DOMContentLoaded', updateCartBadge);
    </script>
    @yield('scripts')
</body>

</html>
