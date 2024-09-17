@extends('layouts.app')
@section('title', 'Jenis Menu')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Jenis Menu</h4>
                        </div>
                        <div class="container">
                            <br>
                            <div
                                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 mx-3">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#createJenisMenuModal">
                                    <i class="fa fa-plus"></i> Tambah Jenis Menu
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="results" class="table-responsive">
                                <table class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-2">Jenis</th>
                                            <th class="px-4 py-2">Harga</th>
                                            <th class="px-4 py-2">Stok</th>
                                            <th class="px-4 py-2">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($jenisMenu->isEmpty())
                                            <tr>
                                                <td colspan="4" class="text-center">Tidak ada jenis menu untuk
                                                    ditampilkan</td>
                                            </tr>
                                        @else
                                            @foreach ($jenisMenu as $jenis)
                                                <tr>
                                                    <td>{{ $jenis->jenis }}</td>
                                                    <td>Rp {{ number_format($jenis->harga, 0, ',', '.') }}</td>
                                                    <td>{{ $jenis->stok }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-warning"
                                                            data-toggle="modal"
                                                            data-target="#editJenisMenuModal{{ $jenis->id }}">
                                                            Edit
                                                        </button>
                                                        <button class="btn btn-sm btn-danger"
                                                            onclick="confirmDelete({{ $jenis->id }})">Hapus</button>
                                                        <form id="delete-form-{{ $jenis->id }}"
                                                            action="{{ route('admin.jenis-menu.destroy', $jenis->id) }}"
                                                            method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Jenis Menu Modal -->
    <div class="modal fade" id="createJenisMenuModal" tabindex="-1" role="dialog"
        aria-labelledby="createJenisMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.jenis-menu.store', $menu_id) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createJenisMenuModalLabel">Tambah Jenis Menu Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="jenis">Jenis</label>
                            <input type="text" class="form-control" id="jenis" name="jenis" required>
                        </div>
                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga" required
                                min="0">
                        </div>
                        <div class="form-group">
                            <label for="stok">Stok</label>
                            <input type="number" class="form-control" id="stok" name="stok" required
                                min="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($jenisMenu as $jenis)
        <!-- Edit Jenis Menu Modal -->
        <div class="modal fade" id="editJenisMenuModal{{ $jenis->id }}" tabindex="-1" role="dialog"
            aria-labelledby="editJenisMenuModalLabel{{ $jenis->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.jenis-menu.update', $jenis->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editJenisMenuModalLabel{{ $jenis->id }}">Edit Jenis Menu</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="jenis{{ $jenis->id }}">Jenis</label>
                                <input type="text" class="form-control" id="jenis{{ $jenis->id }}" name="jenis"
                                    value="{{ $jenis->jenis }}" required>
                            </div>
                            <div class="form-group">
                                <label for="harga{{ $jenis->id }}">Harga</label>
                                <input type="text" class="form-control" id="harga{{ $jenis->id }}" name="harga"
                                    value="{{ number_format($jenis->harga, 0, ',', '.') }}" required min="0">
                            </div>
                            <div class="form-group">
                                <label for="stok{{ $jenis->id }}">Stok</label>
                                <input type="number" class="form-control" id="stok{{ $jenis->id }}" name="stok"
                                    value="{{ $jenis->stok }}" required min="0">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
    <script>
        // Ambil semua input yang id-nya dimulai dengan 'harga'
        const hargaInputs = document.querySelectorAll('input[id^="harga"]');

        // Loop untuk menerapkan event listener pada setiap input harga
        hargaInputs.forEach(function(hargaInput) {
            hargaInput.addEventListener('input', function() {
                // Ambil nilai input, hapus format pemisah ribuan jika ada
                let value = this.value.replace(/\./g, '');

                // Pastikan nilai hanya angka
                if (!isNaN(value) && value.length > 0) {
                    // Format angka menjadi ribuan dengan titik
                    this.value = new Intl.NumberFormat('id-ID').format(value);
                } else {
                    this.value = '';
                }
            });

            // Tambahkan event listener pada form submit untuk setiap input
            hargaInput.closest('form').addEventListener('submit', function() {
                // Hapus pemisah ribuan sebelum submit
                hargaInput.value = hargaInput.value.replace(/\./g, '');
            });
        });
    </script>
@endsection
