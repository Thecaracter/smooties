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
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pesananTableBody">
                                        @forelse ($pesanans as $pesanan)
                                            <tr data-pesanan-id="{{ $pesanan->id }}">
                                                <td>{{ $pesanan->kode_pemesanan }}</td>
                                                <td>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                                                <td>{{ $pesanan->metode_pembayaran }}</td>
                                                <td class="status">{{ $pesanan->status }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary"
                                                        onclick="bukaPeta({{ $pesanan->latitude }}, {{ $pesanan->longitude }})">
                                                        Lihat Peta
                                                    </button>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" data-toggle="modal"
                                                        data-target="#detailModal{{ $pesanan->id }}">
                                                        Detail
                                                    </button>
                                                </td>
                                                <td class="action-cell">
                                                    @if ($pesanan->status != 'selesai')
                                                        <form
                                                            action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            @php
                                                                $nextStatus =
                                                                    $pesanan->status == 'dibayar'
                                                                        ? 'diantar'
                                                                        : 'selesai';
                                                                $buttonText =
                                                                    $pesanan->status == 'dibayar'
                                                                        ? 'Antar Pesanan'
                                                                        : 'Selesaikan Pesanan';
                                                                $buttonClass =
                                                                    $pesanan->status == 'dibayar'
                                                                        ? 'btn-primary'
                                                                        : 'btn-warning';
                                                            @endphp
                                                            <input type="hidden" name="status"
                                                                value="{{ $nextStatus }}">
                                                            <button type="submit" class="btn btn-sm {{ $buttonClass }}">
                                                                {{ $buttonText }}
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button class="btn btn-sm btn-success" disabled>Pesanan
                                                            Selesai</button>
                                                    @endif
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

    @foreach ($pesanans as $pesanan)
        <!-- Modal Detail Pesanan -->
        <div class="modal fade" id="detailModal{{ $pesanan->id }}" tabindex="-1" role="dialog"
            aria-labelledby="detailModalLabel{{ $pesanan->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel{{ $pesanan->id }}">Detail Pesanan:
                            {{ $pesanan->kode_pemesanan }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>Informasi Pesanan</h5>
                        <p><strong>Total Harga:</strong> Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                        <p><strong>Metode Pembayaran:</strong> {{ $pesanan->metode_pembayaran }}</p>
                        <p><strong>Status:</strong> {{ $pesanan->status }}</p>

                        <div class="mt-4">
                            <button
                                class="btn btn-outline-primary rounded-pill text-left d-flex justify-content-between align-items-center px-3"
                                style="width: 20%;" onclick="toggleWaktuDetail({{ $pesanan->id }})">
                                <span>Waktu Pesanan</span>
                                <i id="timeIcon{{ $pesanan->id }}" class="fas fa-chevron-down ml-2"></i>
                            </button>
                        </div>
                        <div id="timeDetails{{ $pesanan->id }}" class="mt-3" style="display: none;">
                            <div class="card">
                                <div class="card-body">
                                    <p><strong>Waktu Diproses:</strong>
                                        {{ $pesanan->waktu_diproses ? $pesanan->waktu_diproses->format('d/m/Y H:i:s') : 'Belum diproses' }}
                                    </p>
                                    <p><strong>Waktu Dibayar:</strong>
                                        {{ $pesanan->waktu_dibayar ? $pesanan->waktu_dibayar->format('d/m/Y H:i:s') : 'Belum dibayar' }}
                                    </p>
                                    <p><strong>Waktu Diantar:</strong>
                                        {{ $pesanan->waktu_diantar ? $pesanan->waktu_diantar->format('d/m/Y H:i:s') : 'Belum diantar' }}
                                    </p>
                                    <p><strong>Waktu Selesai:</strong>
                                        {{ $pesanan->waktu_selesai ? $pesanan->waktu_selesai->format('d/m/Y H:i:s') : 'Belum selesai' }}
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
                                @foreach ($pesanan->detailPesanan as $detail)
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
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
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

        function updatePesananInTable(pesanan) {
            console.log('Updating table with:', pesanan);
            var row = document.querySelector(`tr[data-pesanan-id="${pesanan.id}"]`);
            if (row) {
                if (pesanan.status === 'selesai') {
                    // Remove the row if the order is completed
                    row.remove();
                } else {
                    row.querySelector('.status').textContent = pesanan.status;

                    var actionCell = row.querySelector('.action-cell');
                    var nextStatus = pesanan.status === 'dibayar' ? 'diantar' : 'selesai';
                    var buttonText = pesanan.status === 'dibayar' ? 'Antar Pesanan' : 'Selesaikan Pesanan';
                    var buttonClass = pesanan.status === 'dibayar' ? 'btn-primary' : 'btn-warning';

                    actionCell.innerHTML = `
                        <form action="{{ route('admin.pesanan.updateStatus', '') }}/${pesanan.id}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="${nextStatus}">
                            <button type="submit" class="btn btn-sm ${buttonClass}">
                                ${buttonText}
                            </button>
                        </form>
                    `;
                }

                // Update waktu pesanan jika ada
                if (pesanan.waktu_diproses) {
                    document.getElementById(`waktu_diproses_${pesanan.id}`).textContent = new Date(pesanan.waktu_diproses)
                        .toLocaleString();
                }
                if (pesanan.waktu_dibayar) {
                    document.getElementById(`waktu_dibayar_${pesanan.id}`).textContent = new Date(pesanan.waktu_dibayar)
                        .toLocaleString();
                }
                if (pesanan.waktu_diantar) {
                    document.getElementById(`waktu_diantar_${pesanan.id}`).textContent = new Date(pesanan.waktu_diantar)
                        .toLocaleString();
                }
                if (pesanan.waktu_selesai) {
                    document.getElementById(`waktu_selesai_${pesanan.id}`).textContent = new Date(pesanan.waktu_selesai)
                        .toLocaleString();
                }

                // Tambahkan SweetAlert untuk memberi tahu pengguna tentang pembaruan
                Swal.fire({
                    title: pesanan.status === 'selesai' ? 'Pesanan Selesai' : 'Pesanan Diperbarui',
                    text: pesanan.status === 'selesai' ?
                        `Pesanan ${pesanan.kode_pemesanan} telah selesai dan dihapus dari daftar` :
                        `Status pesanan ${pesanan.kode_pemesanan} telah diperbarui menjadi ${pesanan.status}`,
                    icon: 'info',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        }
    </script>
@endsection
