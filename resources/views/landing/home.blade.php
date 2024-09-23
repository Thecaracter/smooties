@extends('layouts.landing')
@section('content')
    <section id="beranda" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Rasakan Kesegaran dalam Setiap Tegukan</h1>
                    <p class="lead mb-4">Nikmati smoothie lezat kami yang dibuat dari bahan-bahan alami terbaik untuk
                        menyegarkan hari Anda.</p>
                    <a href="#menu" class="btn btn-primary btn-lg">Jelajahi Menu</a>
                </div>
                <div class="col-lg-6">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='500' height='300' viewBox='0 0 500 300'%3E%3Crect width='500' height='300' fill='%23c5cae9' fill-opacity='0.5'/%3E%3Ccircle cx='250' cy='150' r='100' fill='%233949ab' fill-opacity='0.8'/%3E%3Cpath d='M200 200 Q 250 100 300 200 L 300 250 Q 250 300 200 250 Z' fill='%231a237e' fill-opacity='0.9'/%3E%3Cpath d='M180 170 Q 250 50 320 170' stroke='white' stroke-width='8' fill='none'/%3E%3C/svg%3E"
                        alt="Smoothie Illustration" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <section id="tentang" class="py-5">
        <div class="container">
            <h2 class="text-center section-title">Tentang Kami</h2>
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='500' height='300' viewBox='0 0 500 300'%3E%3Crect width='500' height='300' fill='%23e8eaf6'/%3E%3Ccircle cx='150' cy='150' r='100' fill='%233949ab' fill-opacity='0.8'/%3E%3Ccircle cx='350' cy='150' r='100' fill='%231a237e' fill-opacity='0.9'/%3E%3Cpath d='M150 150 L 350 150' stroke='white' stroke-width='8' fill='none'/%3E%3C/svg%3E"
                        alt="About Smootie by Vie" class="img-fluid about-image">
                </div>
                <div class="col-lg-6">
                    <h3>Selamat Datang di Smootie by Vie</h3>
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
