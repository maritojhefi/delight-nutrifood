<div class="card card-style">
    <div class="cal-header">
        <div class="d-flex bg-highlight bg-dtheme-blue flex-row align-items-center justify-content-evenly w-100">
            <button id="mes-anterior-btn" title="mes-anterior" data-mes-anterior=""
            >
                <i data-lucide="chevron-left" class="lucide-icon"
                                style="width: 2rem; height: 2rem;"

                ></i>
            </button>
            <h4 id="mes-calendario" class="cal-title text-center text-uppercase font-800 color-white">Mes</h4>
            <button id="mes-siguiente-btn" title="mes-siguiente" data-mes-siguiente=""
            >
                <i data-lucide="chevron-right" class="lucide-icon"
                                style="width: 2rem; height: 2rem;"

                ></i>
            </button>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="cal-days bg-highlight bg-dtheme-blue opacity-80 pt-2 pb-2">
        <a href="#">DOM</a>
        <a href="#">LUN</a>
        <a href="#">MAR</a>
        <a href="#">MIE</a>
        <a href="#">JUE</a>
        <a href="#">VIE</a>
        <a href="#">SAB</a>
        <div class="clearfix"></div>
    </div>
    <div class="divider mb-1"></div>
    <div id="fechas-calendario-plan" class="cal-dates cal-dates-border">
        <a href="#" class="cal-disabled">25</a>
        <a href="#" class="cal-disabled">26</a>
        <a href="#" class="cal-disabled">27</a>
        <a href="#" class="cal-disabled">28</a>
        <a href="#" class="cal-disabled">29</a>
        <a href="#" class="cal-disabled">30</a>
        <a href="#" data-menu="menu-events">1</a>
        <a href="#" data-menu="menu-events">2</a>
        <a href="#" data-menu="menu-events">3</a>
        <a href="#" data-menu="menu-events">4</a>
        <a href="#" data-menu="menu-events">5</a>
        <a href="#" data-menu="menu-events">6</a>
        <a href="#" data-menu="menu-events">7</a>
        <a href="#" data-menu="menu-events">8</a>
        <a href="#" data-menu="menu-events">9</a>
        <a href="#" data-menu="menu-events">10</a>
        <a href="#" data-menu="menu-events">11</a>
        <a href="#" class="cal-selected"><button><i class="fa fa-square color-highlight"></i><span>12</span></button></a>
        <a href="#" data-menu="menu-events"><button class="bg-highlight p-2 rounded-xs line-height-xs">13</button></a>
        <a href="#" data-menu="menu-events">14</a>
        <a href="#" data-menu="menu-events">15</a>
        <a href="#" data-menu="menu-events">16</a>
        <a href="#" data-menu="menu-events">17</a>
        <a href="#" data-menu="menu-events">18</a>
        <a href="#" data-menu="menu-events">19</a>
        <a href="#" data-menu="menu-events">20</a>
        <a href="#" data-menu="menu-events">21</a>
        <a href="#" data-menu="menu-events">22</a>
        <a href="#" data-menu="menu-events">23</a>
        <a href="#" data-menu="menu-events">24</a>
        <a href="#" data-menu="menu-events">25</a>
        <a href="#" data-menu="menu-events">26</a>
        <a href="#" data-menu="menu-events">27</a>
        <a href="#" data-menu="menu-events">28</a>
                <!-- <a href="#" class="cal-disabled">1</a> -->
        <a href="#" data-menu="menu-events">29</a>
        <a href="#" data-menu="menu-events">30</a>
        <a href="#" data-menu="menu-events">31</a>
        <a href="#" class="cal-disabled">1</a>
        <a href="#" class="cal-disabled">2</a>
        <a href="#" class="cal-disabled">3</a>
        <a href="#" class="cal-disabled">4</a>
        <a href="#" class="cal-disabled">5</a>
        <div class="clearfix"></div>
    </div>
</div>

