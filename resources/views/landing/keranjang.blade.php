@extends('layouts.landing')
@section('content')
    <section id="keranjang" class="py-5">
        <div class="container">
            <h2 class="text-center section-title">Keranjang Belanja</h2>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Varian</th>
                            <th>Harga</th>
                            <th style="text-align: center;">Jumlah</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="cartItems">
                        <!-- Items will be dynamically added here -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                            <td id="cartSubtotal"><strong>0</strong></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Jarak Pengiriman:</strong></td>
                            <td id="shippingDistance"><strong>0 km</strong></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Biaya Pengiriman:</strong></td>
                            <td id="shippingCost"><strong>0</strong></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Total:</strong></td>
                            <td id="grandTotal"><strong>0</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @if (Auth::check())
                <div class="text-end mt-3">
                    <button id="checkoutButton" class="btn btn-primary">Lanjutkan ke Pembayaran</button>
                </div>
            @else
                <div class="text-end mt-3">
                    <button id="loginButton" class="btn btn-primary">Login untuk Melanjutkan</button>
                </div>
            @endif
            <div id="map" style="height: 400px; margin-top: 20px;"></div>
            <div class="mt-3 d-flex justify-content-start gap-2">
                <button onclick="toggleAddressSearch()" class="btn btn-primary">Cari Alamat</button>
                <button onclick="locateUser()" class="btn btn-secondary">Dapatkan Lokasi Saya</button>
            </div>
            <div id="addressSearchContainer" style="display: none; margin-top: 10px;">
                <div class="input-group mb-3">
                    <input type="text" id="addressInput" class="form-control" placeholder="Masukkan alamat Anda"
                        autocomplete="off">
                    <button class="btn btn-outline-secondary" type="button" onclick="searchAddress()">Cari</button>
                </div>
                <ul id="suggestions" class="list-group"
                    style="position: absolute; z-index: 1000; width: calc(100% - 30px);"></ul>
            </div>
        </div>
    </section>

    <div id="loadingOverlay"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white;">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p>Menghitung ongkir...</p>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <script src="{{ asset('landing/js/keranjang.js') }}"></script>
@endsection
