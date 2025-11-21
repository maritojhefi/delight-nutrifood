@props([
    'horarios' => [],
    'horariosData' => null,
])

{{-- SLIDER HORARIOS --}}
<div class="splide horarios-slider-custom my-4" 
    id="horarios-slider"
    data-slider-ready="false"
    style="opacity: 0; transition: opacity 0.3s ease; visibility: visible;">
    <div class="splide__track" id="horarios-slider-track">
        <ul class="splide__list" id="horarios-slider-list">
            @foreach ($horarios as $horario)
                <li class="splide__slide d-flex flex-row align-items-center justify-content-center" id="horarios-slider-{{trim($horario->nombre)}}">
                    <h2 class="font-20 d-block pb-0 m-0">
                        <button class="time-btn opacity-50" data-time="{{ trim($horario->nombre) }}">
                            {{ ucfirst(Str::lower(trim($horario->nombre))) }}
                        </button>
                    </h2>
                </li>
            @endforeach
        </ul>
    </div>
</div>

{{-- SLIDER SUBCATEGORIAS --}}
<div class="splide categorizados-slider-custom mb-4" 
    id="slider-categorizados"
    data-slider-ready="false"
    style="opacity: 0; transition: opacity 0.3s ease; visibility: visible;">
    <div class="splide__track" id="slider-categorizados-track">
        <ul class="splide__list" id="slider-categorizados-list">
            {{-- LISTADO DE ELEMENTOS A RENDERIZARSE --}}
        </ul>
    </div>
</div>

