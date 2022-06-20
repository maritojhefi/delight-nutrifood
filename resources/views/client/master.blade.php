<!DOCTYPE HTML>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1, user-scalable=0 viewport-fit=cover" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>Delight</title>

    <link rel="stylesheet" type="text/css" href="{{asset('styles/bootstrap.css')}}">
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900|Roboto:300,300i,400,400i,500,500i,700,700i,900,900i&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('fonts/css/fontawesome-all.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('styles/highlights/highlight_mint.css')}}">
    @laravelPWA

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>

        @media only screen and (min-width: 1000px) {
          /* For large screens: */
          #margen {margin-right: 700px !important; margin-left: 700px}
        }
        
        </style>
        
@stack('header')
</head>

<body id="margen" class="theme-light">
    <div id="preloader">
        <div class="spinner-border color-highlight" role="status"></div>
    </div>
    <div id="page">

        <div class="header header-fixed header-auto-show header-logo-app">
            <a href="#" data-back-button class="header-title header-subtitle">Atras</a>
            <a href="#" data-back-button class="header-icon header-icon-1"><i class="fas fa-arrow-left"></i></a>
            
         
            <a href="#" data-menu="menu-main" class="header-icon header-icon-4 "><i class="fas fa-bars"></i></a>
        </div>
        @include('client.partials.footer-menu')

        <div class="page-content">
            @yield('content')
        </div>

        <div id="menu-share" class="menu menu-box-bottom menu-box-detached rounded-m" data-menu-height="420"
            data-menu-effect="menu-over">

        </div>
        <div id="menu-highlights" class="menu menu-box-bottom menu-box-detached rounded-m" data-menu-height="510"
            data-menu-effect="menu-over">
            @include('client.partials.menu-colors')
        </div>
        <div id="menu-main" class="menu menu-box-right menu-box-detached rounded-m" data-menu-width="260"
            data-menu-active="nav-pages" data-menu-effect="menu-over">
            @include('client.partials.menu-sidebar')
        </div>
    </div>
    @include('client.partials.modalredes')
    
    <script type="text/javascript" src="{{asset('scripts/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('scripts/custom.js')}}"></script>
    <script>
        function myFunction() {
            var element = document.body;
            element.classList.toggle("theme-dark");
            window.addEventListener('toast', ({
                detail: {
                    toastid
                }
            }) => {
                var toastID = document.getElementById('notification-1');
                toastID = new bootstrap.Toast(toastID);
                toastID.show();
            })
        }
    
    </script>
    @stack('scripts')

    <script>
        quarter()
                    function quarter() {
                       
          window.resizeTo(
            window.screen.availWidth / 2,
            window.screen.availHeight / 2
          );
          console.log('hola')
        }
        
                </script>
</body>
