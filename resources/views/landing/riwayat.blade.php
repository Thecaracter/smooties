@extends('layouts.landing')

@section('content')
    <section id="riwayat" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center section-title mb-4">Riwayat Pembelian</h2>
            @if ($authenticated)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Pemesanan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pesanan as $p)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $p->kode_pemesanan }}</td>
                                    <td>{{ $p->created_at->format('d-m-Y H:i') }}</td>
                                    <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $p->status === 'selesai' ? 'success' : 'warning' }}">
                                            {{ ucfirst($p->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                            data-bs-target="#detailModal{{ $p->id }}">Detail</button>
                                        @if ($p->status === 'selesai')
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#commentModal{{ $p->id }}">Beri Penilaian</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $pesanan->links() }}

                @foreach ($pesanan as $p)
                    <!-- Modal for each order -->
                    <div class="modal fade" id="detailModal{{ $p->id }}" tabindex="-1"
                        aria-labelledby="detailModalLabel{{ $p->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detailModalLabel{{ $p->id }}">Detail Pesanan
                                        #{{ $p->kode_pemesanan }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Menu</th>
                                                <th>Jumlah</th>
                                                <th>Harga Satuan</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($p->detailPesanan as $detail)
                                                <tr>
                                                    <td>{{ $detail->jenisMenu->menu->nama }}</td>
                                                    <td>{{ $detail->jumlah }}</td>
                                                    <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                                    <td>Rp
                                                        {{ number_format($detail->jumlah * $detail->harga_satuan, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-end">Total:</th>
                                                <th>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comment Modal -->
                    @if ($p->status === 'selesai')
                        <div class="modal fade" id="commentModal{{ $p->id }}" tabindex="-1"
                            aria-labelledby="commentModalLabel{{ $p->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="commentModalLabel{{ $p->id }}">Beri Penilaian
                                            untuk Pesanan #{{ $p->kode_pemesanan }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('user.riwayat.comment', $p->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            @foreach ($p->detailPesanan as $detail)
                                                @php
                                                    $menu = $detail->jenisMenu->menu;
                                                    $existingComment = $p->komentar
                                                        ->where('user_id', auth()->id())
                                                        ->where('menu_id', $menu->id)
                                                        ->first();
                                                @endphp
                                                @if (!$existingComment)
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ $menu->nama }}</label>
                                                        <div class="star-rating" data-menu-id="{{ $menu->id }}">
                                                            <input type="hidden" name="rating[{{ $menu->id }}]"
                                                                value="0">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <i class="fas fa-star"
                                                                    data-rating="{{ $i }}"></i>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                            <input type="hidden" name="menu_id" id="selected_menu_id">
                                            <div class="mb-3">
                                                <label for="isi_komentar" class="form-label">Komentar</label>
                                                <textarea class="form-control" id="isi_komentar" name="isi_komentar" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-primary">Kirim Penilaian</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="text-center">
                    <p>Anda harus login untuk melihat riwayat pembelian.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                </div>
            @endif
        </div>
    </section>

    <style>
        .star-rating {
            font-size: 0;
        }

        .star-rating i {
            font-size: 1.2rem;
            color: #ddd;
            cursor: pointer;
        }

        .star-rating i.active {
            color: #ffd700;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const starContainers = document.querySelectorAll('.star-rating');
            starContainers.forEach(container => {
                const stars = container.querySelectorAll('i');
                const hiddenInput = container.querySelector('input[type="hidden"]');
                const menuId = container.dataset.menuId;

                stars.forEach(star => {
                    star.addEventListener('click', () => {
                        const rating = star.dataset.rating;
                        hiddenInput.value = rating;
                        document.getElementById('selected_menu_id').value = menuId;

                        stars.forEach((s, index) => {
                            if (index < rating) {
                                s.classList.add('active');
                            } else {
                                s.classList.remove('active');
                            }
                        });
                    });
                });
            });
        });
    </script>
@endsection
