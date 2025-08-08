<!DOCTYPE HTML>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1, user-scalable=0 viewport-fit=cover" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>{{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }}</title>

    <link rel="stylesheet" type="text/css" href="{{ asset('styles/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/custom.css') }}">

    <link
        href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900|Roboto:300,300i,400,400i,500,500i,700,700i,900,900i&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/highlights/highlight_teal.css') }}">


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



        <div class="page-content">
            @yield('content')
        </div>
        <div class="" id="card-promo"
            style="position:fixed;border-radius:12px;left:10px;right:10px;height:80px;bottom: 10px">
            <div class="card card-style bg-dark-dark m-0" style="height: 85px">
                <div class="p-2">
                    <div class="row p-0 d-flex align-items-center">
                        <div class="col-auto pe-0 m-0">
                            <a href="#"><img src="{{ asset(GlobalHelper::getValorAtributoSetting('logo')) }}"
                                    class="rounded-sm shadow-xl img-fluid" style="height:66px"></a>
                        </div>
                        <div class="col m-0">
                            <a href="#" class="p-0">
                                <strong class="m-0 font-13 text-white m-0">Armando tu pedido!</strong>
                                <p class="color-white font-12 m-0 p-0" style="margin:0;padding:0;line-height:12px">
                                    Agrega todo lo que deseas!
                                </p>
                            </a>
                        </div>
                        <div class="col m-0">
                            <a href="#"
                                class="btn btn-xxs mb-3 rounded-s text-uppercase font-700 shadow-s  mt-3 scale-box">
                                <span class="badge badge-light font-10">Ver pedido</span>
                                <span id="spinner" class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true" style="display: none;"></span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <div id="toast-loading" class="toast toast-tiny toast-top bg-blue-dark fade hide" data-bs-delay="1500"
        data-bs-autohide="true"><i class="fa fa-sync fa-spin me-3"></i>Actualizado!</div>

    <div id="toast-carrito" class="toast toast-tiny toast-top bg-green-dark hide" data-bs-delay="1000"
        data-bs-autohide="true"><i class="fa fa-check  me-3"></i>Añadido!</div>

    <div id="saved-to-favorites" class="snackbar-toast bg-green-dark color-white fade hide" data-delay="3000"
        data-autohide="true"><i class="fa fa-shopping-cart me-3"></i>Añadido al carrito!</div>


    @stack('modals')
    <script type="text/javascript" src="{{ asset('scripts/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('scripts/custom.js') }}"></script>


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
        // $(document).ready(function() {
        //     $(".copiarLink").click(function() {
        //         var $temp = $("<input>");
        //         $("body").append($temp);
        //         $temp.val($(this).attr('ruta')).select();
        //         document.execCommand("copy");
        //         $temp.remove();


        //         var toastID = document.getElementById('shared');
        //         toastID = new bootstrap.Toast(toastID);
        //         toastID.show();
        //     });
        // });
        $(document).ready(function() {
            $(document).on('click', '.copiarLink', async function(e) {
                e.preventDefault();
                // Obtener el valor de la ruta
                const rutaValue = this.getAttribute('ruta');
                
                // Error en caso de que no exista
                if (!rutaValue) {
                    console.error("No se encontro el atributo ruta");
                    return;
                }

                try {
                    // API Moderna de portapapeles
                    await navigator.clipboard.writeText(rutaValue);
                    
                    console.log("Copiado exitoso:", rutaValue);
                    
                    // Revelar toast de existir
                    const toastEl = document.getElementById('shared');
                    if (toastEl) {
                        new bootstrap.Toast(toastEl).show();
                    }
                } catch (err) {
                    console.warn("API de portapapeles moderna fallo, utilizando fallback", err);
                    
                    // Fallback alternartivo para navegadores viejos (no funcionaria con componentes dinamicos)
                    const tempInput = document.createElement('input');
                    tempInput.value = rutaValue;
                    document.body.appendChild(tempInput);
                    tempInput.select();
                    
                    try {
                        const successful = document.execCommand('copy');
                        if (!successful) throw new Error('Copy failed');
                        console.log("Copia mediante fallback exitosa");
                    } finally {
                        document.body.removeChild(tempInput);
                    }
                    
                    // Revelar toast de existir
                    const toastEl = document.getElementById('shared');
                    if (toastEl) {
                        new bootstrap.Toast(toastEl).show();
                    }
                }
            });
        });
    </script>


    @stack('scripts')


</body>

</html>
