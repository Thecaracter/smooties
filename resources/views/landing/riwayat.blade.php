@extends('layouts.landing')
@section('content')
    <section id="riwayat" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center section-title">Riwayat Pembelian</h2>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nomor Pesanan</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>15 Sep 2024</td>
                            <td>#12345</td>
                            <td>Rp 105.000</td>
                            <td><span class="badge bg-success">Selesai</span></td>
                        </tr>
                        <tr>
                            <td>10 Sep 2024</td>
                            <td>#12344</td>
                            <td>Rp 75.000</td>
                            <td><span class="badge bg-success">Selesai</span></td>
                        </tr>
                        <tr>
                            <td>5 Sep 2024</td>
                            <td>#12343</td>
                            <td>Rp 140.000</td>
                            <td><span class="badge bg-success">Selesai</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
