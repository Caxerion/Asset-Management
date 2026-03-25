<!DOCTYPE html>
<html>
<head>
    <title>Lupa Password</title>

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
                    <img src="{{ asset('assets/img/logo-RE.png') }}" height="50">

                    <h5 class="login-title">Kanal Lupa Password</h5>
                    <p class="login-subtitle">Masukkan email Anda untuk reset password</p>
                </div>

                @if (session('success'))
                    <div class="card-panel green lighten-4 green-text">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('reset_link'))
                    <div class="card-panel blue lighten-4 blue-text text-darken-4" style="margin-top: 15px;">
                        <strong>🔗 Password Reset Link:</strong><br>
                        <a href="{{ session('reset_link') }}" target="_blank" style="word-break: break-all;">{{ session('reset_link') }}</a>
                        <br><br>
                        <small class="grey-text">Click the link to reset your password</small>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="card-panel red lighten-4 red-text">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.sendResetLink') }}">
                    @csrf

                    <div class="input-field">
                        <input type="email" name="email" value="{{ old('email') }}" required>
                        <label>Email</label>
                    </div>

                    <button class="btn waves-effect waves-light btn-login">
                        Kirim Link Reset Password
                    </button>
                </form>

                <div class="center" style="margin-top: 20px;">
                    <a href="{{ route('login') }}" class="grey-text text-darken-1">
                     Kembali ke Login
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="{{ asset('materialize/js/materialize.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Success message - Link reset password
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Link Reset Dibuat!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    @endif

    // Show reset link in alert
    @if(session('reset_link'))
        Swal.fire({
            icon: 'info',
            title: 'Password Reset Link',
            html: '<a href="{{ session('reset_link') }}" target="_blank" style="word-break: break-all;">{{ session('reset_link') }}</a>',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Buka Link'
        });
    @endif

    // Error message
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ $errors->first() }}',
            confirmButtonColor: '#d33',
            confirmButtonText: 'OK'
        });
    @endif
});
</script>
</body>
</html>
