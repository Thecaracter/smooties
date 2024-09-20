@extends('layouts.app')

@section('title', 'Admin Pesanan')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Daftar Pesanan</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Kode Pemesanan</th>
                                            <th>Total Harga</th>
                                            <th>Metode Pembayaran</th>
                                            <th>Status Pesanan</th>
                                            <th>Peta</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pesananTableBody">
                                        @forelse ($riwayats as $riwayat)
                                            <tr data-pesanan-id="{{ $riwayat->id }}">
                                                <td>{{ $riwayat->kode_pemesanan }}</td>
                                                <td>Rp {{ number_format($riwayat->total_harga, 0, ',', '.') }}</td>
                                                <td>{{ $riwayat->metode_pembayaran }}</td>
                                                <td class="status">{{ $riwayat->status }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary"
                                                        onclick="bukaPeta({{ $riwayat->latitude }}, {{ $riwayat->longitude }})">
                                                        Lihat Peta
                                                    </button>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" data-toggle="modal"
                                                        data-target="#detailModal{{ $riwayat->id }}">
                                                        Detail
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Tidak ada pesanan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($riwayats as $riwayat)
        <!-- Modal Detail Pesanan -->
        <div class="modal fade" id="detailModal{{ $riwayat->id }}" tabindex="-1" role="dialog"
            aria-labelledby="detailModalLabel{{ $riwayat->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel{{ $riwayat->id }}">Detail Pesanan:
                            {{ $riwayat->kode_pemesanan }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>Informasi Pesanan</h5>
                        <p><strong>Total Harga:</strong> Rp {{ number_format($riwayat->total_harga, 0, ',', '.') }}</p>
                        <p><strong>Metode Pembayaran:</strong> {{ $riwayat->metode_pembayaran }}</p>
                        <p><strong>Status:</strong> {{ $riwayat->status }}</p>

                        <div class="mt-4">
                            <button
                                class="btn btn-outline-primary rounded-pill text-left d-flex justify-content-between align-items-center px-3"
                                style="width: 20%;" onclick="toggleWaktuDetail({{ $riwayat->id }})">
                                <span>Waktu Pesanan</span>
                                <i id="timeIcon{{ $riwayat->id }}" class="fas fa-chevron-down ml-2"></i>
                            </button>
                        </div>
                        <div id="timeDetails{{ $riwayat->id }}" class="mt-3" style="display: none;">
                            <div class="card">
                                <div class="card-body">
                                    <p><strong>Waktu Diproses:</strong>
                                        {{ $riwayat->waktu_diproses ? $riwayat->waktu_diproses->format('d/m/Y H:i:s') : 'Belum diproses' }}
                                    </p>
                                    <p><strong>Waktu Dibayar:</strong>
                                        {{ $riwayat->waktu_dibayar ? $riwayat->waktu_dibayar->format('d/m/Y H:i:s') : 'Belum dibayar' }}
                                    </p>
                                    <p><strong>Waktu Diantar:</strong>
                                        {{ $riwayat->waktu_diantar ? $riwayat->waktu_diantar->format('d/m/Y H:i:s') : 'Belum diantar' }}
                                    </p>
                                    <p><strong>Waktu Selesai:</strong>
                                        {{ $riwayat->waktu_selesai ? $riwayat->waktu_selesai->format('d/m/Y H:i:s') : 'Belum selesai' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <h5 class="mt-4">Detail Item</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Jenis</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($riwayat->detailPesanan as $detail)
                                    <tr>
                                        <td>{{ $detail->jenisMenu->menu->nama }}</td>
                                        <td>{{ $detail->jenisMenu->jenis }}</td>
                                        <td>{{ $detail->jumlah }}</td>
                                        <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($detail->jumlah * $detail->harga, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <script>
        function bukaPeta(lat, long) {
            window.open(`https://www.google.com/maps?q=${lat},${long}`, '_blank');
        }

        function toggleWaktuDetail(id) {
            var details = document.getElementById('timeDetails' + id);
            var icon = document.getElementById('timeIcon' + id);
            if (details.style.display === "none") {
                details.style.display = "block";
                icon.className = "fas fa-chevron-up ml-2";
            } else {
                details.style.display = "none";
                icon.className = "fas fa-chevron-down ml-2";
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Anda akan memperbarui status pesanan ini.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, perbarui!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Pusher dan pembaruan real-time
            var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
                encrypted: true
            });

            var channel = pusher.subscribe('pesanan');
            channel.bind('pesanan-updated', function(data) {
                console.log('Received update:', data);
                updatePesananInTable(data.pesanan);
            });
        });
    </script>
@endsection