@push('header')
    <style>
        .cal-dates button {
            height: 1.8rem;
            width: 1.8rem;
        }
    </style>
@endpush

@push('scripts')
    <!-- Script para el control del calendario appkit -->
    <script>
        $(document).ready( async function () {
            await PlanesService.obtenerCalendarioPlan( {{ $plan->id }},{{ $usuario->id }} ).then(
                (respuestaCalendario) => {
                    console.log("Respuesta obtenida sobre la informacion del plan: ", respuestaCalendario.data);
                    console.log("Respuesta servida desde la cache: ", respuestaCalendario.cached);

                    // Obtener respuestas separadas divididas por meses en orden
                    const mesesPlan = respuestaCalendario.data.meses;
                    // Usar el mes y las fechas contenidas en el para renderizar el calendario
                    const infoMesActual = mesesPlan.find((mes) => mes.currentDayFlag);
                    console.log("info meses plan", mesesPlan);
                    
                    if (infoMesActual) {
                        construirCalendario(infoMesActual, mesesPlan);
                    }
                }
            );
        });

        const construirCalendario = (infoMes, infoMeses) => {
            console.log("Construyendo calendario para: ", infoMes);
            
            // Actualizar el titulo del mes
            $('#mes-calendario').text(`${infoMes.nombre} ${infoMes.anio}`);

            
            // Actualizar botones
            $('#mes-anterior-btn').data('mes-anterior', infoMes.numero === 1 ? 12 : infoMes.numero - 1);
            // $('#mes-anterior-btn').data('mes-anterior', infoMes.mes.numero === 1 ? 12 : infoMes.numero - 1);
            $('#mes-anterior-btn').data('anio', infoMes.numero === 1 ? infoMes.anio - 1 :infoMes.anio);
            $('#mes-siguiente-btn').data('mes-siguiente', infoMes.numero === 12 ? 1 : infoMes.numero + 1);
            $('#mes-siguiente-btn').data('anio', infoMes.numero === 12 ? infoMes.anio + 1 :infoMes.anio);
            
            // Mapear dias con planes
            const diasDisponibles = {};
            infoMes.dias.forEach(dia => {
                const fecha = dia.start; // Formato: YYYY-MM-DD
                diasDisponibles[fecha] = dia;
            });
            
            // Obtener mes y año
            const year = infoMes.anio;
            const month = infoMes.numero; // 1-12
            
            // Primer dia del mes
            const primerDia = new Date(year, month - 1, 1);
            // Ultimo dia del mes
            const ultimoDia = new Date(year, month, 0);
            
            // Calcular el primer dia de la semana del mes (0 = Sunday, 6 = Saturday)
            const primerDiaSemana = primerDia.getDay();
            
            // Calcular cuantos dias se mostraran del mes anterior 
            const diasMesAnterior = primerDiaSemana;
            
            // Calcular cuantos dias se mostraran del mes siguiente 
            const ultimoDiaSemana = ultimoDia.getDay();
            const diasMesSiguiente = ultimoDiaSemana === 6 ? 0 : 6 - ultimoDiaSemana;
            
            // Build the calendar grid
            const calendarHTML = [];
            
            // Add days from previous month
            const mesAnterior = new Date(year, month - 1, 0);
            const ultimoDiaMesAnterior = mesAnterior.getDate();
            
            // Dias de mes anterior
            for (let i = diasMesAnterior - 1; i >= 0; i--) {
                const dia = ultimoDiaMesAnterior - i;
                calendarHTML.push(`<a href="#" class="cal-disabled">${dia}</a>`);
            }
            
            // Agregar los dias del mes actual
            const totalDiasMes = ultimoDia.getDate();
            const hoy = new Date();
            const esHoy = (dia) => {
                return hoy.getDate() === dia && 
                        hoy.getMonth() === (month - 1) && 
                        hoy.getFullYear() === year;
            };
            
            for (let dia = 1; dia <= totalDiasMes; dia++) {
                const fecha = `${year}-${String(month).padStart(2, '0')}-${String(dia).padStart(2, '0')}`;
                const diaInfo = diasDisponibles[fecha];
                let bgColor = '';
                if (diaInfo) {

                    switch (diaInfo.tipo) {
                        case 'pendiente':
                            bgColor = 'bg-highlight';
                            break;
                        case 'permiso':
                            bgColor = 'bg-magenta-dark';
                            break;
                        case 'finalizado':
                            bgColor = 'bg-orange-dark';
                            break;
                        default:
                            break;
                    }

                    // Si el dia dispone de un plan/feriado
                    if (esHoy(dia)) {
                        // Dia de hoy con plan
                        calendarHTML.push(`
                            <a href="#" class="cal-selected" data-menu="menu-events" data-fecha="${fecha}">
                                <button class="${bgColor} rounded-xs line-height-xs">${dia}</button>
                            </a>
                        `);
                    } else if (diaInfo.tipo === 'feriado') {
                        // Feriado
                        calendarHTML.push(`
                            <a href="#" data-menu="menu-events" data-fecha="${fecha}" class="cal-feriado">
                                <button class="bg-red-dark rounded-xs line-height-xs">${dia}</button>
                            </a>
                        `);
                    } else {
                        // Dia regular con plan
                        calendarHTML.push(`
                            <a href="#" data-menu="menu-events" data-fecha="${fecha}">
                                <button class="${bgColor} rounded-xs line-height-xs">${dia}</button>
                            </a>
                        `);
                    }
                } else {
                    // Dias sin planes
                    if (esHoy(dia)) {
                        // Dia actual sin eventos
                        calendarHTML.push(`
                            <a href="#" data-fecha="${fecha}">
                                <button>
                                    <span>${dia}</span>
                                </button>
                            </a>
                        `);
                    } else {
                        // Dia cualquiera sin plan
                        calendarHTML.push(`<a href="#" data-fecha="${fecha}">${dia}</a>`);
                    }
                }
            }
            
            // Dias del siguiente mes
            for (let dia = 1; dia <= diasMesSiguiente; dia++) {
                calendarHTML.push(`<a href="#" class="cal-disabled">${dia}</a>`);
            }
            
            // Agregar clearfix
            calendarHTML.push('<div class="clearfix"></div>');
            
            // Actualizar elementos del calendario
            $('#fechas-calendario-plan').html(calendarHTML.join(''));

            $('#mes-anterior-btn').off('click').on('click', function (e) {
                e.preventDefault();
                console.log("cambio al mes anterior");
                const mesAnterior = $(this).data("mes-anterior");
                const anioAnterior = $(this).data("anio");
                const nuevoMes = infoMeses.filter((mes) => mes.numero == mesAnterior && mes.anio == anioAnterior);
                console.log("mes anterior: ", nuevoMes);
                
                if (nuevoMes.length) {
                    construirCalendario(nuevoMes[0], infoMeses);
                }
            });

            $('#mes-siguiente-btn').off('click').on('click', function (e) {
                e.preventDefault();
                console.log("cambio al mes siguiente");
                const mesSiguiente = $(this).data("mes-siguiente");
                const anioSiguiente = $(this).data("anio");

                const nuevoMes = infoMeses.filter((mes) => mes.numero == mesSiguiente && mes.anio == anioSiguiente);
                console.log("mes siguiente: ", nuevoMes);

                
                if (nuevoMes.length) {
                    construirCalendario(nuevoMes[0], infoMeses);
                }
            });

            // Handler para click en botones de dias con plan
            $('.cal-dates a[data-fecha]').not('.cal-disabled').on('click', function(e) {
                e.preventDefault();
                const fecha = $(this).data('fecha');
                const diaInfo = diasDisponibles[fecha];
                
                if (diaInfo) {
                    console.log('Día seleccionado:', fecha, diaInfo);
                    // Handle day selection - show events menu, etc.
                }
            });
        }
    </script>
@endpush