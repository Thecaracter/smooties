@extends('layouts.landing')
@section('content')
    <section id="beranda" class="hero-section">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active"
                    style="background-image: url('https://images.unsplash.com/photo-1505252585461-04db1eb84625?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1600&q=80');">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <h1 class="display-4 fw-bold mb-4">Selamat Datang di Smootie by Vie</h1>
                                <p class="lead mb-4">Nikmati smoothie lezat kami yang dibuat dari bahan-bahan alami terbaik
                                    untuk menyegarkan hari Anda.</p>
                                <a href="#menu" class="btn btn-primary btn-lg">Jelajahi Menu</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item"
                    style="background-image: url('https://images.unsplash.com/photo-1614204424926-196a80bf0be8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1600&q=80');">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6 ms-auto">
                                <h1 class="display-4 fw-bold mb-4">Rasakan Kesegaran Alami</h1>
                                <p class="lead mb-4">Setiap tegukan smoothie kami penuh dengan nutrisi dan cita rasa yang
                                    memanjakan lidah Anda.</p>
                                <a href="#tentang" class="btn btn-primary btn-lg">Tentang Kami</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item"
                    style="background-image: url('https://images.unsplash.com/photo-1605870445919-838d190e8e1b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1600&q=80');">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <h1 class="display-4 fw-bold mb-4">Hidupkan Gaya Hidup Sehat</h1>
                                <p class="lead mb-4">Temukan berbagai pilihan smoothie yang tidak hanya lezat, tapi juga
                                    mendukung kesehatan Anda.</p>
                                <a href="#menu" class="btn btn-primary btn-lg">Lihat Menu</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <section id="tentang" class="py-5">
        <div class="container">
            <h2 class="text-center section-title">Tentang Kami</h2>
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.9721704796207!2d106.7329903!3d-6.397587799999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69e90052319d61%3A0x98040e750b8202e6!2ssmoothies%20vie!5e0!3m2!1sen!2sid!4v1727179484166!5m2!1sen!2sid"
                        width="90%" height="300" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade" class="about-image rounded">
                    </iframe>
                </div>
                <div class="col-lg-6">
                    <h3>Rasakan Kesegaran dalam Setiap Tegukan</h3>
                    <p>Kami adalah perusahaan yang berdedikasi untuk menyajikan smoothie berkualitas tinggi dengan
                        bahan-bahan segar dan alami. Didirikan pada tahun 2020, Smootie by Vie telah menjadi tujuan
                        utama bagi pecinta smoothie di seluruh kota.</p>
                    <p>Misi kami adalah mempromosikan gaya hidup sehat melalui minuman lezat dan bergizi. Setiap
                        smoothie kami dibuat dengan cinta dan perhatian khusus terhadap detail untuk memastikan
                        pengalaman rasa yang tak terlupakan.</p>
                    <a href="#menu" class="btn btn-primary">Lihat Menu Kami</a>
                </div>
            </div>
        </div>
    </section>

    <section id="menu" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center section-title mb-4">Menu Terlaris</h2>
            <div class="row">
                @foreach ($topMenus as $menu)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm" data-menu-id="{{ $menu->id }}">
                            <img src="{{ asset('fotoMenu/' . $menu->foto) }}" class="card-img-top"
                                alt="{{ $menu->nama }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $menu->nama }}</h5>
                                <p class="card-text">{{ $menu->kategori->nama }}</p>
                                <button class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modal{{ $menu->id }}">Lihat Detail</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @foreach ($topMenus as $menu)
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
                                            <button class="varian-btn {{ $loop->first ? 'active' : '' }}"
                                                data-harga="{{ $jenis->harga }}"
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
        // Inisialisasi Pusher
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true
        });
    </script>
    <script src="{{ asset('landing/js/home.js') }}"></script>
@endsection
