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

@push('modals')
    <!-- <div id="menu-events" class="menu menu-box-bottom rounded-m menu-active" data-menu-height="420" style="display: block; height: 420px;"> -->
    <div id="menu-pedidos-dia-calendario" class="menu menu-box-modal rounded-m" style="width: 90%">
        <div class="menu-title flex-align-center">
            <p class="color-highlight line-height-xs" style="width: 85%;">{{ $plan->nombre }}</p>
            <h1 id="fecha-menu-dia-calendario" class="font-20 mt-1 mb-0">29 de Febrero 2025</h1>
            <a href="#" class="close-menu"><i class="fa fa-times-circle"></i></a>
        </div>
        <div class="divider divider-margins my-2"></div>
        <div class="content mt-0">
            <a id="pendientes-anchor" href="#" class="d-flex gap-3 align-items-center mb-3">
                <div class="align-self-center">
                    <i data-lucide="notebook-pen" class="lucide-icon color-theme" style=" width: 2rem; height: 2rem;"></i>
                </div>
                <div class="align-self-center">
                    <h5 class="">Pedidos Pendientes</h5>
                    <p class="mb-0 mt-n1 font-10">
                        <span><i class="fa fa-map-marker color-blue-dark pe-1"></i>Solicitar permiso para mis pedidos del día</span>
                        <!-- <span class="ps-2"><i class="fa fa-user color-green-dark pe-1"></i>25k+ Attending</span> -->
                    </p>
                </div>
                <div class="align-self-center ms-auto">
                    <i class="fa fa-arrow-right pe-2 opacity-30"></i>
                </div>
            </a>
            <a id="finalizados-anchor" href="#" class="d-flex gap-3 align-items-center mb-3">
                <div class="align-self-center">
                    <i data-lucide="calendar-check" class="lucide-icon color-theme" style=" width: 2rem; height: 2rem;"></i>
                </div>
                <div class="align-self-center">
                    <h5 class="color-orange-dark">Pedidos Finalizados</h5>
                    <p class="mb-0 mt-n1 font-10">
                        <span><i class="fa fa-map-marker color-blue-dark pe-1"></i>Ver el historial de mis pedidos este día (pronto)</span>
                        <!-- <span class="ps-2"><i class="fa fa-user color-green-dark pe-1"></i>25k+ Attending</span> -->
                    </p>
                </div>
                <!-- <div class="align-self-center ms-auto">
                    <i class="fa fa-arrow-right pe-2 opacity-30"></i>
                </div> -->
            </a>
            <a id="permisos-anchor" href="#" class="d-flex gap-3 align-items-center mb-3">
                <div class="align-self-center">
                    <i data-lucide="calendar-clock" class="lucide-icon color-theme" style=" width: 2rem; height: 2rem;"></i>
                </div>
                <div class="align-self-center">
                    <h5 class="color-magenta-dark">Permisos</h5>
                    <p class="mb-0 mt-n1 font-10">
                        <span><i class="fa fa-map-marker color-blue-dark pe-1"></i>Retirar permisos para mis pedidos este día (pronto) </span>
                        <!-- <span class="ps-2"><i class="fa fa-user color-green-dark pe-1"></i>25k+ Attending</span> -->
                    </p>
                </div>
                <div class="align-self-center ms-auto">
                    <i class="fa fa-arrow-right pe-2 opacity-30"></i>
                </div>
            </a>
        </div>
    </div>

    <div id="menu-pedir-permisos" class="menu menu-box-modal rounded-m" style="width: 90%">
        <div class="menu-title flex-align-center">
            <a href="#" class="back-menu-pedidos-dia">
                <i data-lucide="chevron-left" class="lucide-icon"></i>
            </a>
            <p class="color-highlight line-height-xs" style="width: 85%;">{{ $plan->nombre }}</p>
            <h1 id="fecha-menu-dia-calendario" class="font-24 mt-1 mb-0">29 de Febrero 2025</h1>
        </div>
        <div class="divider divider-margins mt-1 mb-0"></div>
        <div class="content m-0 p-3">
                <!-- <h4 class="font-700 mb-3">Seleccionar Permisos</h4> -->
                <p class="mb-0">Tienes <span id="mensaje-cantidad-permisos"></span> pedidos disponibles para marcar con permiso este día.</p>
            <div id="menu-selector-permisos">Selector</div>
        </div>
    </div>

    <div id="menu-deshacer-permisos" class="menu menu-box-modal rounded-m" style="width: 90%">
        <div class="menu-title flex-align-center">
            <a href="#" class="back-menu-pedidos-dia">
                <i data-lucide="chevron-left" class="lucide-icon"></i>
            </a>
            <p class="color-highlight line-height-xs" style="width: 85%;">{{ $plan->nombre }}</p>
            <h1 id="fecha-menu-dia-calendario" class="font-24 mt-1 mb-0">29 de Febrero 2025</h1>
        </div>
        <div class="divider divider-margins my-2"></div>
        <div class="content mt-0">
            <p>Selector</p>
        </div>
    </div>
