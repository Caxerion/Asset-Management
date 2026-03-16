<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>

    <!-- Materialize -->
    <link rel="stylesheet" href="{{ asset('materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- Custom Auth CSS -->
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col s12 m6 offset-m3">
            <div class="card login-box">

                <div class="center">
                    <img src="{{ asset('public/assets/img/RE.png') }}" height="50">

                    <h5 class="login-title">Reset Password 🔐</h5>
                    <p class="login-subtitle">Masukkan password baru Anda</p>
                </div>

                @if ($errors->any())
                    <div class="card-panel red lighten-4 red-text">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="input-field">
                        <input type="email" name="email" value="{{ $email }}" disabled>
                        <label>Email</label>
                    </div>

                    <div class="input-field">
                        <input type="password" name="password" required>
                        <label>Password Baru</label>
                    </div>

                    <div class="input-field">
                        <input type="password" name="password_confirmation" required>
                        <label>Konfirmasi Password</label>
                    </div>

                    <button class="btn waves-effect waves-light btn-login">
                        Reset Password
                    </button>
                </form>

                <div class="center" style="margin-top: 20px;">
                    <a href="{{ route('login') }}" class="grey-text text-darken-1">
                        <i class="material-icons">arrow_back</i> Kembali ke Login
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="{{ asset('materialize/js/materialize.min.js') }}"></script>
</body>
</html>
