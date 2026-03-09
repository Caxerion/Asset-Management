@extends('layouts.app')
<style>
    body{
        
    }
    .hero{
        position: relative;
        top: 0;
        left:0;
        min-height: 100vh;
        width: 100%;
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
        background-image: linear-gradient(
            rgba(195, 194, 194, 0.4),
            rgba(245, 245, 245, 0.325)
        ),url(/assets/img/grahare.jpg);
    }
    .hero-content{
        display: flex;
        align-items: center;
        justify-content: space-evenly;
        width: 100%;
        min-height: 100%;
    }
    .hero-text{
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1rem;
        backdrop-filter: blur(3px);
    }
    .second-text p{
        font-size: 10px;
        text-align: center;
    }
</style>
@section('content')
<div class="hero">
    <div class="hero-content">
        <div class="hero-text">
            <h1>Selamat Datang di Dashboard</h1>
            <p class="second-text">Silakan pilih menu dari navigasi di atas.</p>
        </div>
    </div>
</div>
@endsection