@once
@push('scripts')
{{-- SCRIPT CONTROL DE SLIDER DE HORARIOS --}}
<script type="module">
    import Splide from 'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.esm.min.js';
    import { AutoScroll } from 'https://cdn.jsdelivr.net/npm/@splidejs/splide-extension-auto-scroll@0.4.1/dist/js/splide-extension-auto-scroll.esm.min.js';

    const subcategoriasPorHorario = @json($horariosData);
    const horarios = @json($horarios);

    let horariosSlider = null;
    let categorizadosSlider = null;
    let defaultTime = '';

    // DETERMINAR HORARIO ACTUAL
    const determinarHorarioActual = () => {
        const now = new Date();
        const currentHour = now.getHours();
        const currentMinute = now.getMinutes();
        const currentTimeInMinutes = currentHour * 60 + currentMinute;
        
        let horarioActual = '';

        // Iterar sobre los horarios para encontrar el rango que incluye la hora actual
        for (const horario of horarios) {
            const startTime = horario.hora_inicio.split(':');
            const endTime = horario.hora_fin.split(':');
            
            const startMinutes = parseInt(startTime[0]) * 60 + parseInt(startTime[1]);
            const endMinutes = parseInt(endTime[0]) * 60 + parseInt(endTime[1]);
            
            // Manejar casos donde el horario cruze la medianoche (ejm: 19:00 - 03:59)
            if (startMinutes > endMinutes) {
                if (currentTimeInMinutes >= startMinutes || currentTimeInMinutes <= endMinutes) {
                    horarioActual = horario.nombre;
                    break;
                }
            } else {
                if (currentTimeInMinutes >= startMinutes && currentTimeInMinutes <= endMinutes) {
                    horarioActual = horario.nombre;
                    break;
                }
            }
        }

        // Si no se encontro un horario actual, buscar el siguiente mas cercano
        if (!horarioActual) {
            let siguienteHorario = null;
            let minTimeDiff = Infinity;

            for (const horario of horarios) {
                const startTime = horario.hora_inicio.split(':');
                const startMinutes = parseInt(startTime[0]) * 60 + parseInt(startTime[1]);
                
                let timeDiff = startMinutes >= currentTimeInMinutes
                    ? startMinutes - currentTimeInMinutes
                    : (24 * 60) - currentTimeInMinutes + startMinutes;
                
                if (timeDiff < minTimeDiff) {
                    minTimeDiff = timeDiff;
                    siguienteHorario = horario;
                }
            }

            horarioActual = siguienteHorario?.nombre || horarios[0]?.nombre || '';
        }

        return horarioActual;
    };

    // MONTAR SLIDER HORARIOS
    const montarSliderHorarios = (sliderElement) => {
        if (sliderElement.dataset.sliderReady === 'true') {
            console.log('Slider de horarios ya montado');
            return;
        }

        try {
            horariosSlider = new Splide(sliderElement, {
                type: 'loop',
                perPage: 3,
                gap: '1rem',
                arrows: false,
                pagination: false,
                fixedWidth: '6rem',
                live:false,
            });

            horariosSlider.on('mounted', () => {
                sliderElement.style.opacity = '1';
                sliderElement.dataset.sliderReady = 'true';
                console.log('Slider de horarios montado con éxito');
                
                // Cargar items por defecto
                actualizarSliderHorario(defaultTime);
                
                // Activar boton por defecto
                document.querySelectorAll('.time-btn').forEach(btn => {
                    if (btn.getAttribute('data-time') === defaultTime) {
                        btn.classList.add('is-active');
                        btn.classList.remove('opacity-50');
                    }
                });
            });

            horariosSlider.mount();

        } catch (error) {
            console.error('Error montando slider de horarios:', error);
        }
    };

    // MONTAR SLIDER CATEGORIZADOS
    const montarSliderCategorizados = (sliderElement) => {
        if (sliderElement.dataset.sliderReady === 'true') {
            console.log('Slider de subcategorias ya montado');
            return;
        }

        try {
            categorizadosSlider = new Splide(sliderElement, {
                type: 'loop',
                perPage: 2,
                arrows: false,
                pagination: false,
                fixedWidth: '14rem',
                direction: 'rtl',
                live:false,
                autoScroll: {
                    speed: 0.2,
                    pauseOnHover: false,
                    pauseOnFocus: false,
                    autoStart: false,
                },
                breakpoints: {
                    640: {
                        perPage: 1,
                    },
                },
            });

            categorizadosSlider.on('mounted', () => {
                sliderElement.style.opacity = '1';
                sliderElement.dataset.sliderReady = 'true';
                console.log('Slider de subcategorias montado con éxito');
            });

            categorizadosSlider.mount({ AutoScroll });

            categorizadosSlider.Components.AutoScroll.play();

            categorizadosSlider.on('moved', () => {
                categorizadosSlider.Components.AutoScroll.play();
            });

            categorizadosSlider.on('dragged', () => {
                categorizadosSlider.Components.AutoScroll.play();
            })

        } catch (error) {
            console.error('Error al montar slider de subcategorias:', error);
        }
    };

    // ACTUALIZAR SLIDER HORARIO
    const actualizarSliderHorario = (horarioNombre) => {
        const items = subcategoriasPorHorario[horarioNombre] || [];
        const listElement = document.getElementById('slider-categorizados-list');
        const sliderElement = document.getElementById('slider-categorizados');

        if (!listElement || !sliderElement) {
            console.error('No hay elementos categorizados para actualizar');
            return;
        }

        // Destruir slider si existe
        if (categorizadosSlider) {
            try {
                categorizadosSlider.destroy();
            } catch (e) {
                console.warn('Error destroying slider:', e);
            }
            categorizadosSlider = null;
        }

        // Reset estado - mantener visibility visible
        sliderElement.dataset.sliderReady = 'false';
        sliderElement.style.opacity = '0';
        sliderElement.style.visibility = 'visible';

        // Renderizar items
        if (items && items.length > 0) {
            listElement.innerHTML = items.map(item => {
                const formattedName = item.nombre.charAt(0).toUpperCase() + item.nombre.slice(1).toLowerCase();
                
                return `
                    <li class="splide__slide hover-grow-s" style="width: 12rem;">
                        <div class="productos-subcategoria-trigger card mx-3 mb-0 card-style bg-20"
                            data-subcategoria-id="${item.id}"
                            data-subcategoria-nombre="${item.nombre}"
                            style="height: 14rem; background-image: url('${item.foto}'); background-size: cover; background-position: center;">
                            <div class="card-bottom">
                                <h3 class="color-white font-18 font-600 mb-3 mx-3">${formattedName}</h3>
                            </div>
                            <div class="card-overlay bg-gradient"></div>
                        </div>
                    </li>
                `;
            }).join('');

            // Montar nuevo slider
            requestAnimationFrame(() => {
                montarSliderCategorizados(sliderElement);
            });
        } else {
            listElement.innerHTML = `
                <li class="splide__slide">
                    <div class="card card-style m-0 p-4 text-center">
                        <p class="mb-0 opacity-50">No hay subcategorías disponibles para este horario</p>
                    </div>
                </li>
            `;
            sliderElement.style.opacity = '1';
            sliderElement.style.visibility = 'visible';
        }
    };

    // EVENT LISTENERS
    // Click en botones de horario
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('time-btn')) {
            e.preventDefault();
            const hora = e.target.getAttribute('data-time');

            // Remover clase activa de todos los botones
            document.querySelectorAll('.time-btn').forEach(btn => {
                btn.classList.add('opacity-50');
                btn.classList.remove('is-active');
            });

            // Activar el boton clickeado
            e.target.classList.remove('opacity-50');
            e.target.classList.add('is-active');

            // Actualizar el contenido del slider
            actualizarSliderHorario(hora);
        }
    });

    // Click en subcategorías (usando jQuery para ProductoService)
    $(document).on('click', '.productos-subcategoria-trigger', async function(e) {
        e.preventDefault();
        
        const subcategoriaId = $(this).data('subcategoria-id');
        const subcategoriaNombre = $(this).data('subcategoria-nombre');
        
        try {
            const productosSubcategoria = await ProductoService.getProductosCategoria(subcategoriaId);
            abrirDialogListado(productosSubcategoria.data, subcategoriaNombre);
        } catch (error) {
            console.error('Error cargando los productos de la subcategoria:', error);
        }
    });

    // INICIALIZACIÓN
    const initSlidersHorariosCategorizados = () => {
        const horariosSliderEl = document.getElementById('horarios-slider');
        const categorizadosSliderEl = document.getElementById('slider-categorizados');

        if (!horariosSliderEl || !categorizadosSliderEl) {
            console.error('No hay sliders para inicializar');
            return;
        }

        // Determinar horario por defecto
        defaultTime = determinarHorarioActual();
        // console.log('Horario por defecto:', defaultTime);

        // Montar slider de horarios (que a su vez cargará el de categorizados)
        montarSliderHorarios(horariosSliderEl);
    };

    // Esperar al DOM y dar tiempo al template para inicializar
    document.addEventListener('DOMContentLoaded', () => {
        // Delay para permitir al template ejecutar su JS primero
        setTimeout(() => {
            initSlidersHorariosCategorizados();
        }, 200);
    });
</script>
@endpush
@endonce