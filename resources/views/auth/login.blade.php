<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

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

                    <h5 class="login-title">Welcome Back! 👋</h5>
                    <p class="login-subtitle">Please sign-in to your account</p>
                </div>

                @if ($errors->any())
                    <div class="card-panel red lighten-4 red-text">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.process') }}">
                    @csrf

                    <div class="input-field">
                        <input type="email" name="email" value="{{ old('email') }}" required>
                        <label>Email</label>
                    </div>

                    <div class="input-field">
                        <input type="password" name="password" required>
                        <label>Password</label>
                    </div>

                    <p>
                        <label>
                            <input type="checkbox" name="remember" />
                            <span>Remember Me</span>
                        </label>

                        <a href="#" class="right">Forgot Password?</a>
                    </p>

                    <button class="btn waves-effect waves-light btn-login">
                        Login
                    </button>
                </form>

            </div>

            <div class="center grey-text text-darken-1" style="margin-top: 20px;">
                <p>Demo Credentials:</p>
                <p><strong>Admin:</strong> admin@gudang.test / adminBARANG!</p>
                <p><strong>Peminjam:</strong> peminjam@gudang.test / PeminjamBARANG!</p>
                <p><strong>Read Only:</strong> read@gudang.test / ReadBARANG!</p>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('materialize/js/materialize.min.js') }}"></script>
</body>
</html>
