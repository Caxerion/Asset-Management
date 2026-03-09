<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

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
                    <img src="{{ asset('images/RE.png') }}" height="50">

                    <h5 class="login-title">Create Account 📝</h5>
                    <p class="login-subtitle" style="text-align: left;">Register to access Stock Barang & Obat</p>
                </div>

                @if ($errors->any())
                    <div class="card-panel red lighten-4 red-text">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="card-panel green lighten-4 green-text">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register.process') }}">
                    @csrf

                    <div class="input-field">
                        <input type="text" name="name" value="{{ old('name') }}" required>
                        <label>Nama Lengkap</label>
                    </div>

                    <div class="input-field">
                        <input type="email" name="email" value="{{ old('email') }}" required>
                        <label>Email</label>
                    </div>

                    <div class="input-field">
                        <input type="password" name="password" required>
                        <label>Password</label>
                    </div>

                    <div class="input-field">
                        <input type="password" name="password_confirmation" required>
                        <label>Konfirmasi Password</label>
                    </div>

                    <div class="input-field">
                        <select name="role" required>
                            <option value="" disabled selected>Pilih Role</option>
                            <option value="admin">Administrator</option>
                            <option value="read">Read Only</option>
                        </select>
                        <label>Role</label>
                    </div>

                    <button class="btn waves-effect waves-light btn-login">
                        Register
                    </button>
                </form>

            </div>

            <div class="center" style="margin-top: 20px;">
                <p>Sudah punya akun? <a href="{{ route('login') }}">Login here</a></p>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('materialize/js/materialize.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('select');
        var instances = M.FormSelect.init(elems);
    });
</script>
</body>
</html>
