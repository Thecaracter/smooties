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
                        alt="About Smoothie Haven" class="img-fluid about-image">
                </div>
                <div class="col-lg-6">
                    <h3>Selamat Datang di Smoothie Haven</h3>
                    <p>Kami adalah perusahaan yang berdedikasi untuk menyajikan smoothie berkualitas tinggi dengan
                        bahan-bahan segar dan alami. Didirikan pada tahun 2020, Smoothie Haven telah menjadi tujuan
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
                <!-- Berry Blast Smoothie -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='200' viewBox='0 0 300 200'%3E%3Crect width='300' height='200' fill='%23f8d7da'/%3E%3Ccircle cx='150' cy='100' r='80' fill='%23e91e63'/%3E%3Cpath d='M110 140 Q 150 60 190 140' stroke='white' stroke-width='8' fill='none'/%3E%3C/svg%3E"
                            class="card-img-top" alt="Berry Blast Smoothie">
                        <div class="card-body">
                            <h5 class="card-title">Berry Blast Smoothie</h5>
                            <p class="card-text">Ledakan rasa dari campuran berry segar pilihan.</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal1">Lihat
                                Detail</button>
                        </div>
                    </div>
                </div>

                <!-- Green Detox Smoothie -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='200' viewBox='0 0 300 200'%3E%3Crect width='300' height='200' fill='%23d4edda'/%3E%3Ccircle cx='150' cy='100' r='80' fill='%234caf50'/%3E%3Cpath d='M110 140 Q 150 60 190 140' stroke='white' stroke-width='8' fill='none'/%3E%3C/svg%3E"
                            class="card-img-top" alt="Green Detox Smoothie">
                        <div class="card-body">
                            <h5 class="card-title">Green Detox Smoothie</h5>
                            <p class="card-text">Smoothie detox kaya nutrisi untuk tubuh sehat.</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal2">Lihat
                                Detail</button>
                        </div>
                    </div>
                </div>

                <!-- Tropical Paradise Smoothie -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='200' viewBox='0 0 300 200'%3E%3Crect width='300' height='200' fill='%23fff3cd'/%3E%3Ccircle cx='150' cy='100' r='80' fill='%23ffc107'/%3E%3Cpath d='M110 140 Q 150 60 190 140' stroke='white' stroke-width='8' fill='none'/%3E%3C/svg%3E"
                            class="card-img-top" alt="Tropical Paradise Smoothie">
                        <div class="card-body">
                            <h5 class="card-title">Tropical Paradise Smoothie</h5>
                            <p class="card-text">Rasakan sensasi tropis dalam setiap tegukan.</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal3">Lihat
                                Detail</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Berry Blast Smoothie -->
        <div class="modal fade" id="modal1" tabindex="-1" aria-labelledby="modal1Label" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal1Label">Berry Blast Smoothie</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='200' viewBox='0 0 300 200'%3E%3Crect width='300' height='200' fill='%23f8d7da'/%3E%3Ccircle cx='150' cy='100' r='80' fill='%23e91e63'/%3E%3Cpath d='M110 140 Q 150 60 190 140' stroke='white' stroke-width='8' fill='none'/%3E%3C/svg%3E"
                                    class="img-fluid rounded" alt="Berry Blast Smoothie">
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold">Deskripsi:</h6>
                                <p>Smoothie lezat dengan campuran berry segar seperti stroberi, blueberry, dan raspberry.
                                    Kaya akan antioksidan dan vitamin.</p>
                                <h6 class="fw-bold mt-3">Pilih Varian:</h6>
                                <div class="varian-options">
                                    <button class="varian-btn active">Regular</button>
                                    <button class="varian-btn">Large</button>
                                    <button class="varian-btn">Extra Berry</button>
                                    <button class="varian-btn">No Sugar</button>
                                </div>
                                <h6 class="fw-bold mt-3">Komentar Pelanggan:</h6>
                                <ul class="list-unstyled">
                                    <li>"Sangat segar dan lezat!" - Ani</li>
                                    <li>"Favorit saya setiap pagi." - Budi</li>
                                </ul>
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

        <!-- Modal for Green Detox Smoothie -->
        <div class="modal fade" id="modal2" tabindex="-1" aria-labelledby="modal2Label" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal2Label">Green Detox Smoothie</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='200' viewBox='0 0 300 200'%3E%3Crect width='300' height='200' fill='%23d4edda'/%3E%3Ccircle cx='150' cy='100' r='80' fill='%234caf50'/%3E%3Cpath d='M110 140 Q 150 60 190 140' stroke='white' stroke-width='8' fill='none'/%3E%3C/svg%3E"
                                    class="img-fluid rounded" alt="Green Detox Smoothie">
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold">Deskripsi:</h6>
                                <p>Smoothie detox kaya nutrisi dengan campuran sayuran hijau dan buah-buahan segar. Cocok
                                    untuk program diet dan gaya hidup sehat.</p>
                                <h6 class="fw-bold mt-3">Pilih Varian:</h6>
                                <div class="varian-options">
                                    <button class="varian-btn active">Regular</button>
                                    <button class="varian-btn">Large</button>
                                    <button class="varian-btn">Extra Greens</button>
                                    <button class="varian-btn">With Chia Seeds</button>
                                </div>
                                <h6 class="fw-bold mt-3">Komentar Pelanggan:</h6>
                                <ul class="list-unstyled">
                                    <li>"Rasanya enak dan bikin segar!" - Citra</li>
                                    <li>"Pas banget buat diet." - Dani</li>
                                </ul>
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

        <!-- Modal for Tropical Paradise Smoothie -->
        <div class="modal fade" id="modal3" tabindex="-1" aria-labelledby="modal3Label" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal3Label">Tropical Paradise Smoothie</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='200' viewBox='0 0 300 200'%3E%3Crect width='300' height='200' fill='%23fff3cd'/%3E%3Ccircle cx='150' cy='100' r='80' fill='%23ffc107'/%3E%3Cpath d='M110 140 Q 150 60 190 140' stroke='white' stroke-width='8' fill='none'/%3E%3C/svg%3E"
                                    class="img-fluid rounded" alt="Tropical Paradise Smoothie">
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold">Deskripsi:</h6>
                                <p>Smoothie dengan rasa tropis yang menyegarkan. Terbuat dari campuran mangga, nanas, dan
                                    pepaya segar. Cocok untuk menyegarkan hari-hari panas.</p>
                                <h6 class="fw-bold mt-3">Pilih Varian:</h6>
                                <div class="varian-options">
                                    <button class="varian-btn active">Regular</button>
                                    <button class="varian-btn">Large</button>
                                    <button class="varian-btn">With Coconut</button>
                                    <button class="varian-btn">Extra Mango</button>
                                </div>
                                <h6 class="fw-bold mt-3">Komentar Pelanggan:</h6>
                                <ul class="list-unstyled">
                                    <li>"Rasanya seperti liburan di pantai!" - Eka</li>
                                    <li>"Segar dan manis, suka banget." - Fandi</li>
                                </ul>
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
    </section>

    <script>
        document.querySelectorAll('.varian-btn').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.varian-options').querySelectorAll('.varian-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
            });
        });
    </script>
@endsection
