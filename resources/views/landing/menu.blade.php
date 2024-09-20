@extends('layouts.landing')

@section('content')
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
                        <div class="col-md-4 mb-4 menu-item" data-id="{{ $menu->id }}"
                            data-name="{{ strtolower($menu->nama) }}"
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
            <div class="modal fade" id="modal{{ $menu->id }}" data-id="{{ $menu->id }}" tabindex="-1"
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
                                    <p class="menu-category">{{ $menu->kategori->nama }}</p>
                                    <h6 class="fw-bold mt-3">Pilih Varian:</h6>
                                    <div class="varian-options">
                                        @foreach ($menu->jenisMenu as $jenis)
                                            <button class="varian-btn {{ $loop->first ? 'active' : '' }}"
                                                onclick="selectVariant(this, '{{ $menu->id }}', '{{ $menu->nama }}', '{{ $jenis->jenis }}', {{ $jenis->harga }})">
                                                {{ $jenis->jenis }} - Rp {{ number_format($jenis->harga, 0, ',', '.') }}
                                            </button>
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
                            <button type="button" class="btn btn-primary"
                                onclick="addToCart('{{ $menu->id }}', '{{ $menu->nama }}')">
                                Tambah ke Keranjang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </section>

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
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

            // Initial update of cart badge
            updateCartBadge();

            // Konfigurasi Pusher
            const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
                encrypted: true
            });

            // Subscribe ke channel menu
            const menuChannel = pusher.subscribe('menu');

            // Listen untuk event menu-updated
            menuChannel.bind('menu-updated', function(data) {
                console.log('Menu updated:', data.menu);
                updateMenu(data.menu);
            });

            // Subscribe ke channel jenis-menu
            const jenisMenuChannel = pusher.subscribe('jenis-menu');

            // Listen untuk event jenis-menu-updated
            jenisMenuChannel.bind('jenis-menu-updated', function(data) {
                console.log('Jenis Menu updated:', data.jenisMenu);
                updateJenisMenu(data.jenisMenu, data.menu);
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

            // Hapus kelas 'active' dari semua tombol dalam modal yang sama
            button.closest('.varian-options').querySelectorAll('.varian-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Tambahkan kelas 'active' ke tombol yang dipilih
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

            // Reset selectedVariant setelah menambahkan ke keranjang
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

        function updateMenu(updatedMenu) {
            const menuItem = document.querySelector(`.menu-item[data-id="${updatedMenu.id}"]`);
            if (menuItem) {
                // Update nama menu
                menuItem.querySelector('.card-title').textContent = updatedMenu.nama;

                // Update kategori
                menuItem.querySelector('.card-text').textContent = updatedMenu.kategori.nama;

                // Update gambar
                menuItem.querySelector('.card-img-top').src = `/fotoMenu/${updatedMenu.foto}`;

                // Update data atribut
                menuItem.dataset.name = updatedMenu.nama.toLowerCase();
                menuItem.dataset.category = updatedMenu.kategori.nama.toLowerCase();

                // Update modal
                updateMenuModal(updatedMenu);

                // Re-run filter untuk memastikan item tetap terlihat jika sesuai dengan filter saat ini
                filterMenuItems();
            }
        }

        function updateMenuModal(updatedMenu) {
            const modal = document.querySelector(`#modal${updatedMenu.id}`);
            if (modal) {
                modal.querySelector('.modal-title').textContent = updatedMenu.nama;
                modal.querySelector('.img-fluid').src = `/fotoMenu/${updatedMenu.foto}`;

                // Update varian
                updateJenisMenuOptions(modal, updatedMenu.jenis_menu);

                // Update komentar
                updateComments(modal, updatedMenu.recent_comments);
            }
        }

        function updateJenisMenu(updatedJenisMenu, updatedMenu) {
            const modal = document.querySelector(`#modal${updatedMenu.id}`);
            if (modal) {
                updateJenisMenuOptions(modal, updatedMenu.jenis_menu);
            }
        }

        function updateJenisMenuOptions(modal, jenisMenus) {
            const varianOptions = modal.querySelector('.varian-options');
            varianOptions.innerHTML = '';
            jenisMenus.forEach((jenis, index) => {
                const button = document.createElement('button');
                button.className = `varian-btn ${index === 0 ? 'active' : ''}`;
                button.onclick = () => selectVariant(button, jenis.menu_id, jenis.menu.nama, jenis.jenis, jenis
                    .harga);
                button.textContent = `${jenis.jenis} - Rp ${jenis.harga.toLocaleString('id-ID')}`;
                varianOptions.appendChild(button);
            });
        }

        function updateComments(modal, comments) {
            const commentsContainer = modal.querySelector('.comments-container');
            commentsContainer.innerHTML = '';
            if (comments && comments.length > 0) {
                comments.forEach(comment => {
                    const commentElement = document.createElement('div');
                    commentElement.className = 'comment-card mb-2 p-2 border rounded';
                    commentElement.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>${comment.user.username}</strong>
                            <div class="star-rating">
                                ${Array(5).fill().map((_, i) => 
                                    `<i class="${i < comment.rating ? 'fas' : 'far'} fa-star text-warning"></i>`
                                ).join('')}
                            </div>
                        </div>
                        <p class="mb-0 mt-1">${comment.isi_komentar}</p>
                    `;
                    commentsContainer.appendChild(commentElement);
                });
            } else {
                commentsContainer.innerHTML = '<p>Belum ada komentar.</p>';
            }
        }
    </script>
@endsection
