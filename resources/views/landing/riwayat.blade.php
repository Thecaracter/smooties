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
                                <th>Tanggal</th>
                                <th>Nomor Pesanan</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pesanan as $p)
                                <tr>
                                    <td>{{ $p->created_at->format('d M Y') }}</td>
                                    <td>#{{ $p->kode_pemesanan }}</td>
                                    <td>{{ 'Rp ' . number_format($p->total_harga, 0, ',', '.') }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $p->status === 'selesai' ? 'success' : ($p->status === 'diantar' ? 'primary' : 'warning') }}">
                                            {{ ucfirst($p->status) }}
                                        </span>
                                    </td>
                                    <td><button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                            data-bs-target="#detailModal{{ $p->id }}">Detail</button></td>
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
                                    <h6>Tanggal Pesanan: {{ $p->created_at->format('d M Y H:i') }}</h6>
                                    <h6>Status: <span
                                            class="badge bg-{{ $p->status === 'selesai' ? 'success' : 'warning' }}">{{ ucfirst($p->status) }}</span>
                                    </h6>
                                    <table class="table table-bordered mt-3">
                                        <thead>
                                            <tr>
                                                <th>Menu</th>
                                                <th>Jenis</th>
                                                <th>Jumlah</th>
                                                <th>Harga</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($p->detailPesanan as $detail)
                                                <tr>
                                                    <td>{{ $detail->jenisMenu->menu->nama }}</td>
                                                    <td>{{ $detail->jenisMenu->nama }}</td>
                                                    <td>{{ $detail->jumlah }}</td>
                                                    <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-end">Total:</th>
                                                <th>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info text-center" role="alert">
                    <h4 class="alert-heading">Anda harus login terlebih dahulu</h4>
                    <p>Untuk melihat riwayat pembelian, silakan login ke akun Anda.</p>
                    <hr>
                    <p class="mb-0">
                        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-secondary">Daftar</a>
                    </p>
                </div>
            @endif
        </div>
    </section>
@endsection
