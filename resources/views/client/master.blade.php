<!DOCTYPE HTML>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1, user-scalable=0 viewport-fit=cover" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>Delight</title>

    <link rel="stylesheet" type="text/css" href="{{ asset('styles/bootstrap.css') }}">
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900|Roboto:300,300i,400,400i,500,500i,700,700i,900,900i&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/highlights/highlight_mint.css') }}">
    <script>
        var blurred = false;
        window.onblur = function() {
            blurred = true;
        };
        window.onfocus = function() {
            blurred && (location.reload());
        };
    </script>
    <style>
        .fa-beat {
            animation: fa-beat 5s ease infinite;
        }

        @keyframes fa-beat {
            0% {
                transform: scale(1);
            }

            5% {
                transform: scale(1.25);
            }

            20% {
                transform: scale(1);
            }

            30% {
                transform: scale(1);
            }

            35% {
                transform: scale(1.25);
            }

            50% {
                transform: scale(1);
            }

            55% {
                transform: scale(1.25);
            }

            70% {
                transform: scale(1);
            }
        }
    </style>
    @stack('header')

    @laravelPWA

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        /* Extra small devices (phones, 600px and down) */
        @media only screen and (max-width: 600px) {}

        /* Small devices (portrait tablets and large phones, 600px and up) */
        @media only screen and (min-width: 600px) {}

        /* Medium devices (landscape tablets, 768px and up) */
        @media only screen and (min-width: 768px) {
            .margen {
                margin-right: 20%;
                margin-left: 20%;
            }
        }

        /* Large devices (laptops/desktops, 992px and up) */
        @media only screen and (min-width: 992px) {
            .margen {
                margin-right: 30%;
                margin-left: 30%;
            }
        }

        /* Extra large devices (large laptops and desktops, 1200px and up) */
        @media only screen and (min-width: 1200px) {
            .margen {
                margin-right: 35%;
                margin-left: 35%
            }
        }
    </style>


</head>

<body id="margen"
    class="{{ isset(auth()->user()->color_page) ? auth()->user()->color_page : 'theme-light' }} margen">
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
    <div id="toast-loading" class="toast toast-tiny toast-top bg-blue-dark fade hide" data-bs-delay="1500"
        data-bs-autohide="true"><i class="fa fa-sync fa-spin me-3"></i>Actualizado!</div>

    <div id="toast-carrito" class="toast toast-tiny toast-top bg-green-dark hide" data-bs-delay="1000"
        data-bs-autohide="true"><i class="fa fa-check  me-3"></i>Añadido!</div>

    <div id="saved-to-favorites" class="snackbar-toast bg-green-dark color-white fade hide" data-delay="3000"
        data-autohide="true"><i class="fa fa-shopping-cart me-3"></i>Añadido al carrito!</div>
    @include('client.partials.modalredes')
    @stack('modals')
    <div id="shared" class="snackbar-toast bg-blue-dark color-white fade hide" data-delay="3000"
        data-autohide="true"><i class="fa fa-shopping-cart me-3"></i>Link copiado!</div>
    <script type="text/javascript" src="{{ asset('scripts/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('scripts/custom.js') }}"></script>

    <script>
        $(document).ready(function() {
            var baseUrl = '{{ env('APP_URL') }}';
            $.ajaxSetup({
                beforeSend: function(xhr, options) {
                    options.url = baseUrl + options.url;
                }
            })
            $(".carrito").click(function() {

                $.ajax({
                    method: "get",
                    url: "/productos/add/carrito/" + $(this).attr('id'),
                    success: function(result) {
                        if (result == 'logout') {
                            window.location.href = "{{ route('login') }}";
                        } else {
                            var toaster = document.getElementById('saved-to-favorites');
                            cart = new bootstrap.Toast(toaster);
                            cart.show()
                        }
                    }
                })

            });
        });
    </script>
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
        $(document).ready(function() {
            $(".copiarLink").click(function() {
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val($(this).attr('ruta')).select();
                document.execCommand("copy");
                $temp.remove();

                
                var toastID = document.getElementById('shared');
                toastID = new bootstrap.Toast(toastID);
                toastID.show();
            });
        })
    </script>


    @stack('scripts')


</body>

</html>
