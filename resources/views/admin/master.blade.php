<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Ventic : Ticketing Admin Template" />
    <meta property="og:title" content="Ventic : Ticketing Admin Template" />
    <meta property="og:description" content="Ventic : Ticketing Admin Template" />
    <meta property="og:image" content="" />
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <!-- PAGE TITLE HERE -->
    <title>DELIGHT</title>

    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('delight_logo.jpg') }}" />



    <!-- Style css -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <script>
        window.PUSHER_APP_KEY = '{{ config('broadcasting.connections.pusher.key') }}';
        window.APP_DEBUG = {{ config('app.debug') ? 'true' : 'false' }};
    </script>
    <script src="{{ asset('js/app3.js') }}"></script>
    <script src="{{ asset('js/app2.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
    <link href="{{ asset('vendor/lightgallery/css/lightgallery.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/locales-all.min.js"></script>
    <script src="{{ asset('js/calendario.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js"></script>
    {{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    @livewireStyles
    @livewireScripts
    @livewireChartsScripts
    @stack('css')

    <style>
        .swal2-popup {
            color: #fff !important;
            /* Fuerza el color del texto */
            background-color: #585858e3 !important;
            /* Fondo personalizado */
            border-radius: 10px;
            /* Bordes redondeados */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            /* Sombra */
        }

        .swal2-title,
        .swal2-content {
            color: #fff !important;
            /* Asegura el color blanco para el título y el contenido */
        }

        .bordeado {
            border-style: solid;
            border-color: #20c996b3;
            border-width: 1px;
            border-radius: 15px;
        }

        .bordeado-pulse {
            border-style: solid;
            border-color: #20c996b3;
            border-width: 1px;
            border-radius: 15px;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(32, 201, 150, 0.5);
            }

            70% {
                box-shadow: 0 0 10px 10px rgba(32, 201, 150, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(32, 201, 150, 0);
            }
        }

        .vertical-hr {
            width: 2px;
            /* Grosor de la línea */
            height: 100px;
            /* Altura de la línea */
            background-color: #20c996;
            /* Color de la línea */
            margin: 0 auto;
            /* Centrar si está dentro de un contenedor */
        }

        .letra12,
        .letra12 * {
            font-size: 12px !important;
        }

        .letra14,
        .letra14 * {
            font-size: 14px !important;
        }

        .letra10,
        .letra10 * {
            font-size: 10px !important;
        }

        .popover-container {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .popover-text {
            visibility: hidden;
            width: 200px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            padding: 10px;
            position: absolute;
            bottom: 125%;
            /* Ajusta según la posición deseada */
            left: 50%;
            transform: translateX(-50%);
            z-index: 1;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .popover-container:hover .popover-text {
            visibility: visible;
            opacity: 1;
        }
    </style>
</head>

<body version="dark">

    @hasSection('content')
        <div id="preloader">
            <div class="loader">
                <div class="loader--dot"></div>
                <div class="loader--dot"></div>
                <div class="loader--dot"></div>
                <div class="loader--dot"></div>
                <div class="loader--dot"></div>
                <div class="loader--dot"></div>
                <div class="loader--text"></div>
            </div>
        </div>
        <div id="main-wrapper">
            <!--**********************************
            Nav header start
        ***********************************-->
            <div class="nav-header">
                <a href="{{ route('ventas.listar') }}" class="brand-logo">
                    <!-- <img class="logo-abbr" src="./images/logo.png" alt="">
    <div class="brand-title">Ventic</div> -->
                    <img class="logo-abbr" width="54" viewBox="0 0 54 54" fill="none"
                        src="{{ asset('delight_logo.jpg') }}" />

                    <span class="brand-title" width="97" height="25" fill="none">Delight
                    </span>
                </a>
                <div class="nav-control">
                    <div class="hamburger">
                        <span class="line"></span><span class="line"></span><span class="line"></span>
                    </div>
                </div>
            </div>

            @include('admin.partials.header')

            @include('admin.partials.sidebar')

            <div class="content-body">
                <div class="container-fluid p-0">
                    @include('admin.partials.alertas')
                    @yield('content')
                </div>
                <!-- row -->


            </div>

        </div>
    @elseif('login')
        @yield('login')
    @endif


    @stack('footer')
    <!-- Required vendors -->
    <script src="{{ asset('vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('vendor/chart.js/Chart.bundle.min.js') }}"></script>



    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <!-- Chart piety plugin files -->
    <script src="{{ asset('vendor/peity/jquery.peity.min.js') }}"></script>

    <!-- Apex Chart -->


    <!-- Dashboard 1 -->
    <script src="{{ asset('js/dashboard/dashboard-1.js') }}"></script>

    <script src="{{ asset('js/custom.min.js') }}"></script>
    <script src="{{ asset('js/deznav-init.js') }}"></script>
    <script src="{{ asset('js/demo.js') }}"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top",
            showConfirmButton: false,
            showCloseButton: true,
            timer: 5000,
            background: "#585858e3",
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener("mouseenter", Swal.stopTimer);
                toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
        });

        window.addEventListener('alert', ({
            detail: {
                type,
                message
            }
        }) => {
            Toast.fire({
                icon: type,
                title: message
            })
        })

        window.addEventListener('copiarTexto', ({
            detail: {
                id,
                ag
            }
        }) => {
            var content = document.getElementById('copiar' + id);
            content.select();
            document.execCommand('copy');
        })

        window.addEventListener('cerrarModal', ({
            detail: {
                id
            }
        }) => {
            $('#' + id).modal('hide');
            console.log(id);
        })
    </script>

    @stack('scripts')
</body>

</html>