<!-- 
    <div id="menu-permiso-simple" class="menu menu-box-modal rounded-m" style="width: 90%">
        <div class="menu-title flex-align-center">
            <p class="color-highlight line-height-xs" style="width: 85%;">{{ $plan->nombre }}</p>
            <h1 id="fecha-menu-dia-calendario" class="font-24 mt-1 mb-0">29 de Febrero 2025</h1>
            <a href="#" class="back-menu-pedidos-dia">
                <i class="fa fa-times-circle close-menu"></i>
            </a>
        </div>
        <div class="divider divider-margins mt-1 mb-0"></div>
        <div class="content m-0 p-3">
            <p class="mb-2">
                <span class="color-theme" id="textoPermisoSimple"></span>
                <br>
                No perderás tu pedido, este pasará al dia siguiente del final de tu plan.
            </p>
            <div class="d-flex flex-row align-items-center justify-content-evenly">
                <button class="close-menu py-2 px-3 font-15 bg-delight-red color-white rounded-s line-height-s text-uppercase font-600 shadow-xl">Cancelar</button>
                <button id="btnPermisoSimple" class="py-2 px-3 font-15 bg-highlight color-white rounded-s line-height-s text-uppercase font-600 shadow-xl">Confirmar</button>
            </div>
        </div>
    </div> -->

    <div id="menu-permiso-simple" class="menu menu-box-modal pb-3 rounded-m overflow-hidden" style="width: 80%;">
        <div class="menu-title p-3">
            <div class="d-flex flex-row gap-2 align-items-center">
                <i data-lucide="calendar-clock" class="lucide-icon" style="width: 2.5rem; height: 2.5rem;"></i>
                <div>
                    <!-- <p class="color-highlight font-10">{{ $plan->nombre }}</p> -->
                    <h1 id="titulo-simple" class="font-20 p-0 m-0 line-height-m">Solicitar permiso</h1>
                </div>
            </div>
            <a href="#" class="close-menu"><i data-lucide="x-circle" class="lucide-icon"></i></a>
        </div>
        <div class="content mt-0 mb-3 d-flex flex-column h-100 gap-3">
            <p id="texto-simple" class="pe-3 mb-0">
                Tu pedido para <span id="fecha-simple"></span>. será pospuesto por un dia.
                
            </p>
            <div class="d-flex flex-row justify-content-between mb-0">
                <a href="#" class="btn close-menu btn-s rounded-s text-uppercase bg-delight-red font-600 rounded-s">Cancelar</a>
                <button id="btnConfirmarSimple" data-pedido="" href="#" class="btn btn-s rounded-s text-uppercase bg-highlight font-600 rounded-s">
                    <span class="d-flex flex-row align-items-center gap-1">Confirmar</span>
                </button>
            </div>
        </div>
    </div>
    <!-- <div id="menu-deshacer-simple">
        <div class="menu-title flex-align-center">
            <p class="color-highlight line-height-xs" style="width: 85%;">{{ $plan->nombre }}</p>
            <h1 id="fecha-menu-dia-calendario" class="font-24 mt-1 mb-0">29 de Febrero 2025</h1>
            <a href="#" class="back-menu-pedidos-dia">
                <i data-lucide="chevron-left" class="lucide-icon"></i>
                <i class="fa fa-times-circle"></i>
            </a>
        </div>
        <div class="divider divider-margins mt-1 mb-0"></div>
    </div> -->
