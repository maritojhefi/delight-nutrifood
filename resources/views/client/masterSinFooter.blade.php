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

    <link rel="stylesheet" type="text/css" href="{{ asset('styles/bootstrap.css') }}?v=1.0.0">
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/custom.css') }}?v=1.0.0">
    <link rel="stylesheet" href="{{ asset('styles/loader/loader.css') }}?v=1.0.0">
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900|Roboto:300,300i,400,400i,500,500i,700,700i,900,900i&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/highlights/highlight_teal.css') }}">
    @laravelPWA
    <style>
        /* Extra small devices (phones, 600px and down) */
        @media only screen and (max-width: 600px) {}

        /* Small devices (portrait tablets and large phones, 600px and up) */
        @media only screen and (min-width: 600px) {}

        /* Medium devices (landscape tablets, 768px and up) */
        @media only screen and (min-width: 768px) {
            #margen {
                margin-right: 20%;
                margin-left: 20%;
            }
        }

        /* Large devices (laptops/desktops, 992px and up) */
        @media only screen and (min-width: 992px) {
            #margen {
                margin-right: 30%;
                margin-left: 30%;
            }
        }

        /* Extra large devices (large laptops and desktops, 1200px and up) */
        @media only screen and (min-width: 1200px) {
            #margen {
                margin-right: 35%;
                margin-left: 35%
            }
        }
    </style>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $(document).ajaxStart(function() { 
                console.log("AJAX Start detectado - mostrando loader.");
                LoaderManager.setIsLoading(true);
            });
            
            $(document).ajaxStop(function() { 
                console.log("AJAX Stop detectado - ocultando loader.");
                LoaderManager.setIsLoading(false);
            });

            $(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
                console.log("AJAX Error detectado - ocultando loader.");
                LoaderManager.setIsLoading(false);
            });
        });
    </script>
    @stack('header')
</head>


<body id="margen"
    class="{{ isset(auth()->user()->color_page) ? auth()->user()->color_page : 'theme-light' }} margen">
    <!-- <div id="preloader">
        <div class="spinner-border color-highlight" role="status"></div>
    </div> -->
    <div id="preloader" style="background: none;">
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
        <x-appkit-header/>
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
        <div id="menu-main" class="menu menu-box-left menu-box-detached rounded-0 d-flex flex-column"
            data-menu-width="260" data-menu-active="nav-pages" data-menu-effect="menu-over" style="z-index: 2000;">
            @include('client.partials.menu-sidebar')
        </div>
    </div>
    @include('client.partials.modalredes')
    @stack('modals')
    <script type="text/javascript" src="{{ asset('scripts/bootstrap.min.js') }}?v=1.0.0"></script>
    <script type="text/javascript" src="{{ asset('scripts/custom.js') }}?v=1.0.0"></script>
    <script>
        window.AppConfig = {
            logoSmallUrl: "{{ asset(GlobalHelper::getValorAtributoSetting('logo_small')) }}",
        };
    </script>
    <script>
        $(document).ready(function() {
            let splideInstances = [];

            $(document).on('click', 'a[href]', function(e) {
                const preloader = $('#preloader');
                const href = $(this).attr('href');

                if (href && href !== '#' && !href.startsWith('#')) {
                    preloader.removeClass('preloader-hide');
                }
            });

            $(window).on('load', async function() {
                const images = document.querySelectorAll('img');
                const imagePromises = Array.from(images).map(img => {
                    if (img.complete) return Promise.resolve();
                    return new Promise(resolve => {
                        img.onload = resolve;
                        img.onerror = resolve;
                    });
                });

                await Promise.all(imagePromises);

                console.log("Type of Splide:", typeof Splide);
                if (typeof Splide !== 'undefined') {
                    await new Promise(resolve => setTimeout(resolve, 150));
                    
                    const splideElements = document.querySelectorAll('.splide');
                    console.log("splideElements found:", splideElements.length);
                    if (splideElements.length > 0) {
                        await new Promise(resolve => setTimeout(resolve, 100));
                    }
                }

                // Delay extra
                await new Promise(resolve => setTimeout(resolve, 200));
                
                $('#preloader').addClass('preloader-hide');
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
            $(".loader").click(function() {
                $(this).html($(this).text() +
                    ' <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                )

                setTimeout(function() {
                    $(".loader").attr('disabled', 'true')
                }, 100)
                setTimeout(function() {
                    $(".loader").prop('disabled', false)
                    $(".loader").children('span').remove()
                }, 3000)
            });

        });
    </script>
    <script>
        const hacerMenuHiderIntocable = () => {
            $('.menu-hider').css('pointer-events', 'none');
            // console.log("TRIGGERED: Menu hider is now untocable.");
        };

        const hacerMenuHiderTocable = () => {
            $('.menu-hider').css('pointer-events', '');
            // console.log("TRIGGERED: Menu hider is now tocable.");
        };

        const observarCambiosMenuActive = (elemento) => {
            const elementoObjetivo = elemento[0];
            
            const callback = (mutationsList, observer) => {
                for (const mutation of mutationsList) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                        const elm = $(mutation.target);

                        const tieneMenuActive = elm.hasClass('menu-active');
                        
                        // The element is guaranteed to have 'hider-intocable' because of the selector
                        const tieneHiderIntocable = true; 

                        if (tieneMenuActive && tieneHiderIntocable) {
                            // Condition met: Element gains 'menu-active'
                            hacerMenuHiderIntocable();
                        } else if (!tieneMenuActive && tieneHiderIntocable) {
                            // Condition met: Element loses 'menu-active'
                            hacerMenuHiderTocable();
                        }
                    }
                }
            };

            // 3. Create and configure the observer
            const observer = new MutationObserver(callback);
            const config = { attributes: true }; // Only watch for attribute changes

            // 4. Start observing
            observer.observe(elementoObjetivo, config);
            console.log(`Watching element: ${elementoObjetivo.tagName}.${elemento.attr('class')}`);
        };


        // 5. Use jQuery to select ALL target elements and apply the watcher function to each
        $(document).ready(function() {
            $('.hider-intocable').each(function() {
                observarCambiosMenuActive($(this));
            });
        });
    </script>

    @stack('scripts')
</body>
