@extends('layout.app')
@section('title')
РЕГИСТРАЦИЯ
@endsection
@section('content')
<div class="container d-flex justify-content-center align-items-center ">
    <div class="row auth-row w-100 justify-content-center align-items-center mt-5 pb-5 mb-5">
    <div class="col-7 blur d-flex flex-column justify-content-center align-items-center p-4" style="margin-bottom: 100px;">
        <img class="mb-5" width="150px" src="{{ asset('public\logo\big_logo_sign.svg') }}">
        <form method="post" action="{{ route('reg_save') }}">
            @csrf
            @method('post')
            <div class="mb-3">
                <label style="color: white;" for="phone">Телефон</label>
                <input style="background: transparent; color:white" type="text" id="phone" name="phone" class="form-control"@error('phone')
                is_invalid
            @enderror>
                <div class="invalid-feedback d-block">
                    @error('phone')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="mb-3">
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
            <div class="mb-5">
                <label style="color: white;" for="password_confirmation">Подтвердите пароль</label>
                <input style="background: transparent; color:white" type="password" id="password_confirmation" name="password_confirmation" class="form-control"@error('password')
                is_invalid
            @enderror>
                <div class="invalid-feedback d-block">
                    @error('password')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <p style="color: white;">Уже есть аккаунт? <a style="color: white;" href="{{ route('login') }}">Войти</a></p>
            <button type="submit" class="btn bold" style="background: linear-gradient(90deg in oklab, #3F1573, #561567); color:white; width: 100%;">Регистрация</button>

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
