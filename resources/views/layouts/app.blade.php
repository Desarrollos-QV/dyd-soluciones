<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name', 'DYD SOLUCIONES') }}</title>

    <!-- core:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/core/core.css') }}">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/sweetalert2/sweetalert2.min.css') }}">
    <!-- plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <!-- end plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/flag-icon-css/css/flag-icon.min.css') }}"> 
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    @yield('css')


    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
</head>

<body class="sidebar-dark">
    <div class="main-wrapper">
        @if (!Route::is('login') && 
            !Route::is('register') && 
            !Route::is('password.request') && 
            !Route::is('password.reset') &&
            !Route::is('servicios_agendados.firmar'))
            <!-- _sidebar -->
            @include('includes.sidebar')
            <!-- _sidebar -->
        @endif

        <div class="page-wrapper @if (Route::is('login') || Route::is('register') || Route::is('password.request') || Route::is('password.reset') || Route::is('servicios_agendados.firmar')) full-page @endif">

            @if (!Route::is('login') && !Route::is('register') && !Route::is('password.request') && !Route::is('password.reset') && !Route::is('servicios_agendados.firmar'))
                <!-- _navbar -->
                @include('includes.navbar')
                <!-- _navbar -->
            @endif

            <div class="page-content">
                <main class="py-4">
                    @if (session('success') || request()->has('success'))
                        <div class="alert alert-success">{{ (session('success')) ? session('success') : request('success') }}</div>
                    @endif
                    @if (session('error') || request()->has('error'))
                        <div class="alert alert-danger">{{ (session('error')) ? session('error') : request('error') }}</div>
                    @endif
 
                    @yield('content')
                </main>
            </div>

            <footer class="footer d-flex flex-column flex-md-row align-items-center justify-content-between">
                <p class="text-muted text-center text-md-left">Copyright © 2025 DYD Soluciones. Todos los derechos
                    reservados.</p>
                <p class="text-muted text-center text-md-left mb-0 d-none d-md-block">
                    Desarrollado por <a href="https://desarrollosqv.com" target="_blank">DesarrollosQV</a> <i
                        class="mb-1 text-primary ml-1 icon-small" data-feather="heart"></i></p>
            </footer>
        </div>
    </div>



    <!-- core:js -->
    <script src="{{ asset('assets/vendors/core/core.js') }}"></script>
    <!-- endinject -->
    <!-- plugin js for this page -->
    <script src="{{ asset('assets/vendors/chartjs/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery.flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/progressbar.js/progressbar.min.js') }}"></script>
    <!-- end plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('assets/vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/vendors/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/promise-polyfill/polyfill.min.js') }}"></script> 
    <!-- endinject -->
    <!-- custom js for this page -->
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <!-- end custom js for this page -->

    @yield('js')
</body>

</html>
