let map, userMarker, fixedMarker, routeControl;
const fixedPoint = [-6.39738522151828, 106.73303321534341];
const SHIPPING_RATE = 3000; // 3000 rupiah per km
let currentRoute = null; // Variabel untuk menyimpan rute terkini

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
        navigator.geolocation.getCurrentPosition(function (position) {
            const userLocation = L.latLng(position.coords.latitude, position.coords.longitude);
            updateUserMarker(userLocation);
            updateRoute(userLocation);
        }, function (error) {
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

document.getElementById('addressInput').addEventListener('input', function () {
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
                li.addEventListener('click', function () {
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

    userMarker.on('dragend', function (event) {
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
        routeControl.getRouter().route(routeControl.getWaypoints(), function (err, routes) {
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

document.addEventListener('DOMContentLoaded', function () {
    initMap();
    displayCart();
    updateCartBadge();

    routeControl.on('routesfound', function (e) {
        currentRoute = e.routes[0];
        const distance = currentRoute.summary.totalDistance / 1000;
        const shippingCost = Math.ceil(distance) * SHIPPING_RATE;
        updateTotal(shippingCost, distance);
        hideLoading();
    });

    document.getElementById('payButton').addEventListener('click', function () {
        processCheckout();
    });
});

function processCheckout() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    let distance = 0;
    let shippingCost = 0;

    if (currentRoute) {
        distance = currentRoute.summary.totalDistance / 1000; // convert to km
        shippingCost = Math.ceil(distance) * SHIPPING_RATE;
    }

    const totalHarga = calculateCartTotal() + shippingCost;

    if (!userMarker) {
        alert('Silakan pilih lokasi pengiriman terlebih dahulu.');
        return;
    }

    const userLocation = userMarker.getLatLng();

    const data = {
        cart: JSON.stringify(cart.map(item => ({
            id: item.id,
            quantity: item.quantity,
            harga: item.harga,
            varian: item.varian
        }))),
        total_harga: totalHarga,
        latitude: userLocation.lat,
        longitude: userLocation.lng,
        shipping_cost: shippingCost
    };

    showLoading();

    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found. Please ensure you have included the CSRF token in your HTML.');
        hideLoading();
        alert('Terjadi kesalahan. CSRF token tidak ditemukan.');
        return;
    }

    fetch('/checkout/process', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        },
        body: JSON.stringify(data)
    })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP error! status: ${response.status}, message: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            hideLoading();
            if (data.snap_token) {
                snap.pay(data.snap_token, {
                    onSuccess: function (result) {
                        handlePaymentResult(result, 'success');
                    },
                    onPending: function (result) {
                        handlePaymentResult(result, 'pending');
                    },
                    onError: function (result) {
                        handlePaymentResult(result, 'error');
                    },
                    onClose: function () {
                        alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                    }
                });
            } else {
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi. Detail: ' + error.message);
        });
}

function handlePaymentResult(result, status) {
    // Instead of sending to '/checkout/finish', we'll check the status directly
    checkOrderStatus(result.order_id, status);
}

function checkOrderStatus(orderId, paymentStatus) {
    fetch(`/checkout/check-status?order_id=${orderId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let message = `Status pesanan: ${data.order_status}. `;
                switch (paymentStatus) {
                    case 'success':
                        message += 'Pembayaran berhasil. ';
                        break;
                    case 'pending':
                        message += 'Pembayaran Anda sedang diproses. ';
                        break;
                    case 'error':
                        message += 'Terjadi kesalahan dalam proses pembayaran. ';
                        break;
                }
                message += data.message;
                alert(message);

                if (data.order_status === 'dibayar') {
                    localStorage.removeItem('cart');
                }
                window.location.href = '/riwayat';
            } else {
                alert('Gagal memeriksa status pesanan. Silakan cek halaman riwayat untuk informasi terbaru.');
                window.location.href = '/riwayat';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan jaringan. Silakan cek status pesanan Anda di halaman riwayat.');
            window.location.href = '/riwayat';
        });
}

// You may want to add a function to periodically check the order status
function startCheckingOrderStatus(orderId) {
    const checkInterval = setInterval(() => {
        checkOrderStatus(orderId);
    }, 5000); // Check every 5 seconds

    // Stop checking after 2 minutes (24 * 5 seconds)
    setTimeout(() => {
        clearInterval(checkInterval);
    }, 120000);
}

document.addEventListener('DOMContentLoaded', function () {
    const loginButton = document.getElementById('loginButton');
    const loginRoute = "/login";

    if (loginButton) {
        loginButton.addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Login Diperlukan',
                text: 'Anda perlu login untuk melanjutkan ke pembayaran. Apakah Anda ingin login sekarang?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Login Sekarang',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = loginRoute;
                }
            });
        });
    }

    const checkoutButton = document.getElementById('checkoutButton');
    if (checkoutButton) {
        checkoutButton.addEventListener('click', function () {
            processCheckout();
        });
    }
});