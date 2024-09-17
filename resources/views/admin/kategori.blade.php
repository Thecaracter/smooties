@extends('layouts.app')
@section('title', 'Kategori')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Kategori</h4>
                        </div>
                        <div class="container">
                            <br>
                            <div
                                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 mx-3">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#createKategoriModal">
                                    <i class="fa fa-plus"></i> Tambah Kategori
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="results" class="table-responsive">
                                <table class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-2" style="width: 70%;">Nama</th>
                                            <th class="px-4 py-2" style="width: 30%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($categories->isEmpty())
                                            <tr>
                                                <td colspan="2" class="text-center">Tidak ada kategori untuk ditampilkan
                                                </td>
                                            </tr>
                                        @else
                                            @foreach ($categories as $category)
                                                <tr>
                                                    <td>{{ $category->nama }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-warning"
                                                            data-toggle="modal"
                                                            data-target="#editKategoriModal{{ $category->id }}">
                                                            Edit
                                                        </button>
                                                        <button class="btn btn-sm btn-danger"
                                                            onclick="confirmDelete({{ $category->id }})">Hapus</button>
                                                        <form id="delete-form-{{ $category->id }}"
                                                            action="{{ route('kategori.destroy', $category->id) }}"
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

    <!-- Create Kategori Modal -->
    <div class="modal fade" id="createKategoriModal" tabindex="-1" role="dialog"
        aria-labelledby="createKategoriModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('kategori.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createKategoriModalLabel">Tambah Kategori Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
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

    @foreach ($categories as $category)
        <!-- Edit Kategori Modal -->
        <div class="modal fade" id="editKategoriModal{{ $category->id }}" tabindex="-1" role="dialog"
            aria-labelledby="editKategoriModalLabel{{ $category->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{ route('kategori.update', $category->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editKategoriModalLabel{{ $category->id }}">Edit Kategori</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nama{{ $category->id }}">Nama</label>
                                <input type="text" class="form-control" id="nama{{ $category->id }}" name="nama"
                                    value="{{ $category->nama }}" required>
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
@endsection
