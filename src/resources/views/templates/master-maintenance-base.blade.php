<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/css/master-maintenance.css', 'resources/js/app.js'])
    <title>@yield('title')</title>
</head>

<body class="px-10">
    @yield('header')
    @yield('content')
    @yield('footer')
    @livewireScripts
</body>

</html>
