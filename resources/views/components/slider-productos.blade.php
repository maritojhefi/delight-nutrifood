@props([
    'title' => null,
    'productos' => [],
    'tag' => 'default',
    'orientation' => 'left'
])

@if ($title && $orientation == 'right')
<div class="card float-end bg-red-light mb-3 py-3 me-n4 px-5 rounded-sm" >
    <h2 class="font-24 text-white mb-0" style="z-index: 10">{{$title}}</h2>
    <div class="card-overlay dark-mode-tint"></div>
</div>
@elseif ($title && $orientation == 'left')
<div class="card float-start bg-highlight mb-3 py-3 ms-n4 px-5 rounded-sm"> 
    <h2 class="font-24 text-white mb-0" style="z-index: 10">{{$title}}</h2>
    <div class="card-overlay dark-mode-tint"></div>
</div>
@endif

{{-- Use a custom class to avoid template auto-init, keep splide for structure --}}
<div class="splide product-slider-custom slider-productos" 
     id="{{$tag}}-products-slider" 
     data-orientation="{{$orientation}}"
     data-slider-ready="false"
     style="opacity: 0; transition: opacity 0.3s ease; visibility: visible;">
    <div class="splide__track">
        <ul class="splide__list">
            @foreach ($productos as $producto)
            <li class="splide__slide">
                <a href="{{route('detalleproducto',$producto->id)}}" class="card rounded-md card-style mb-0">
                    <img src="{{ $producto->pathAttachment() }}" 
                        style="max-height: 100px; width: 100%; object-fit: cover;">
                    <div class="position-absolute position-absolute end-0 p-2 bg-theme bg-dtheme-blue rounded-md color-theme" style="border-radius: 0 0 0 0.375rem;">
                        @if ($producto->descuento && $producto->descuento > 0 && $producto->descuento < $producto->precio)
                        <del class="font-bold">Bs {{$producto->precio}}</del>
                        @endif
                        <h4 class="font-14 mb-0">Bs {{$producto->precioReal()}}</h4>
                    </div>
                    <div class="p-2 bg-theme bg-dtheme-blue rounded-sm">
                        <h5 class="font-14 mb-0 line-height-xs text-center"
                            style="height: 1.7rem">
                            {{Str::limit($producto->nombre(), 35)}}
                        </h5>
                    </div>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</div>

@once
    @push('scripts')
    <!-- SCRIPT CONTROL SLIDERS PRODUCTOS -->
    <script type="module">
    import Splide from 'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.esm.min.js';
    import { AutoScroll } from 'https://cdn.jsdelivr.net/npm/@splidejs/splide-extension-auto-scroll@0.4.1/dist/js/splide-extension-auto-scroll.esm.min.js';

    // function destroyTemplateInstance(slider) {
    //     console.log("Intento de destruir instancia slider de plantilla.");
    //     // Destruir instancias inicializadas por el template
    //     if (slider.splide && typeof slider.splide.destroy === 'function') {
    //         try {
    //             slider.splide.destroy(true);
    //             console.log('Destroyed template instance for:', slider.id);
    //         } catch (e) {
    //             console.warn('Failed to destroy template instance:', e);
    //         }
    //     }
        
    //     // Retirar clases y atributos agregados por el template
    //     slider.classList.remove('is-initialized', 'is-active', 'splide--loop', 'splide--ltr', 'splide--rtl', 'splide--draggable');
        
    //     // Reiniciar atributo data
    //     slider.dataset.sliderReady = 'false';
    // }

    const montarSlider = (slider) => {
        // Revisar si montamos el slider
        if (slider.dataset.sliderReady === 'true') {
            console.log('Slider already mounted:', slider.id);
            return;
        }

        // Destruir instancia del templater de existir
        // destroyTemplateInstance(slider);

        const orientation = slider.dataset.orientation || 'left';
        const direction = orientation === 'right' ? 'rtl' : 'ltr';

        try {
            const splide = new Splide(slider, {
                type: 'loop',
                drag: 'free',
                fixedWidth: '180px',
                fixedHeight: '180px',
                gap: '.8rem',
                padding: '.8rem',
                arrows: false,
                pagination: false,
                pauseOnHover: false,
                pauseOnFocus: false,
                direction: direction,
                live:false,
                autoScroll: {
                    speed: 0.5,
                    pauseOnHover: false,
                    pauseOnFocus: false,
                    autoStart: false,
                },
            });

            splide.on('mounted', () => {
                slider.style.opacity = '1';
                slider.dataset.sliderReady = 'true';
                console.log('Slider mounted successfully:', slider.id);
            });

            splide.on('destroy', () => {
                console.log('Slider destroyed:', slider.id);
                slider.dataset.sliderReady = 'false';
            });

            // Montar con AutoScroll
            splide.mount({ AutoScroll });

            splide.Components.AutoScroll.play();

            splide.on('moved', () => {
                splide.Components.AutoScroll.play();
            });

            splide.on('dragged', () => {
                splide.Components.AutoScroll.play();
            })

            
            // Almacenar instancia
            slider.splide = splide;

        } catch (error) {
            console.error('Error mounting slider:', slider.id, error);
        }
    }

    function initSlidersProductos() {
        const sliders = document.querySelectorAll('.slider-productos');
        console.log('Sliders de Productos:', sliders.length);

        // Lazy loading usando IntersectionObserver
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && entry.target.dataset.sliderReady !== 'true') {
                    console.log('Slider entering viewport:', entry.target.id);
                    montarSlider(entry.target);
                    // Continua observando en caso de ser destruido y necesite remontado
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '50px'
        });

        sliders.forEach(slider => {
            observer.observe(slider);
        });
    }

    // Esperar al DOM y dar tiempo al template para inicializar
    document.addEventListener('DOMContentLoaded', () => {
        // Delay para permitir al template ejecutar su JS primero
        setTimeout(() => {
            initSlidersProductos();
        }, 200);
    });

    // // Exponer a la ventana para reinicializacion manual de ser necesario
    // window.reinitSlidersProductos = () => {
    //     document.querySelectorAll('.slider-productos').forEach(slider => {
    // //         destroyTemplateInstance(slider);
    //         montarSlider(slider);
    //     });
    // };
    </script>
    @endpush
@endonce