@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Dashboard</h3>
                    <h6 class="op-7 mb-2">Penjualan Sarang Walet</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">User</p>
                                        <h4 class="card-title">{{ $countUser }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-info bubble-shadow-small">
                                        <i class="fas fa-user-check"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Menu</p>
                                        <h4 class="card-title">{{ $countMenu }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-success bubble-shadow-small">
                                        <i class="fas fa-luggage-cart"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Transaksi</p>
                                        <h4 class="card-title">{{ $countOngoingOrders }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                        <i class="far fa-check-circle"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Transaksi Selesai</p>
                                        <h4 class="card-title">{{ $countCompletedOrders }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap" style="gap: 20px; justify-content: center;">
                                <select id="statisticType" class="form-select w-auto"
                                    style="padding: 10px 35px 10px 20px; border-radius: 10px; border: 1px solid #ced4da; background-color: #f8f9fa; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); font-size: 16px; margin-right: 10px; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007CB2%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 15px top 50%; background-size: 12px auto;">
                                    <option value="monthly">Statistik Bulanan</option>
                                    <option value="yearly">Statistik Tahunan</option>
                                </select>
                                <select id="yearSelect" class="form-select w-auto"
                                    style="padding: 10px 35px 10px 20px; border-radius: 10px; border: 1px solid #ced4da; background-color: #f8f9fa; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); font-size: 16px; margin-right: 10px; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007CB2%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 15px top 50%; background-size: 12px auto;">
                                    <!-- Years will be populated dynamically -->
                                </select>
                                <select id="monthSelect" class="form-select w-auto"
                                    style="padding: 10px 35px 10px 20px; border-radius: 10px; border: 1px solid #ced4da; background-color: #f8f9fa; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); font-size: 16px; margin-right: 10px; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007CB2%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 15px top 50%; background-size: 12px auto;">
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                                <button id="loadStatisticsBtn" class="btn btn-primary"
                                    style="padding: 10px 24px; border-radius: 10px; background-color: #007bff; border: none; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); font-size: 16px;">
                                    Muat Statistik
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">Pendapatan Statistics</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="min-height: 375px">
                                <canvas id="salesChart"></canvas>
                            </div>
                            <div id="orderSuccessChartLegend"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-primary card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">Pendapatan <span id="periodLabel">Bulanan</span></div>
                            </div>
                            <div class="card-category" id="currentPeriod"></div>
                        </div>
                        <div class="card-body pb-0">
                            <div class="mb-4 mt-2">
                                <h1 id="revenue"></h1>
                            </div>
                        </div>
                    </div>

                    <div class="card card-round">
                        <div class="card-body pb-0">
                            <div class="h1 fw-bold float-end text-primary" id="percentageChange"></div>
                            <h2 class="mb-2" id="comparisonRevenue"></h2>
                            <p class="text-muted">Perbandingan Pendapatan dengan periode sebelumnya</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row card-tools-still-right">
                                <div class="card-title">Product Terlaris</div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">Product Photo</th>
                                            <th scope="col">Product Name</th>
                                            <th scope="col" class="text-end">Quantity Sold</th>
                                        </tr>
                                    </thead>
                                    <tbody id="topSellingProductsTable">
                                        <!-- Top selling products will be populated here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let salesChart;

        document.addEventListener('DOMContentLoaded', function() {
            populateYearSelect();
            setCurrentMonthAndYear();
            toggleMonthSelect();
            document.getElementById('statisticType').addEventListener('change', toggleMonthSelect);
            document.getElementById('loadStatisticsBtn').addEventListener('click', loadSelectedStatistics);

            loadSelectedStatistics();
        });

        function formatToRupiah(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }

        function toggleMonthSelect() {
            const isMonthly = document.getElementById('statisticType').value === 'monthly';
            document.getElementById('monthSelect').style.display = isMonthly ? 'inline-block' : 'none';
        }

        function populateYearSelect() {
            const yearSelect = document.getElementById('yearSelect');
            const currentYear = new Date().getFullYear();
            for (let year = currentYear; year >= currentYear - 10; year--) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                yearSelect.appendChild(option);
            }
        }

        function setCurrentMonthAndYear() {
            const now = new Date();
            document.getElementById('yearSelect').value = now.getFullYear();
            document.getElementById('monthSelect').value = now.getMonth() + 1;
        }

        function loadSelectedStatistics() {
            const type = document.getElementById('statisticType').value;
            const year = document.getElementById('yearSelect').value;
            const month = document.getElementById('monthSelect').value;

            let url = `/admin/dashboard/${type}-statistics?year=${year}`;
            if (type === 'monthly') {
                url += `&month=${month}`;
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    updateRevenueDisplay(data.revenue, data.percentageChange, type);
                    updateSalesChart(data.salesData, type);
                    updateTopSellingProducts(data.topSellingProducts);
                })
                .catch(error => console.error('Error loading statistics:', error));
        }

        function updateRevenueDisplay(revenue, percentageChange, type) {

            document.getElementById('revenue').textContent = formatToRupiah(revenue);
            document.getElementById('comparisonRevenue').textContent = formatToRupiah(revenue);
            document.getElementById('percentageChange').textContent =
                `${percentageChange > 0 ? '+' : ''}${percentageChange.toFixed(2)}%`;
            document.getElementById('periodLabel').textContent = type === 'monthly' ? 'Bulanan' : 'Tahunan';
            document.getElementById('currentPeriod').textContent = getCurrentPeriod();
        }

        function getCurrentPeriod() {
            const type = document.getElementById('statisticType').value;
            const year = document.getElementById('yearSelect').value;
            if (type === 'monthly') {
                const month = document.getElementById('monthSelect').value;
                const date = new Date(year, month - 1);
                return date.toLocaleString('id-ID', {
                    month: 'long',
                    year: 'numeric'
                });
            } else {
                return year;
            }
        }

        function updateSalesChart(salesData, type) {
            const ctx = document.getElementById('salesChart').getContext('2d');

            if (salesChart) {
                salesChart.destroy();
            }

            // Create gradient
            let gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(33, 150, 243, 0.1)'); // Biru muda transparan di atas
            gradient.addColorStop(1, 'rgba(33, 150, 243, 0.6)'); // Biru lebih pekat di bawah

            salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: salesData.labels,
                    datasets: [{
                        label: 'Penjualan',
                        data: salesData.data,
                        borderColor: 'rgba(33, 150, 243, 1)', // Biru solid untuk garis
                        backgroundColor: gradient, // Menggunakan gradient untuk fill
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: type === 'monthly' ? 'Tanggal' : 'Bulan',
                                color: 'rgba(0, 0, 0, 0.7)'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Penjualan (Rp)',
                                color: 'rgba(0, 0, 0, 0.7)'
                            },
                            ticks: {
                                callback: function(value, index, values) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                },
                                color: 'rgba(0, 0, 0, 0.7)'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.8)',
                            titleColor: 'rgba(0, 0, 0, 0.7)',
                            bodyColor: 'rgba(0, 0, 0, 0.7)',
                            borderColor: 'rgba(33, 150, 243, 0.5)',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }

        function updateTopSellingProducts(products) {
            const tableBody = document.getElementById('topSellingProductsTable');
            tableBody.innerHTML = '';

            products.forEach(product => {
                const row = document.createElement('tr');
                row.innerHTML = `
                <td><img src="/fotoMenu/${product.foto}" alt="${product.nama}" width="50"></td>
                <td>${product.nama}</td>
                <td class="text-end">${product.total_sold}</td>
            `;
                tableBody.appendChild(row);
            });
        }
    </script>

    @if (session('alert'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const alert = @json(session('alert'));
                if (alert) {
                    Swal.fire({
                        icon: alert.type,
                        title: alert.type.charAt(0).toUpperCase() + alert.type.slice(1),
                        text: alert.message,
                        confirmButtonText: 'Okay'
                    });
                }
            });
        </script>
    @endif
@endsection
