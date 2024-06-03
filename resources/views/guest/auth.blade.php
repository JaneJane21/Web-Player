@extends('layout.app')
@section('title')
ВХОД
@endsection
@section('content')
<div class="container">
   @if(session()->has('error'))
        <div class="alert alert-light mt-5">
            {{ session('error') }}
        </div>
    @endif
</div>
<div class="container d-flex justify-content-center align-items-center">

    <div class="row auth-row w-100 justify-content-center align-items-center mt-5" style="margin-bottom: 150px;">
    <div class="col-7 blur d-flex flex-column justify-content-center align-items-center p-4">
        <img class="mb-5" width="150px" src="{{ asset('public\logo\big_logo_sign.svg') }}">
        <form method="post" action="{{ route('auth') }}">
            @csrf
            @method('post')
            <div class="mb-3">
                <label style="color: white;" for="phone">Телефон</label>
                <input style="background: transparent; color:white" type="text" id="phone" name="phone" class="form-control"
                @error('phone')
                    is_invalid
                @enderror>
                <div class="invalid-feedback d-block">
                    @error('phone')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="mb-5">
                <label style="color: white;" for="password">Пароль</label>
                <input style="background: transparent; color:white" type="password" id="password" name="password" class="form-control"@error('password')
                is_invalid
            @enderror>
            <div class="invalid-feedback d-block">
                @error('password')
                    {{ $message }}
                @enderror
            </div>
            </div>

            <p style="color: white;">Нет аккаунта? <a style="color: white;" href="{{ route('reg') }}">Зарегистрироваться</a></p>
            <button type="submit" class="btn bold" style="background: linear-gradient(90deg in oklab, #3F1573, #561567); color:white; width: 100%;">Вход</button>

        </form>


    </div>
</div>
</div>

<style>
    body{
        background-image: url('public\\bg\\auth_gradient.png');
        background-repeat: no-repeat;
        background-size: cover;
    }
    .blur{
        background-image: url('public\\bg\\blur_panel.png');
        background-repeat: no-repeat;
        background-size: cover;
        border-radius: 13px;
    }
    /* .auth-row{
        height: 80vh;
    } */
</style>
@endsection
