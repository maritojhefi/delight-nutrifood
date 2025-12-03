<!DOCTYPE HTML>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1, user-scalable=0 viewport-fit=cover" />
    @if(config('app.env') !== 'local')
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endif

    <title>{{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }}</title>

    <link rel="stylesheet" type="text/css" href="{{ asset('styles/bootstrap.css') }}?v=1.0.2">
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/custom.css') }}?v=1.0.1">
    <link rel="stylesheet" href="{{ asset('styles/loader/loader.css') }}?v=1.0.1">
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900|Roboto:300,300i,400,400i,500,500i,700,700i,900,900i&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/highlights/highlight_mint.css') }}">

    @env('production')
        <script>
            var blurred = false;
            window.onblur = function() {
                blurred = true;
            };
            window.onfocus = function() {
                blurred && (location.reload());
            };
        </script>
    @endenv
    <script src="{{ asset('js/app.js') }}?v=1.0.1" defer></script>
    <style>
        .bordeado {
            border-style: dotted;
            border-color: #37BC9B;
            border-width: 2px;
            /* border-radius: 15px; */
        }

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

    <!-- CONFIGURACION AJAX JQUERY -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $(document).ajaxStart(function() { 
                // // console.log("AJAX Start detectado - mostrando loader.");
                LoaderManager.setIsLoading(true);
            });
            
            $(document).ajaxStop(function() { 
                // // console.log("AJAX Stop detectado - ocultando loader.");
                LoaderManager.setIsLoading(false);
            });

            $(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
                // // console.log("AJAX Error detectado - ocultando loader.");
                LoaderManager.setIsLoading(false);
            });
        });
    </script>
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
    <div id="preloader" style="background: none;">
        <!-- <div class="spinner-border color-highlight" role="status"></div> -->
        <!-- Backdrop -->
        <div id="loader-backdrop visible" class="loader-backdrop" style="
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 9998;
            opacity: 1;
            transition: opacity 600ms ease-in-out;
        "></div>

        <!-- Loader Container -->
        <div id="loader-container visible" class="loader-container" style="
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 1;
            transform: scale(0.95);
            transition: opacity 600ms ease-in-out, transform 600ms ease-in-out;
        ">
            <div class="loader-content">
                <!-- Animated logo -->
                <img
                    src="{{ asset(GlobalHelper::getValorAtributoSetting('logo_small')) }}"
                    alt="Loading"
                    class="loader-logo"
                />

                <!-- Pulsing glow effect -->
                <div class="loader-glow"></div>
            </div>

            <!-- Loading text -->
            <div class="loader-text-container">
                <p class="loader-text">Cargando</p>
                <div class="loader-dots">
                    <span class="loader-dot"></span>
                    <span class="loader-dot delay-1"></span>
                    <span class="loader-dot delay-2"></span>
                </div>
            </div>
        </div>
    </div>
    <div id="page">
        {{-- <div class="header header-fixed header-auto-show header-logo-app">
            <a href="#" data-back-button class="header-title header-subtitle">Atras</a>
            <a href="#" data-back-button class="header-icon header-icon-1"><i class="fas fa-arrow-left"></i></a>
            <a href="#" data-menu="menu-main" class="header-icon header-icon-4 "><i class="fas fa-bars"></i></a>
        </div> --}}
        {{-- Inclusion del header-bar --}}
        <x-appkit-header />
        {{-- Inclusion del footer --}}
        @include('client.partials.footer-menu')

        {{-- Renderizado del contenido de la pagina --}}
        <div id="contenido-cliente" class="page-content">
            @yield('content')
        </div>

        <div id="menu-share" class="menu menu-box-bottom menu-box-detached rounded-m" data-menu-height="420"
            data-menu-effect="menu-over">

        </div>
        <div id="menu-highlights" class="menu menu-box-bottom menu-box-detached rounded-m" data-menu-height="510"
            data-menu-effect="menu-over">
            @include('client.partials.menu-colors')
        </div>
        {{-- Inclusion del Menu Sidebar --}}
        <div id="menu-main" class="menu menu-box-left menu-box-detached rounded-0 d-flex flex-column"
            data-menu-width="260" data-menu-active="nav-pages" data-menu-effect="menu-over" style="z-index: 2000;">
            @include('client.partials.menu-sidebar')
        </div>
    </div>
    <div id="toast-loading" class="toast toast-tiny toast-top bg-blue-dark fade hide" data-bs-delay="1500"
        data-bs-autohide="true"><i class="fa fa-sync fa-spin me-3"></i>Actualizado!</div>

    {{-- <div id="toast-carrito" class="toast toast-tiny toast-top bg-green-dark hide" data-bs-delay="1000"
        data-bs-autohide="true"><i class="fa fa-check  me-3"></i>Añadido al carrito!</div> --}}

    <div id="toast-cart-added" class="snackbar-toast bg-green-dark color-white fade hide" data-autohide="true"
        style="z-index: 9999"><i class="fa fa-shopping-cart me-3"></i>Añadido al carrito!</div>
    <div id="toast-cart-item-limit" class="snackbar-toast bg-yellow-dark color-white fade hide" data-autohide="true"
        style="z-index: 9999"><i class="fa fa-shopping-cart me-3"></i>Limite alcanzado!</div>

    <div id="toast-success" class="snackbar-toast bg-green-dark color-white fade hide line-height-l py-2" data-autohide="true"
    style="z-index: 9999"><i class="fa fa-shopping-cart me-3"></i>Mensaje de exito</div>
    <div id="toast-warning" class="snackbar-toast bg-yellow-dark color-white fade hide line-height-l py-2" data-autohide="true"
        style="z-index: 9999"><i class="fa fa-shopping-cart me-3"></i>Mensaje de advertencia</div>
    <div id="toast-error" class="snackbar-toast bg-red-dark color-white fade hide line-height-l py-2" data-autohide="true"
        style="z-index: 9999"><i class="fa fa-shopping-cart me-3"></i>Mensaje de error</div>
    @include('client.partials.modalredes')

    <div id="shared" class="snackbar-toast bg-blue-dark color-white fade hide" data-delay="3000" data-autohide="true"
        style="z-index: 9999"><i class="fa fa-shopping-cart me-3"></i>Link copiado!</div>
    @auth
        @php
            $estaCompleto = auth()->user()->tienePerfilCompleto();
        @endphp
        <x-perfil-incompleto-component :estaCompleto="$estaCompleto" />
    @endauth
    @stack('modals')
    <script type="text/javascript" src="{{ asset('scripts/bootstrap.min.js') }}?v=1.0.0"></script>
    <script type="text/javascript" src="{{ asset('scripts/custom.js') }}?v=1.0.0"></script>
    <script>
        window.AppConfig = {
            logoSmallUrl: "{{ asset(GlobalHelper::getValorAtributoSetting('logo_small')) }}",
        };
    </script>
    <script>
        // CONTROL PRELOADER EN REDIRECCIONES
        $(document).ready(function() {
            let splideInstances = [];

            // Mostrar prelaoder en enlaces normales
            $(document).on('click', 'a[href]', function(e) {
                const preloader = $('#preloader');
                const href = $(this).attr('href');

                if (href && href !== '#' && !href.startsWith('#')) {
                    preloader.removeClass('preloader-hide');
                }
            });

            // Ocultar preloader cuando todo este cargado
            async function hidePreloader() {
                const images = document.querySelectorAll('img');
                const imagePromises = Array.from(images).map(img => {
                    if (img.complete) return Promise.resolve();
                    return new Promise(resolve => {
                        img.onload = resolve;
                        img.onerror = resolve;
                    });
                });

                await Promise.all(imagePromises);

                if (typeof Splide !== 'undefined') {
                    await new Promise(resolve => setTimeout(resolve, 150));
                    
                    const splideElements = document.querySelectorAll('.splide');
                    if (splideElements.length > 0) {
                        await new Promise(resolve => setTimeout(resolve, 100));
                    }
                }

                await new Promise(resolve => setTimeout(resolve, 200));
                
                $('#preloader').addClass('preloader-hide');
            }

            // Manejar carga inicial
            $(window).on('load', hidePreloader);

            // Manejar navegacion atras/adelante
            window.addEventListener('pageshow', function(event) {
                // event.persisted es true cuando la pagina es restaurada desde bfcache
                if (event.persisted) {
                    // La pagina fue restaurada desde cache, ocultar loader inmediatamente
                    $('#preloader').addClass('preloader-hide');
                }
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
    
    <script>
        window.mostrarToastSuccess = (mensaje) => {
            const toastsuccess = $('#toast-success');
            toastsuccess.text(mensaje);
            const toast = new bootstrap.Toast(toastsuccess);
            toast.show()
            setTimeout(() => {
                toast.hide();
            }, 3000);
        }

        window.mostrarToastAdvertencia = (mensaje) => {
            const toasterror = $('#toast-warning');
            toasterror.text(mensaje);
            const toast = new bootstrap.Toast(toasterror);
            toast.show()
            setTimeout(() => {
                toast.hide();
            }, 3000);
        }
        
        window.mostrarToastError = (mensaje) => {
            const toasterror = $('#toast-error');
            toasterror.text(mensaje);
            const toast = new bootstrap.Toast(toasterror);
            toast.show()
            setTimeout(() => {
                toast.hide();
            }, 3000);
        }
    </script>

    <script>
        // SCRIPT PARA EVITAR TRANSFORMACIONES INDESEADAS - SOLO MÉTODO 3
        document.addEventListener('DOMContentLoaded', function() {
            // Establecer el elemento a proteger
            const protectedElement = document.getElementById('contenido-cliente');

            if (!protectedElement) {
                // // console.error('Elemento protegido no encontrado!');
                return;
            }

            // // console.log('🛡️ Bloqueo transformaciones directas de estilo cargado');
            // // console.log('📋 Elemento protegido:', protectedElement);

            // Bloquear transformaciones directas de estilo
            let originalTransformDescriptor = Object.getOwnPropertyDescriptor(protectedElement.style, 'transform');
            if (!originalTransformDescriptor) {
                // De no encontrarse el elemento, obtenerlo del prototipo
                originalTransformDescriptor = Object.getOwnPropertyDescriptor(CSSStyleDeclaration.prototype,
                    'transform');
            }

            Object.defineProperty(protectedElement.style, 'transform', {
                get: function() {
                    // Retornar el valor actual o un string vacio.
                    return originalTransformDescriptor ? originalTransformDescriptor.get.call(this) :
                    '';
                },
                set: function(value) {
                    // // console.log('🚨 TRANFORMACION DIRECTA BLOQUEADA:');
                    // // console.log('   - Valor que se intento implementar:', value);
                    // // console.log('   - Call stack (culpable):');
                    // // console.trace();
                    // No setear nada mas, solo bloquear la transformacion -> return;
                    return;
                },
                configurable: true
            });

            // // console.log('✅ Bloqueo transformaciones directas de estilo cargado');
        });
    </script>
    @stack('scripts')
</body>

</html>