@endpush

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
            // // $('.menu-hider').addClass('menu-active');
            await renderizarCalendario();

            // await PlanesService.obtenerCalendarioPlan( {{ $plan->id }},{{ $usuario->id }} ).then(
            //     (respuestaCalendario) => {
            //         console.log("Respuesta obtenida sobre la informacion del plan: ", respuestaCalendario.data);
            //         console.log("Respuesta servida desde la cache: ", respuestaCalendario.cached);

            //         // Obtener respuestas separadas divididas por meses en orden
            //         const mesesPlan = respuestaCalendario.data.meses;
            //         // Usar el mes y las fechas contenidas en el para renderizar el calendario
            //         const infoMesActual = mesesPlan.find((mes) => mes.currentDayFlag);
            //         console.log("info meses plan", mesesPlan);
                    
            //         if (infoMesActual) {
            //             construirCalendario(infoMesActual, mesesPlan);
            //         }
            //     }
            // );
        });

        const renderizarCalendario = async () => {
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
        }

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

                    // Check if diaInfo.eventos exists. If it does, get its length. 
                    // If it doesn't (i.e., it's undefined or null), use 0 instead.
                    let numPlanesDia = diaInfo.eventos ? diaInfo.eventos.length : 0;

                    const badgeHTML = numPlanesDia > 1 ? `
                                    <div class="badge border bg-theme bg-dtheme-blue color-theme bg-delight-red position-absolute top-0">
                                        ${numPlanesDia}
                                    </div>
                                ` : '';

                    const claseAccion = numPlanesDia > 1 ? 'cal-menu-opener' : 'cal-disabled-menu';

                    let claseAccionSimple = '';

                    if (numPlanesDia == 1) {
                        // Determinar si diaInfo es apropiado para pedir permiso o remover permiso
                        // Dependiendo del resultado, se les asignara una clase
                        // confirmar-pedido-permiso puede pedir permiso

                        const fechaPedido = new Date(diaInfo.end + 'T08:55:00');
                        const ahora = new Date();
                        const habilitadoAccion = fechaPedido > ahora;

                        console.log("InfoDiaSimple:", diaInfo);
                        console.log("HabilitadoAccion:", habilitadoAccion);
                            
                        if (diaInfo.eventos[0].estado == "pendiente" && habilitadoAccion) {
                            claseAccionSimple = `pedir-permiso-simple`;
                            console.log("Hay boton pedido simple calendario");
                        } else if (diaInfo.eventos[0].estado == "permiso" && habilitadoAccion) {
                            claseAccionSimple = `deshacer-menu-${diaInfo.eventos[0].id}`;
                            console.log("Hay boton deshacer simple calendario");
                        }

                        // confirmar-deshacer-permiso puede deshacer permiso
                    }
                    // REALIZAR LA ASIGNACION APROPIADA DE INFORMACION PARA EL DESPLIEGUE DEL MODAL/MENU

                    // Si el dia dispone de un plan/feriado
                    if (esHoy(dia)) {
                        // Dia de hoy con plan
                        console.log("diaInfo:",diaInfo);

                        calendarHTML.push(`
                            <a href="#" class="cal-selected ${claseAccion} position-relative" data-fecha="${fecha}">
                                <button class="${bgColor} rounded-xs line-height-xs">${dia} ${badgeHTML}</button>
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
                            <a href="#" class="${claseAccion} position-relative"  data-fecha="${fecha}">
                                <button class="${bgColor} rounded-xs line-height-xs">${dia} ${badgeHTML}</button>
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
            $('.cal-dates a[data-fecha]').not('.cal-disabled-menu').on('click', function(e) {
                e.preventDefault();
                const fecha = $(this).data('fecha');
                const diaInfo = diasDisponibles[fecha];
                
                if (diaInfo) {
                    console.log('Día seleccionado:', fecha, diaInfo);

                    if (diaInfo.tipo != 'feriado') {
                        revelarMenuDia(diaInfo, infoMes);
                    }
                    // Handle day selection - show events menu, etc.
                }
            });

            $('.cal-disabled-menu').off('click').on('click', function(e) {
                e.preventDefault();
                const fecha = $(this).data('fecha');
                const diaInfo = diasDisponibles[fecha];
                console.log("Diainfo para evento con permiso simple: ", diaInfo);
                // switch(diaInfo[0].estado)
                switch (diaInfo.eventos[0].estado) {
                    case "pendiente":
                        // construirMenuPermisoSimple(true);
                        console.log("Es pendiente");
                        construirMenuPermisoSimple(true, diaInfo, infoMes);
                        revelarMenuPermisoSimple();
                        break;
                    case "permiso":
                        console.log("Es permiso");
                        construirMenuPermisoSimple(false, diaInfo, infoMes);
                        revelarMenuPermisoSimple();
                        break;
                    default:
                        break;
                }
            });


        }

        const construirMenuPermisoSimple = (esPendiente, infoDia, infoMes) => {
             // CAMBIAR LA FECHA DEL MODAL
            const stringFecha = `${infoDia.start}T00:00:00Z`;
            const dateObj = new Date(stringFecha);
            const dia = dateObj.getUTCDate();
            // const botonAccionSimple = $('#btnPermisoSimple');
            // const textoSimple = $('#textoPermisoSimple');
            const tituloSimple = $('#titulo-simple');
            const textoSimple = $('#texto-simple'); // elemento <p>, incluir html con span para modificarlo
            // const textoFecha = $('#fecha-simple');
            const botonConfirmacion = $('#btnConfirmarSimple');

            botonConfirmacion.data('pedido', infoDia.eventos[0].id);
            // textoFecha.text(infoDia.start);
            // const fecha = infoDia.start.trim('-');
            $('#fecha-menu-dia-calendario').text(`${dia} de ${infoMes.nombre}  de ${infoMes.anio}`);
            // asignar funcionalidad al boton de confirmacion
            if (esPendiente) {
                tituloSimple.text("Solicitar Permiso");
                textoSimple.text("¿Deseas pedir permiso para el pedido de este día?");
                $('#btnConfirmarSimple').off('click').on('click', async function(e) {
                    e.preventDefault();
                    
                    const cantidadPermisos = 1;
                    const $btn = $(this);
                    const $span = $btn.find('span');
                    const textoOriginal = $span.text();
                    
                    // Disable controls while processing
                    botonConfirmacion.prop('disabled', true);
                    // $span.html('<i class="fa fa-spinner fa-spin me-1"></i>Procesando');
                    
                    try {
                        const response = await PlanesService.asignarPermisosVarios(infoDia.start, cantidadPermisos, {{ $plan->id }});
                        await renderizarCalendario();
                        console.log('Permisos marcados exitosamente:', response.data);
                        ocultarMenusPermisos();
                        ocultarTodosMenus();
                        // Show success message and refresh calendar
                        // ... your success handling logic
                        
                    } catch (error) {
                        console.error('Error al marcar permisos:', error);
                        // Show error message
                    } finally {
                        // Re-enable everything
                        $btn.prop('disabled', false);
                        $input.prop('disabled', false);
                        $span.text(textoOriginal);
                    }
                });
                // botonAccionSimple.removeClass('deshacer-simple');
                // botonAccionSimple.addClass('permiso-simple');
                // textoSimple.text("¿Deseas pedir permiso para este día?");
            } else {
                tituloSimple.text("Deshacer Permiso");
                textoSimple.text("¿Deseas retirar el permiso para este día?");
            }

            

            // botonAccionSimple.off('click').on('click', async function (e) {
            //     e.preventDefault();

            //     if (botonAccionSimple.hasClass('permiso-simple')) {
            //         console.log("Axios call para asignar permiso");
            //     } else if (botonAccionSimple.hasClass('deshacer-simple')) 
            //     {
            //         console.log("Axios call para retirar permiso");   
            //     }                
            // });
        }

        const construirMenuPedidosDia = (infoDia, infoMes) => {
            console.log("Construccion del modal/menu para controlar los pedidos del cliente");
            console.log("Informacion para construir el modal:", infoDia);
            console.log("Informacion del mes pal modal", infoMes);

            // CAMBIAR LA FECHA DEL MODAL
            const stringFecha = `${infoDia.start}T00:00:00Z`;
            const dateObj = new Date(stringFecha);
            const dia = dateObj.getUTCDate();
            // const fecha = infoDia.start.trim('-');
            $('#fecha-menu-dia-calendario').text(`${dia} de ${infoMes.nombre}  de ${infoMes.anio}`);

            // Agrupar pedidos
            const pendientes = infoDia.eventos.filter((pedido) => pedido.estado == "pendiente");
            const finalizados = infoDia.eventos.filter((pedido) => pedido.estado == "finalizado");
            // const permisos = infoDia.eventos.filter((pedido) => pedido.estado == "permiso");

            const permisos = infoDia.eventos.filter((pedido) => {
                if (pedido.estado !== "permiso") return false;
                
                // Convert the string date to a Date object at 8:55 AM
                const fechaPermiso = new Date(pedido.end + 'T08:55:00');
                const ahora = new Date();
                
                // Only include permisos where the 8:55 AM datetime has already passed
                return fechaPermiso > ahora;
            });

            console.log("Permisos válidos (antes de las 8:55 AM de su fecha):", permisos);

            // const testPermiso = permisos[0];
            // console.log("Datatype testPermiso", typeof(testPermiso.end));
            // El datatype de permiso.end es stringFecha, a pesar de ser un date
            // convertir a datetime de 9:00 am para evaluar el filtrado apropiado
            

            // if (finalizados.length) {
            //     console.log("finalizados.length true")
            //     $('#finalizados-anchor').css('display','flex');
            //     $('#finalizados-anchor').removeClass('d-none');
            //     $('#finalizados-anchor').addClass('d-flex');
            // } else {
            //     console.log("finalizados.length fakse")
            //     $('#finalizados-anchor').removeClass('d-flex');
            //     $('#finalizados-anchor').addClass('d-none');
            // }

            if (pendientes.length) {
                $('#pendientes-anchor').off('click').on('click', async function (e) {
                    e.preventDefault();
                    console.log("Abriendo menu solicitar permisos dia");
                    construirSelectorPermisos(infoDia, infoMes, pendientes);
                    revelarMenuPedirPermisos();
                });
                $('#pendientes-anchor').removeClass('d-none');
                $('#pendientes-anchor').addClass('d-flex');

                
            } else {
                $('#pendientes-anchor').removeClass('d-flex');
                $('#pendientes-anchor').addClass('d-none');
            }

            if (permisos.length) {
                $('#permisos-anchor').off('click').on('click', async function (e) {
                    e.preventDefault();
                    console.log("Abriendo menu deshacer permisos dia");
                    construirSelectorDeshacer(infoDia, infoMes);
                    revelarMenuDeshacerPermisos();
                });
                $('#permisos-anchor').removeClass('d-none');
                $('#permisos-anchor').addClass('d-flex');
            } else {
                $('#permisos-anchor').removeClass('d-flex');
                $('#permisos-anchor').addClass('d-none');
            }
            
        }

        const construirSelectorPermisos = (infoDia, infoMes, pendPermisibles) => {
            reiniciarBackButton(infoDia, infoMes);
            
            const maxPermisos = pendPermisibles.length;

            $('#mensaje-cantidad-permisos').text(maxPermisos);
            
            const htmlSelector = `
                <div class="d-flex flex-row align-items-center justify-content-between mx-2 mt-2">
                    <div class="stepper rounded-s d-flex flex-row">
                        <a href="#" class="stepper-sub"><i class="fa fa-minus color-theme"></i></a>
                        <input type="number" id="cantidad-permisos" min="1" max="${maxPermisos}" value="${maxPermisos}">
                        <a href="#" class="stepper-add"><i class="fa fa-plus color-theme"></i></a>
                    </div>
                    <button 
                        id="btn-confirmar-permisos"
                        class="py-2 px-3 wrapper font-15 bg-magenta-dark rounded-s line-height-s text-uppercase font-600 shadow-xl"
                    >
                        <span class="d-flex flex-row align-items-center gap-1">Confirmar</span>
                    </button>
                </div>
            `;
            
            // Insert the HTML
            $('#menu-selector-permisos').html(htmlSelector);
            
            const $input = $('#cantidad-permisos');
            const $btnSub = $('.stepper-sub');
            const $btnAdd = $('.stepper-add');
            
            // Handle subtract button
            $btnSub.off('click').on('click', function(e) {
                e.preventDefault();
                
                let currentValue = parseInt($input.val());
                if (currentValue > 1) {
                    $input.val(currentValue - 1);
                }
            });
            
            // Handle add button
            $btnAdd.off('click').on('click', function(e) {
                e.preventDefault();
                
                let currentValue = parseInt($input.val());
                if (currentValue < maxPermisos) {
                    $input.val(currentValue + 1);
                }
            });
            
            // Handle manual input changes
            $input.on('input change', function() {
                let valor = parseInt($(this).val());
                
                // Validate and constrain the value
                if (isNaN(valor) || valor < 1) {
                    valor = 1;
                } else if (valor > maxPermisos) {
                    valor = maxPermisos;
                }
                
                $(this).val(valor);
            });
            
            // Handle submit button click
            $('#btn-confirmar-permisos').off('click').on('click', async function(e) {
                e.preventDefault();
                
                const cantidadPermisos = parseInt($input.val());
                const $btn = $(this);
                const $span = $btn.find('span');
                const textoOriginal = $span.text();
                
                // Validate the input
                if (cantidadPermisos < 1 || cantidadPermisos > maxPermisos) {
                    console.error('Cantidad inválida');
                    return;
                }
                
                // Disable controls while processing
                $btn.prop('disabled', true);
                $input.prop('disabled', true);
                $span.html('<i class="fa fa-spinner fa-spin me-1"></i>Procesando');
                
                try {
                    const response = await PlanesService.asignarPermisosVarios(infoDia.start, cantidadPermisos, {{ $plan->id }});
                    await renderizarCalendario();
                    console.log('Permisos marcados exitosamente:', response.data);
                    ocultarMenusPermisos();
                    ocultarTodosMenus();
                    // Show success message and refresh calendar
                    // ... your success handling logic
                    
                } catch (error) {
                    console.error('Error al marcar permisos:', error);
                    // Show error message
                } finally {
                    // Re-enable everything
                    $btn.prop('disabled', false);
                    $input.prop('disabled', false);
                    $span.text(textoOriginal);
                }
            });
        };

        const construirSelectorDeshacer = (infoDia, infoMes) => {
            reiniciarBackButton(infoDia, infoMes);
        }

        const revelarMenuPedirPermisos = () => {
            ocultarrMenuDiaSinHider();
            $('#menu-pedir-permisos').addClass('menu-active')
        }

        const revelarMenuPermisoSimple = () => {
            // construirMenuPermisoSimple(esPendiente);
            console.log("Revelando menu permiso simple");
            $('.menu-hider').addClass('menu-active');
            $('#menu-permiso-simple').addClass('menu-active');

            
        }

        const revelarMenuDeshacerPermisos = () => {
            ocultarrMenuDiaSinHider();
            $('#menu-deshacer-permisos').addClass('menu-active')
        }

        const revelarMenuDia = (infoDia, infoMes) => {
            construirMenuPedidosDia(infoDia, infoMes);
            $('#menu-pedidos-dia-calendario').addClass('menu-active');
            $('.menu-hider').addClass('menu-active');
        }

        const ocultarrMenuDia = () => {
            $('#menu-pedidos-dia-calendario').removeClass('menu-active');
            $('.menu-hider').removeClass('menu-active');
        }

        const ocultarrMenuDiaSinHider = () => {
            $('#menu-pedidos-dia-calendario').removeClass('menu-active');
            // $('.menu-hider').removeClass('menu-active');
        }

        const ocultarMenusPermisos = () => {
            $('#menu-pedir-permisos').removeClass('menu-active');
            $('#menu-deshacer-permisos').removeClass('menu-active');
        }

        const ocultarTodosMenus = () => {
            $('.menu-active').removeClass('menu-active');
        }

        const reiniciarBackButton = (infoDia, infoMes) => {
            $('.back-menu-pedidos-dia').off('click').on('click', function (e) {
                e.preventDefault();
                ocultarMenusPermisos();
                revelarMenuDia(infoDia, infoMes);

            })
        }
    </script>
@endpush