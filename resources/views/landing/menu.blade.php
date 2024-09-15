@extends('layouts.landing')
@section('content')
    <style>
        .search-filter-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .search-input,
        .category-dropdown-btn {
            padding: 8px 15px;
            border: 1px solid #007bff;
            border-radius: 20px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .search-input {
            width: 200px;
        }

        .search-input:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        .category-dropdown {
            position: relative;
            display: inline-block;
        }

        .category-dropdown-btn {
            background-color: white;
            color: #007bff;
            cursor: pointer;
            min-width: 150px;
            text-align: left;
        }

        .category-dropdown-btn:hover,
        .category-dropdown-btn:focus {
            background-color: #f0f8ff;
        }

        .category-dropdown-btn::after {
            content: '\25BC';
            float: right;
        }

        .category-dropdown-content {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 150px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.1);
            z-index: 1;
            border-radius: 10px;
            overflow: hidden;
        }

        .category-dropdown-content button {
            color: black;
            padding: 8px 15px;
            text-decoration: none;
            display: block;
            width: 100%;
            text-align: left;
            border: none;
            background-color: transparent;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .category-dropdown-content button:hover {
            background-color: #f0f8ff;
        }

        .show {
            display: block;
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .category-title {
            font-size: 20px;
            color: #007bff;
            margin-bottom: 15px;
        }

        .hidden {
            display: none;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <section id="menu" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center section-title mb-4">Semua Menu</h2>

            <div class="search-filter-container">
                <input type="text" id="searchInput" class="search-input" placeholder="Cari menu...">
                <div class="category-dropdown">
                    <button id="categoryDropdownBtn" class="category-dropdown-btn">Semua Kategori</button>
                    <div id="categoryDropdownContent" class="category-dropdown-content">
                        <button data-category="all">Semua Kategori</button>
                        @foreach ($allMenus->groupBy('kategori_id') as $kategoriId => $menus)
                            <button data-category="{{ $menus->first()->kategori->nama }}">
                                {{ $menus->first()->kategori->nama }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row" id="menuContainer">
                @foreach ($allMenus->groupBy('kategori_id') as $kategoriId => $menus)
                    <div class="col-12 mb-4 category-header">
                        <h3 class="category-title">{{ $menus->first()->kategori->nama }}</h3>
                    </div>
                    @foreach ($menus as $menu)
                        <div class="col-md-4 mb-4 menu-item" data-name="{{ strtolower($menu->nama) }}"
                            data-category="{{ strtolower($menu->kategori->nama) }}">
                            <div class="card h-100 shadow-sm">
                                <img src="{{ asset('fotoMenu/' . $menu->foto) }}" class="card-img-top"
                                    alt="{{ $menu->nama }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $menu->nama }}</h5>
                                    <p class="card-text">{{ $menu->kategori->nama }}</p>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modal{{ $menu->id }}">Lihat Detail</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>

        @foreach ($allMenus as $menu)
            <div class="modal fade" id="modal{{ $menu->id }}" tabindex="-1"
                aria-labelledby="modal{{ $menu->id }}Label" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal{{ $menu->id }}Label">{{ $menu->nama }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="{{ asset('fotoMenu/' . $menu->foto) }}" class="img-fluid rounded"
                                        alt="{{ $menu->nama }}">
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold">Kategori:</h6>
                                    <p>{{ $menu->kategori->nama }}</p>
                                    <h6 class="fw-bold mt-3">Pilih Varian:</h6>
                                    <div class="varian-options">
                                        @foreach ($menu->jenisMenu as $jenis)
                                            <button
                                                class="varian-btn {{ $loop->first ? 'active' : '' }}">{{ $jenis->jenis }}
                                                - Rp {{ number_format($jenis->harga, 0, ',', '.') }}</button>
                                        @endforeach
                                    </div>
                                    <h6 class="fw-bold mt-3">Komentar Terbaru:</h6>
                                    <div class="comments-container" style="max-height: 200px; overflow-y: auto;">
                                        @forelse ($menu->recent_comments as $comment)
                                            <div class="comment-card mb-2 p-2 border rounded">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <strong>{{ $comment->user->username }}</strong>
                                                    <div class="star-rating">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= $comment->rating)
                                                                <i class="fas fa-star text-warning"></i>
                                                            @else
                                                                <i class="far fa-star text-warning"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                </div>
                                                <p class="mb-0 mt-1">{{ $comment->isi_komentar }}</p>
                                            </div>
                                        @empty
                                            <p>Belum ada komentar.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-primary">Tambah ke Keranjang</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const categoryDropdownBtn = document.getElementById('categoryDropdownBtn');
            const categoryDropdownContent = document.getElementById('categoryDropdownContent');
            const categoryOptions = categoryDropdownContent.querySelectorAll('button');
            const menuItems = document.querySelectorAll('.menu-item');
            const categoryHeaders = document.querySelectorAll('.category-header');

            let activeCategory = 'all';

            function filterMenuItems() {
                const searchTerm = searchInput.value.toLowerCase();

                menuItems.forEach(item => {
                    const name = item.dataset.name;
                    const category = item.dataset.category;
                    const matchesSearch = name.includes(searchTerm);
                    const matchesCategory = activeCategory === 'all' || category === activeCategory
                        .toLowerCase();

                    if (matchesSearch && matchesCategory) {
                        item.classList.remove('hidden');
                        item.classList.add('fade-in');
                    } else {
                        item.classList.add('hidden');
                        item.classList.remove('fade-in');
                    }
                });

                updateCategoryHeaders();
            }

            function updateCategoryHeaders() {
                categoryHeaders.forEach(header => {
                    const categoryName = header.querySelector('.category-title').textContent;
                    const hasVisibleItems = Array.from(menuItems).some(item =>
                        !item.classList.contains('hidden') && item.dataset.category.toLowerCase() ===
                        categoryName.toLowerCase()
                    );
                    header.classList.toggle('hidden', !hasVisibleItems);
                });
            }

            searchInput.addEventListener('input', filterMenuItems);

            categoryDropdownBtn.addEventListener('click', function(event) {
                event.stopPropagation();
                categoryDropdownContent.classList.toggle('show');
            });

            categoryOptions.forEach(option => {
                option.addEventListener('click', function() {
                    activeCategory = this.dataset.category;
                    categoryDropdownBtn.textContent = this.textContent;
                    categoryDropdownContent.classList.remove('show');
                    filterMenuItems();
                });
            });

            // Close the dropdown if the user clicks outside of it
            window.addEventListener('click', function() {
                categoryDropdownContent.classList.remove('show');
            });

            // Initial filter
            filterMenuItems();
        });
    </script>
@endsection
