@extends('layouts.app')
@section('title', 'Menu')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Menu</h4>
                        </div>
                        <div class="container">
                            <br>
                            <div
                                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 mx-3">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#createMenuModal">
                                    <i class="fa fa-plus"></i> Add Menu
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="results" class="table-responsive">
                                <table class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Photo</th>
                                            <th>Is Active</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($menus->isEmpty())
                                            <tr>
                                                <td colspan="5" class="text-center">No menus to display</td>
                                            </tr>
                                        @else
                                            @foreach ($menus as $menu)
                                                <tr>
                                                    <td>{{ $menu->nama }}</td>
                                                    <td>{{ $menu->kategori->nama ?? 'N/A' }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-secondary btn-sm"
                                                            data-toggle="modal"
                                                            data-target="#imageModal-{{ $menu->id }}">
                                                            View Image
                                                        </button>
                                                    </td>
                                                    <td>{{ $menu->aktif ? 'Active' : 'Inactive' }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-warning"
                                                            data-toggle="modal"
                                                            data-target="#editMenuModal{{ $menu->id }}">
                                                            Edit
                                                        </button>
                                                        <button class="btn btn-sm btn-danger"
                                                            onclick="confirmDelete({{ $menu->id }})">Delete</button>
                                                        <form id="delete-form-{{ $menu->id }}"
                                                            action="{{ route('menu.destroy', $menu->id) }}" method="POST"
                                                            style="display: none;">
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

    <!-- Create Menu Modal -->
    <div class="modal fade" id="createMenuModal" tabindex="-1" role="dialog" aria-labelledby="createMenuModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('menu.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createMenuModalLabel">Add New Menu</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="kategori_id">Category</label>
                            <select class="form-control" id="kategori_id" name="kategori_id" required>
                                <option value="">Select a category</option> <!-- Placeholder option -->
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nama">Name</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <input type="text" class="form-control" id="deskripsi" name="deskripsi" required>
                        </div>
                        <div class="form-group">
                            <label for="foto">Photo</label>
                            <input type="file" class="form-control" id="foto" name="foto" required>
                        </div>
                        <div class="form-group">
                            <label for="aktif">Active</label>
                            <select class="form-control" id="aktif" name="aktif">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($menus as $menu)
        <!-- Edit Menu Modal -->
        <div class="modal fade" id="editMenuModal{{ $menu->id }}" tabindex="-1" role="dialog"
            aria-labelledby="editMenuModalLabel{{ $menu->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{ route('menu.update', $menu->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editMenuModalLabel{{ $menu->id }}">Edit Menu</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="kategori_id{{ $menu->id }}">Category</label>
                                <select class="form-control" id="kategori_id{{ $menu->id }}" name="kategori_id"
                                    required>
                                    <option value="">Select a category</option> <!-- Placeholder option -->
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $menu->kategori_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="nama{{ $menu->id }}">Name</label>
                                <input type="text" class="form-control" id="nama{{ $menu->id }}" name="nama"
                                    value="{{ $menu->nama }}" required>
                            </div>
                            <div class="form-group">
                                <label for="deskripsi{{ $menu->id }}">Deskripsi</label>
                                <input type="text" class="form-control" id="deskripsi{{ $menu->id }}"
                                    name="deskripsi" value="{{ $menu->deskripsi }}" required>
                            </div>
                            <div class="form-group">
                                <label for="foto{{ $menu->id }}">Photo</label>
                                <input type="file" class="form-control" id="foto{{ $menu->id }}" name="foto">
                                <small class="form-text text-muted">Leave empty to keep the current image</small>
                            </div>
                            <div class="form-group">
                                <label for="aktif{{ $menu->id }}">Active</label>
                                <select class="form-control" id="aktif{{ $menu->id }}" name="aktif">
                                    <option value="1" {{ $menu->aktif ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$menu->aktif ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Image Modal -->
        <div class="modal fade" id="imageModal-{{ $menu->id }}" tabindex="-1" role="dialog"
            aria-labelledby="imageModalLabel-{{ $menu->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #1A2035; color: white;">
                        <h5 class="modal-title" id="imageModalLabel-{{ $menu->id }}">{{ $menu->nama }}</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="{{ asset('fotoMenu/' . $menu->foto) }}" alt="{{ $menu->nama }}"
                                    class="img-fluid rounded shadow"
                                    style="max-height: 300px; width: 100%; object-fit: cover;">
                            </div>
                            <div class="col-md-6">
                                @if ($menu->deskripsi)
                                    <h6 class="font-weight-bold mb-3">Deskripsi:</h6>
                                    <p class="text-muted">{{ $menu->deskripsi }}</p>
                                @endif
                                <h6 class="font-weight-bold mt-4 mb-3">Jenis Menu:</h6>
                                <div class="d-flex flex-wrap">
                                    @foreach ($menu->jenisMenu as $jenis)
                                        <div class="bg-light rounded-pill px-3 py-2 m-1 text-center">
                                            <span class="font-weight-bold">{{ $jenis->jenis }}</span><br>
                                            <span class="text-primary">Rp
                                                {{ number_format($jenis->harga, 0, ',', '.') }}</span><br>
                                            <small class="text-muted">Stok: {{ $jenis->stok }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded-pill" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this menu?')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endsection
