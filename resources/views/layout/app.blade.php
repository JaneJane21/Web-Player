<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('public\css\bootstrap.css') }}">
    <link rel="icon" href="{{ asset('public\logo\logo_sign.svg') }}" type="image/x-icon">
</head>
<body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/3.3.5/vue.global.js"></script>
    <script src="{{ asset('public\js\bootstrap.bundle.js') }}"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&display=swap');
        p,h1,h2,h3,h4,h5,h6,div,a,input,label,button,span,option,select{
            font-family: "Exo 2", sans-serif;
            font-optical-sizing: auto;
            /* font-weight: <weight>; */
            font-style: normal;
        }
        .bold{
            font-weight: 700;
        }
        .extra-bold{
            font-weight: 800;
        }
        /* a{
            text-decoration: none
        } */
    </style>
    @include('layout.navbar')
    @yield('content')
    @include('layout.musicbar')
</body>
</html>
