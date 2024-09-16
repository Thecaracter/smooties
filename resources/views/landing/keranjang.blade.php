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
                            <td colspan="4" class="text-end"><strong>Total:</strong></td>
                            <td id="grandTotal"><strong>0</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="text-end mt-3">
                <a href="#" class="btn btn-primary">Lanjutkan ke Pembayaran</a>
            </div>
            <div id="map" style="height: 400px; margin-top: 20px;"></div>
            <button onclick="locateUser()" class="btn btn-secondary mt-2">Dapatkan Lokasi Saya</button>
        </div>
    </section>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        function updateQuantity(itemId, varianJenis, change) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            let item = cart.find(i => i.id === itemId && i.varian === varianJenis);
            if (item) {
                item.quantity += change;
                if (item.quantity < 1) {
                    cart = cart.filter(i => i !== item);
                }
            }
            localStorage.setItem('cart', JSON.stringify(cart));
            displayCart();
            updateCartBadge();
        }

        function removeItem(itemId, varianJenis) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            cart = cart.filter(i => !(i.id === itemId && i.varian === varianJenis));
            localStorage.setItem('cart', JSON.stringify(cart));
            displayCart();
            updateCartBadge();
        }

        function displayCart() {
            const cartItems = document.getElementById('cartItems');
            const grandTotal = document.getElementById('grandTotal');
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            let total = 0;

            cartItems.innerHTML = '';
            cart.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.nama}</td>
                    <td>${item.varian}</td>
                    <td class="harga">${item.harga}</td>
                    <td style="text-align: center; vertical-align: middle;">
                        <div class="input-group" style="justify-content: center; width: 150px; margin: 0 auto;">
                            <button class="btn btn-outline-secondary" onclick="updateQuantity('${item.id}', '${item.varian}', -1)">-</button>
                            <input type="number" class="form-control jumlah" value="${item.quantity}" min="1" readonly style="width: 50px; text-align: center;">
                            <button class="btn btn-outline-secondary" onclick="updateQuantity('${item.id}', '${item.varian}', 1)">+</button>
                        </div>
                    </td>
                    <td class="total">${item.harga * item.quantity}</td>
                    <td><button class="btn btn-sm btn-danger" onclick="removeItem('${item.id}', '${item.varian}')"><i class="fas fa-trash"></i></button></td>
                `;
                cartItems.appendChild(row);
                total += item.harga * item.quantity;
            });

            grandTotal.textContent = total;
        }

        let map;
        let userMarker;

        function initMap() {
            map = L.map('map').setView([-6.2088, 106.8456], 13); // Default to Jakarta
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
        }

        function locateUser() {
            map.locate({
                setView: true,
                maxZoom: 16
            });

            map.on('locationfound', function(e) {
                if (userMarker) {
                    map.removeLayer(userMarker);
                }
                userMarker = L.marker(e.latlng).addTo(map)
                    .bindPopup("Lokasi Anda: " + e.latlng.lat.toFixed(5) + ", " + e.latlng.lng.toFixed(5))
                    .openPopup();
            });

            map.on('locationerror', function(e) {
                alert("Lokasi tidak ditemukan: " + e.message);
            });
        }

        // Call this function when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            displayCart();
            initMap();
        });
    </script>
@endsection
