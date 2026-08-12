<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>Login - Sistem Informasi BPJN</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Vite Assets -->
        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/LoginPage.jsx'])
    </head>
    <body class="antialiased">
        <div 
            id="react-login-root"
            data-csrf="{{ csrf_token() }}"
            data-old-email="{{ old('email') }}"
            data-status="{{ session('status') }}"
            data-errors="{{ json_encode($errors->toArray()) }}"
        ></div>
    </body>
</html>
