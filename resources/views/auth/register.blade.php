@extends('layouts.auth')

@section('content')
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
                    <div class="card card-primary">
                        <div class="card-header text-center">
                            <h4>Register</h4>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('auth-register') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input id="username" type="text"
                                        class="form-control @error('username') is-invalid @enderror" name="username"
                                        value="{{ old('username') }}" required autofocus>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password" class="d-block">Password</label>
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required>
                                    <div id="password-strength" class="progress mt-2" style="height: 5px;">
                                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small id="passwordHelpBlock" class="form-text text-muted">
                                        Password harus memiliki minimal 8 karakter, mengandung huruf besar, huruf kecil,
                                        angka, dan karakter khusus (@$!%*?&).
                                    </small>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation" class="d-block">Konfirmasi Password</label>
                                    <input id="password_confirmation" type="password" class="form-control"
                                        name="password_confirmation" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone_number">Nomor Telepon</label>
                                    <input id="phone_number" type="tel"
                                        class="form-control @error('phone_number') is-invalid @enderror" name="phone_number"
                                        value="{{ old('phone_number') }}" placeholder="+628xxxxxxxxxx">
                                    <small class="form-text text-muted">
                                        Contoh format yang valid: +628123456789, 08123456789
                                    </small>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        Register
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="mb-4 text-muted text-center">
                            Sudah Terdaftar? <a href="{{ route('login') }}">Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if (session('alert'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const alert = @json(session('alert'));
                if (alert) {
                    Swal.fire({
                        icon: alert.type,
                        title: alert.type.charAt(0).toUpperCase() + alert.type.slice(1),
                        text: alert.message,
                        confirmButtonText: 'Okay'
                    });
                }
            });
        </script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const strengthBar = document.querySelector('#password-strength .progress-bar');

            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;

                if (password.match(/[a-z]+/)) strength += 1;
                if (password.match(/[A-Z]+/)) strength += 1;
                if (password.match(/[0-9]+/)) strength += 1;
                if (password.match(/[$@#&!]+/)) strength += 1;
                if (password.length >= 8) strength += 1;

                switch (strength) {
                    case 0:
                        strengthBar.style.width = '0%';
                        strengthBar.className = 'progress-bar bg-danger';
                        break;
                    case 1:
                        strengthBar.style.width = '20%';
                        strengthBar.className = 'progress-bar bg-danger';
                        break;
                    case 2:
                        strengthBar.style.width = '40%';
                        strengthBar.className = 'progress-bar bg-warning';
                        break;
                    case 3:
                        strengthBar.style.width = '60%';
                        strengthBar.className = 'progress-bar bg-info';
                        break;
                    case 4:
                        strengthBar.style.width = '80%';
                        strengthBar.className = 'progress-bar bg-success';
                        break;
                    case 5:
                        strengthBar.style.width = '100%';
                        strengthBar.className = 'progress-bar bg-success';
                        break;
                }
            });
        });
    </script>
@endsection
