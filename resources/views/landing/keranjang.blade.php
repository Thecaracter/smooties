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
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Berry Blast Smoothie</td>
                            <td>Rp 35.000</td>
                            <td>2</td>
                            <td>Rp 70.000</td>
                            <td><button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></td>
                        </tr>
                        <tr>
                            <td>Green Detox Smoothie</td>
                            <td>Rp 40.000</td>
                            <td>1</td>
                            <td>Rp 40.000</td>
                            <td><button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                            <td><strong>Rp 110.000</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="text-end mt-3">
                <a href="#" class="btn btn-primary">Lanjutkan ke Pembayaran</a>
            </div>
        </div>
    </section>
@endsection
