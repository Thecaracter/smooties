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
            <div class="text-end mt-3">
                <a href="#" class="btn btn-primary">Lanjutkan ke Pembayaran</a>
            </div>
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
    <script>
        let map, userMarker, fixedMarker, routeControl;
        const fixedPoint = [-6.39738522151828, 106.73303321534341];
        const SHIPPING_RATE = 3000; // 3000 rupiah per km

        function initMap() {
            map = L.map('map').setView(fixedPoint, 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            fixedMarker = L.marker(fixedPoint, {
                    draggable: false
                }).addTo(map)
                .bindPopup("Titik Awal").openPopup();

            routeControl = L.Routing.control({
                waypoints: [
                    L.latLng(fixedPoint[0], fixedPoint[1]),
                    L.latLng(fixedPoint[0], fixedPoint[1])
                ],
                routeWhileDragging: true,
                show: false,
                router: L.Routing.osrmv1({
                    serviceUrl: 'https://router.project-osrm.org/route/v1',
                    profile: 'driving'
                })
            }).addTo(map);

            clearUserMarker();
        }

        function clearUserMarker() {
            if (userMarker) {
                map.removeLayer(userMarker);
                userMarker = null;
            }
        }

        function locateUser() {
            if ('geolocation' in navigator) {
                showLoading();
                navigator.geolocation.getCurrentPosition(function(position) {
                    const userLocation = L.latLng(position.coords.latitude, position.coords.longitude);
                    updateUserMarker(userLocation);
                    updateRoute(userLocation);
                }, function(error) {
                    hideLoading();
                    alert("Lokasi tidak ditemukan: " + error.message);
                });
            } else {
                alert("Geolocation tidak didukung oleh browser Anda.");
            }
        }

        function toggleAddressSearch() {
            const container = document.getElementById('addressSearchContainer');
            container.style.display = container.style.display === 'none' ? 'block' : 'none';
        }

        let debounceTimer;

        document.getElementById('addressInput').addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(getSuggestions, 300);
        });

        function getSuggestions() {
            const input = document.getElementById('addressInput');
            const query = input.value;

            if (query.length < 3) {
                document.getElementById('suggestions').innerHTML = '';
                return;
            }

            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    const suggestions = document.getElementById('suggestions');
                    suggestions.innerHTML = '';
                    data.slice(0, 5).forEach(item => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item';
                        li.textContent = item.display_name;
                        li.addEventListener('click', function() {
                            input.value = item.display_name;
                            suggestions.innerHTML = '';
                            updateUserMarker(L.latLng(item.lat, item.lon));
                            updateRoute(L.latLng(item.lat, item.lon));
                        });
                        suggestions.appendChild(li);
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        function searchAddress() {
            const address = document.getElementById('addressInput').value;
            if (!address) {
                alert('Silakan masukkan alamat');
                return;
            }

            showLoading();

            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        const location = L.latLng(data[0].lat, data[0].lon);
                        updateUserMarker(location);
                        updateRoute(location);
                    } else {
                        alert('Alamat tidak ditemukan');
                        hideLoading();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mencari alamat');
                    hideLoading();
                });
        }

        function updateUserMarker(latlng) {
            clearUserMarker();
            userMarker = L.marker(latlng, {
                    draggable: true
                }).addTo(map)
                .bindPopup("Lokasi Anda (Geser untuk mengubah)").openPopup();

            userMarker.on('dragend', function(event) {
                updateRoute(event.target.getLatLng());
            });

            map.setView(latlng, 13);
        }

        function updateRoute(userLocation) {
            showLoading();
            routeControl.setWaypoints([
                L.latLng(fixedPoint[0], fixedPoint[1]),
                L.latLng(userLocation.lat, userLocation.lng)
            ]);
        }

        function showLoading() {
            document.getElementById('loadingOverlay').style.display = 'block';
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        function updateTotal(shippingCost, distance) {
            const cartTotal = calculateCartTotal();
            const grandTotal = cartTotal + shippingCost;

            document.getElementById('cartSubtotal').textContent = formatCurrency(cartTotal);
            document.getElementById('shippingDistance').textContent = `${Math.ceil(distance)} km`;
            document.getElementById('shippingCost').textContent = formatCurrency(shippingCost);
            document.getElementById('grandTotal').textContent = formatCurrency(grandTotal);
        }

        function calculateCartTotal() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            return cart.reduce((total, item) => total + (item.harga * item.quantity), 0);
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(amount);
        }

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
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            cartItems.innerHTML = '';
            cart.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.nama}</td>
                    <td>${item.varian}</td>
                    <td class="harga">${formatCurrency(item.harga)}</td>
                    <td style="text-align: center; vertical-align: middle;">
                        <div class="input-group" style="justify-content: center; width: 150px; margin: 0 auto;">
                            <button class="btn btn-outline-secondary" onclick="updateQuantity('${item.id}', '${item.varian}', -1)">-</button>
                            <input type="number" class="form-control jumlah" value="${item.quantity}" min="1" readonly style="width: 50px; text-align: center;">
                            <button class="btn btn-outline-secondary" onclick="updateQuantity('${item.id}', '${item.varian}', 1)">+</button>
                        </div>
                    </td>
                    <td class="total">${formatCurrency(item.harga * item.quantity)}</td>
                    <td><button class="btn btn-sm btn-danger" onclick="removeItem('${item.id}', '${item.varian}')"><i class="fas fa-trash"></i></button></td>
                `;
                cartItems.appendChild(row);
            });

            if (routeControl && routeControl.getRouter()) {
                showLoading();
                routeControl.getRouter().route(routeControl.getWaypoints(), function(err, routes) {
                    if (!err && routes.length > 0) {
                        const distance = routes[0].summary.totalDistance / 1000;
                        const shippingCost = Math.ceil(distance) * SHIPPING_RATE;
                        updateTotal(shippingCost, distance);
                    }
                    hideLoading();
                });
            } else {
                updateTotal(0, 0);
            }
        }

        function updateCartBadge() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            const badge = document.getElementById('cartBadge');
            if (badge) {
                badge.textContent = totalItems;
                badge.style.display = totalItems > 0 ? 'inline-block' : 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            displayCart();
            updateCartBadge();

            routeControl.on('routesfound', function(e) {
                const routes = e.routes;
                const distance = routes[0].summary.totalDistance / 1000;
                const shippingCost = Math.ceil(distance) * SHIPPING_RATE;
                updateTotal(shippingCost, distance);
                hideLoading();
            });
        });
    </script>
@endsection
