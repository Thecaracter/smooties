@extends('layouts.app')
@section('title', 'User')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">User</h4>
                        </div>
                        <div class="container">
                        </div>
                        <div class="card-body">
                            <div id="results" class="table-responsive">
                                <table class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-2">Username</th>
                                            <th class="px-4 py-2">Email</th>
                                            <th class="px-4 py-2">No Telpon</th>
                                            <th class="px-4 py-2">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($users->isEmpty())
                                            <tr>
                                                <td colspan="2" class="text-center">Tidak ada kategori untuk ditampilkan
                                                </td>
                                            </tr>
                                        @else
                                            @foreach ($users as $user)
                                                <tr>
                                                    <td>{{ $user->username }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->phone_number }}</td>
                                                    <td>

                                                        <button class="btn btn-sm btn-danger"
                                                            onclick="confirmDelete({{ $user->id }})">Hapus</button>
                                                        <form id="delete-form-{{ $user->id }}"
                                                            action="{{ route('user.destroy', $user->id) }}" method="POST"
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
