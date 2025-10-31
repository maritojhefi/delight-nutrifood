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
        <!-- <a href="#" class="cal-disabled">25</a>
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
        <a href="#" data-menu="menu-events">29</a>
        <a href="#" data-menu="menu-events">30</a>
        <a href="#" data-menu="menu-events">31</a>
        <a href="#" class="cal-disabled">1</a>
        <a href="#" class="cal-disabled">2</a>
        <a href="#" class="cal-disabled">3</a>
        <a href="#" class="cal-disabled">4</a>
        <a href="#" class="cal-disabled">5</a>
        <div class="clearfix"></div> -->
    </div>
</div>

@push('modals')
    <div id="menu-pedidos-dia-calendario" class="menu menu-box-modal rounded-m" style="width: 90%; max-width: 320px;">
        <div class="menu-title flex-align-center">
            <p class="color-highlight line-height-xs" style="width: 85%;">{{ $plan->nombre }}</p>
                <h1 id="fecha-menu-control-dia" class="font-20 mt-1 mb-0 fecha-seleccionada">Fecha del día</h1>
            <a href="#" class="close-menu"><i class="fa fa-times-circle"></i></a>
        </div>
        <div class="divider divider-margins my-2"></div>
        <div class="content mt-0">
            <p id="texto-dia-permisos" class="color-theme d-none mb-2">Solicitaste permiso para los pedidos de este día.</p>
            <a id="pendientes-anchor" href="#" class="d-flex gap-3 align-items-center mb-3">
                <div class="align-self-center">
                    <i data-lucide="notebook-pen" class="lucide-icon color-theme" style=" width: 2rem; height: 2rem;"></i>
                </div>
                <div class="align-self-center">
                    <h5 class="">Solicitar Permisos: <span id="contador-pendientes-menu"></span></h5>
                    <p class="mb-0 mt-n1 font-10 line-height-s">
                        <span><i class="fa fa-book color-blue-dark pe-1"></i>Solicitar permisos para mis pedidos del día</span>
                        <!-- <span class="ps-2"><i class="fa fa-user color-green-dark pe-1"></i>25k+ Attending</span> -->
                    </p>
                </div>
                <div class="align-self-center ms-auto">
                    <i class="fa fa-arrow-right pe-2 opacity-30"></i>
                </div>
            </a>
            <!-- <a id="finalizados-anchor" href="#" class="d-flex gap-3 align-items-center mb-3">
                <div class="align-self-center">
                    <i data-lucide="calendar-check" class="lucide-icon color-theme" style=" width: 2rem; height: 2rem;"></i>
                </div>
                <div class="align-self-center">
                    <h5 class="color-orange-dark">Pedidos Finalizados</h5>
                    <p class="mb-0 mt-n1 font-10 line-height-s">
                        <span><i class="fa fa-book color-blue-dark pe-1"></i>Ver el historial de mis pedidos este día</span>
                    </p>
                </div>
                <div class="align-self-center ms-auto">
                    <i class="fa fa-arrow-right pe-2 opacity-30"></i>
                </div>
            </a> -->
            <a id="permisos-anchor" href="#" class="d-flex gap-3 align-items-center mb-3">
                <div class="align-self-center">
                    <i data-lucide="calendar-clock" class="lucide-icon color-theme" style=" width: 2rem; height: 2rem;"></i>
                </div>
                <div class="align-self-center">
                    <h5 class="color-magenta-dark">Retirar Permisos: <span id="contador-permisos-menu"></span></h5>
                    <p class="mb-0 mt-n1 font-10 line-height-s">
                        <span><i class="fa fa-book color-blue-dark pe-1"></i>Retirar permisos para mis pedidos este día</span>
                        <!-- <span class="ps-2"><i class="fa fa-user color-green-dark pe-1"></i>25k+ Attending</span> -->
                    </p>
                </div>
                <div class="align-self-center ms-auto">
                    <i class="fa fa-arrow-right pe-2 opacity-30"></i>
                </div>
            </a>
        </div>
    </div>

    <div id="menu-pedir-permisos" class="menu menu-box-modal rounded-m" style="width: 90%; max-width: 320px">
        <div class="menu-title flex-align-center">
            <a href="#" class="back-menu-pedidos-dia">
                <i class="fa fa-arrow-circle-left"></i>
            </a>
            <p class="color-highlight line-height-xs" style="width: 85%;">{{ $plan->nombre }}</p>
            <h1 id="fecha-menu-control-permisos" class="font-20 mt-1 mb-0 fecha-seleccionada">Fecha del día</h1>
        </div>
        <div class="divider divider-margins my-2"></div>
        <div class="content m-0 px-3">
            <p id="mensaje-permisos" class="mb-0 color-theme"></p>
        </div>
        <div class="d-flex flex-ro mt-2 mb-3 align-items-center justify-content-evenly">
                <div class="stepper rounded-s d-flex flex-row">
                    <a href="#" class="stepper-restar"><i class="fa fa-minus color-theme"></i></a>
                    <input type="number" id="cantidad-permisos" min="1" value="1">
                    <a href="#" class="stepper-agregar"><i class="fa fa-plus color-theme"></i></a>
                </div>
                <button 
                    id="btn-confirmar-permisos"
                    class="py-2 px-2 wrapper font-15 bg-magenta-dark rounded-s line-height-s text-uppercase font-600 shadow-xl"
                >
                    <span class="d-flex flex-row align-items-center gap-1">Confirmar</span>
                </button>
            </div>
    </div>

    <div id="menu-historial-finalizados" class="menu menu-box-modal overflow rounded-m" style="width: 90%; max-width: 320px">
        <div class="menu-title flex-align-center">
            <!-- <a href="#" class="back-menu-pedidos-dia">
                <i data-lucide="chevron-left" class="lucide-icon"></i>
            </a> -->
            <p class="color-highlight line-height-xs" style="width: 85%;">{{ $plan->nombre }}</p>
            <h1 id="fecha-historial-permisos" class="font-20 mt-1 mb-0 fecha-seleccionada">Fecha del día</h1>
            <a href="#" class="close-menu"><i class="fa fa-times-circle"></i></a>
        </div>
        <div class="divider divider-margins my-2"></div>
        <div class="content m-0 px-3">
            <h2 class="color-teal-dark">Historial de pedidos</h2>
            <ul id="lista-historial-finalizado" class=" d-flex flex-column gap-2 list-unstyled overflow-scroll" style="max-height: 50vh;">
                <!-- LISTADO DE PEDIDOS FINALIZADOS EN EL DÍA -->
            </ul>
        </div>
    </div>

    <div id="menu-permiso-simple" class="menu menu-box-modal pb-3 rounded-m overflow-hidden" style="width: 90%; max-width: 320px">
        <div class="menu-title p-3">
            <div class="d-flex flex-row gap-2 align-items-center">
                <i data-lucide="calendar-clock" class="lucide-icon" style="width: 2.5rem; height: 2.5rem;"></i>
                <!-- <div class="d-flex flex-column justify-content-between align-content-center"> -->
                    <!-- <p class="color-highlight font-10 m-0 mt-0">{{ $plan->nombre }}</p> -->
                    <h1 id="titulo-simple" class="font-20 p-0 m-0 line-height-m">Solicitar permiso</h1>
                <!-- </div> -->
            </div>
            <a href="#" class="close-menu"><i data-lucide="x-circle" class="lucide-icon"></i></a>
        </div>
        <div class="content mt-0 mb-3 d-flex flex-column h-100 gap-3">
            <p id="texto-simple" class="pe-3 mb-0 color-theme">                
            </p>
            <div class="d-flex flex-row gap-1 justify-content-evenly mb-0">
                <a href="#" class="close-menu py-2 px-2 font-15 rounded-s text-uppercase bg-delight-red color-white font-600 line-height-s">Cancelar</a>
                <button id="btnConfirmarSimple" data-pedido="" href="#" class="py-2 px-2 font-15 rounded-s text-uppercase bg-highlight font-600 line-height-s">
                    <span class="d-flex flex-row align-items-center gap-1">Confirmar</span>
                </button>
            </div>
        </div>
    </div>
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
            await renderizarCalendario();
        });

        const renderizarCalendario = async (fechaSeleccionada = null) => {
            await PlanesService.obtenerCalendarioPlan({{ $plan->id }}, {{ $usuario->id }}).then(
                (respuestaCalendario) => {
                    // console.log("Respuesta obtenida sobre la informacion del plan: ", respuestaCalendario.data);
                    // console.log("Respuesta servida desde la cache: ", respuestaCalendario.cached);

                    // Obtener respuestas separadas divididas por meses en orden
                    const mesesPlan = respuestaCalendario.data.meses;
                    
                    let infoMesActual;
                    
                    if (fechaSeleccionada) {
                        // Extraer el mes de la fecha seleccionada (formato: "YYYY-MM-DD")
                        const mesSeleccionado = parseInt(fechaSeleccionada.split('-')[1]);
                        const anioSeleccionado = parseInt(fechaSeleccionada.split('-')[0]);
                        
                        // Buscar el mes correspondiente
                        infoMesActual = mesesPlan.find((mes) => 
                            mes.numero === mesSeleccionado && mes.anio === anioSeleccionado
                        );
                        
                        // // console.log(`Buscando mes ${mesSeleccionado}/${anioSeleccionado} para fecha: ${fechaSeleccionada}`);
                    } else {
                        // Comportamiento por defecto: usar el mes actual
                        infoMesActual = mesesPlan.find((mes) => mes.currentDayFlag);
                    }
                    
                    // console.log("info meses plan", mesesPlan);
                    // console.log("mes seleccionado", infoMesActual);
                    
                    if (infoMesActual) {
                        construirCalendario(infoMesActual, mesesPlan);
                    } else if (fechaSeleccionada) {
                        console.warn(`No se encontró información para la fecha: ${fechaSeleccionada}`);
                    }
                }
            );
        }

        const construirCalendario = (infoMes, infoMeses) => {
            // console.log("Construyendo calendario para: ", infoMes);
            
            // Actualizar el titulo del mes
            $('#mes-calendario').text(`${infoMes.nombre} ${infoMes.anio}`);

            // Actualizar botones
            const mesAnterior = infoMes.numero === 1 ? 12 : infoMes.numero - 1;
            const anioAnterior = infoMes.numero === 1 ? infoMes.anio - 1 : infoMes.anio;
            const mesSiguiente = infoMes.numero === 12 ? 1 : infoMes.numero + 1;
            const anioSiguiente = infoMes.numero === 12 ? infoMes.anio + 1 : infoMes.anio;

            $('#mes-anterior-btn').data('mes-anterior', mesAnterior).data('anio', anioAnterior);
            $('#mes-siguiente-btn').data('mes-siguiente', mesSiguiente).data('anio', anioSiguiente);

            // Constantes reutilizables
            const FONT_CLASS = 'font-18';
            const ROUNDED_CLASS = 'rounded-xs';
            const LINE_HEIGHT_CLASS = 'line-height-xs';
            const BG_COLORS = {
                pendiente: 'bg-green-dark',
                permiso: 'bg-magenta-dark',
                finalizado: 'bg-orange-dark'
            };

            // Mapear dias con planes
            const diasDisponibles = {};
            infoMes.dias.forEach(dia => {
                diasDisponibles[dia.start] = dia;
            });

            // Obtener mes y año
            const year = infoMes.anio;
            const month = infoMes.numero; // 1-12

            // Primer y ultimo dia del mes
            const primerDia = new Date(year, month - 1, 1);
            const ultimoDia = new Date(year, month, 0);

            // Calcular el primer dia de la semana del mes (0 = Sunday, 6 = Saturday)
            const primerDiaSemana = primerDia.getDay();

            // Calcular cuantos dias se mostraran de meses adyacentes
            const diasMesAnterior = primerDiaSemana;
            const ultimoDiaSemana = ultimoDia.getDay();
            const diasMesSiguiente = ultimoDiaSemana === 6 ? 0 : 6 - ultimoDiaSemana;

            // Funciones auxiliares
            const formatFecha = (year, month, dia) => 
                `${year}-${String(month).padStart(2, '0')}-${String(dia).padStart(2, '0')}`;

            const esHoy = (dia) => {
                const hoy = new Date();
                return hoy.getDate() === dia && 
                    hoy.getMonth() === (month - 1) && 
                    hoy.getFullYear() === year;
            };

            const crearBotonCalendario = (dia, bgColor = '', badgeHTML = '') => {
                const clases = `${FONT_CLASS} ${bgColor} position-relative ${ROUNDED_CLASS} ${LINE_HEIGHT_CLASS}`.trim();
                return `<button class="${clases}">${dia} ${badgeHTML}</button>`;
            };

            const crearEnlaceCalendario = (dia, clases = '', dataFecha = '', contenido = '') => {
                const dataAttr = dataFecha ? `data-fecha="${dataFecha}"` : '';
                return `<a href="#" class="${clases}" ${dataAttr}>${contenido}</a>`;
            };

            const contarEventosPorEstado = (eventos, estado) => 
                (eventos || []).filter(evento => evento.estado === estado).length;

            const construirBadgeContainer = (numPlanes, numPermisos, numPendientes) => {
                if (numPermisos >= 1 && numPermisos < numPlanes) {
                    return `
                        <div class="d-flex flex-row align-items-center position-absolute top-0 end-0" style="transform: translate(40%, -50%);">
                            ${construirBadgesContadorPermiso(numPendientes, false)}
                            ${construirBadgesContadorPermiso(numPermisos, true)}
                        </div>
                    `;
                }
                return `
                    <div class="position-absolute top-0 end-0" style="transform: translate(50%, -50%);">
                        ${construirBadgesContadorPermiso(numPlanes, false)}
                    </div>
                `;
            };

            const determinarClaseAccionSimple = (diaInfo) => {
                if (!diaInfo.eventos || diaInfo.eventos.length !== 1) return '';
                
                const evento = diaInfo.eventos[0];
                const fechaPedido = new Date(diaInfo.end + 'T08:55:00');
                const ahora = new Date();
                const habilitadoAccion = fechaPedido > ahora;
                
                if (!habilitadoAccion) return '';
                
                if (evento.estado === "pendiente") return 'pedir-permiso-simple';
                if (evento.estado === "permiso") return `deshacer-menu-${evento.id}`;
                
                return '';
            };

            // Construir las grillas del calendario
            const calendarHTML = [];

            // Dias de mes anterior
            const fechaMesAnterior = new Date(year, month - 1, 0);
            const ultimoDiaMesAnterior = fechaMesAnterior.getDate();

            for (let i = diasMesAnterior - 1; i >= 0; i--) {
                const dia = ultimoDiaMesAnterior - i;
                calendarHTML.push(crearEnlaceCalendario(dia, `cal-disabled ${FONT_CLASS}`, '', dia));
            }

            // Agregar los dias del mes actual
            const totalDiasMes = ultimoDia.getDate();

            for (let dia = 1; dia <= totalDiasMes; dia++) {
                const fecha = formatFecha(year, month, dia);
                const diaInfo = diasDisponibles[fecha];
                
                if (diaInfo) {
                    // Contar eventos por estado
                    const numPlanesDia = diaInfo.eventos ? diaInfo.eventos.length : 0;
                    const numPermisosDia = contarEventosPorEstado(diaInfo.eventos, 'permiso');
                    const numPendientesDia = contarEventosPorEstado(diaInfo.eventos, 'pendiente');
                    
                    // Determinar clases de acción
                    // const claseAccion = numPlanesDia > 1 ? 'cal-menu-opener' : 'cal-disabled-menu';
                    // const claseAccion = numPlanesDia > 1 ? diaInfo.tipo == "finalizado" ? 'historial-dia':'cal-menu-opener' : 'cal-disabled-menu';

                    const claseAccion = diaInfo.tipo == "finalizado" ? 'historial-dia' : (numPlanesDia > 1 ? 'cal-menu-opener' : 'cal-disabled-menu' );

                    const claseAccionSimple = numPlanesDia === 1 ? determinarClaseAccionSimple(diaInfo) : '';
                    
                    // Construir badge container
                    let badgeContainerHTML = diaInfo.tipo === 'finalizado' 
                        ? '' 
                        : construirBadgeContainer(numPlanesDia, numPermisosDia, numPendientesDia);
                    
                    // Determinar color de fondo
                    const bgColor = BG_COLORS[diaInfo.tipo] || '';
                    
                    // Construir HTML según el tipo de día
                    if (diaInfo.tipo === 'feriado') {
                        // Feriado
                        const boton = crearBotonCalendario(dia, 'bg-red-dark');
                        calendarHTML.push(crearEnlaceCalendario('', 'cal-feriado', fecha, boton)
                            .replace('class="cal-feriado"', 'class="cal-feriado" data-menu="menu-events"'));
                    } else {
                        // Dia con plan
                        const claseContainer = esHoy(dia) 
                            ? `cal-selected overflow-visible ${claseAccion} ${claseAccionSimple}`.trim()
                            : `overflow-visible ${claseAccion} ${claseAccionSimple}`.trim();
                        
                        const boton = crearBotonCalendario(dia, bgColor, badgeContainerHTML);
                        calendarHTML.push(crearEnlaceCalendario('', claseContainer, fecha, boton));
                    }
                } else {
                    // Dias sin planes
                    if (esHoy(dia)) {
                        // Dia actual sin eventos
                        calendarHTML.push(`
                            <a href="#" data-fecha="${fecha}">
                                <button>
                                    <span class="${FONT_CLASS}">${dia}</span>
                                </button>
                            </a>
                        `);
                    } else {
                        // Dia cualquiera sin plan
                        calendarHTML.push(crearEnlaceCalendario(dia, FONT_CLASS, fecha, dia));
                    }
                }
            }

            // Dias del siguiente mes
            for (let dia = 1; dia <= diasMesSiguiente; dia++) {
                calendarHTML.push(crearEnlaceCalendario(dia, 'cal-disabled', '', dia));
            }
            
            // Agregar clearfix
            calendarHTML.push('<div class="clearfix"></div>');

            // const finalizados = diaInfo.eventos.filter((pedido) => pedido.estado == "finalizado");
            
            // Actualizar elementos del calendario
            $('#fechas-calendario-plan').html(calendarHTML.join(''));

            $('#mes-anterior-btn').off('click').on('click', function (e) {
                e.preventDefault();
                const mesAnterior = $(this).data("mes-anterior");
                const anioAnterior = $(this).data("anio");
                const nuevoMes = infoMeses.filter((mes) => mes.numero == mesAnterior && mes.anio == anioAnterior);
                
                if (nuevoMes.length) {
                    construirCalendario(nuevoMes[0], infoMeses);
                }
            });

            $('#mes-siguiente-btn').off('click').on('click', function (e) {
                e.preventDefault();
                const mesSiguiente = $(this).data("mes-siguiente");
                const anioSiguiente = $(this).data("anio");

                const nuevoMes = infoMeses.filter((mes) => mes.numero == mesSiguiente && mes.anio == anioSiguiente);
                
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
                    // // console.log('Día seleccionado:', fecha, diaInfo);

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
                // // console.log("Diainfo para evento con permiso simple: ", diaInfo);
                switch (diaInfo.eventos[0].estado) {
                    case "pendiente":
                        // // console.log("Es pendiente");
                        construirMenuPermisoSimple(true, diaInfo, infoMes);
                        revelarMenuPermisoSimple();
                        break;
                    case "permiso":
                        // // console.log("Es permiso");
                        construirMenuPermisoSimple(false, diaInfo, infoMes);
                        revelarMenuPermisoSimple();
                        break;
                    default:
                        break;
                }
            });

            $('.historial-dia').off('click').on('click', async function (e) {
                e.preventDefault();                  
                const fecha = $(this).data('fecha');
                const diaInfo = diasDisponibles[fecha];
                const finalizados = diaInfo.eventos.filter((pedido) => pedido.estado == "finalizado");
                construirListadoFinalizados(finalizados, diaInfo, infoMes);
                revelarMenuFinalizados();
            });
        }

        const construirBadgesContadorPermiso = (contador, esPermiso) => {
            const propiedades = {
                "bgColor": esPermiso ? "bg-magenta-dark" : "bg-theme bg-dtheme-blue" ,
                "textColor": esPermiso ? "color-white" : "color-theme",
            }

            return `
                <div class="badge border d-flex align-items-center justify-content-center p-0 ${propiedades.bgColor}  ${propiedades.textColor}"
                    style="width: 0.95rem !important; height: 0.95rem !important">
                    <span class="${propiedades.textColor}" style="font-size: 0.7rem">${contador}</span>
                </div>
            `

        }

        const construirMenuPermisoSimple = (esPendiente, infoDia, infoMes) => {
             // CAMBIAR LA FECHA DEL MODAL
            const stringFecha = `${infoDia.start}T00:00:00Z`;
            const dateObj = new Date(stringFecha);
            const dia = dateObj.getUTCDate();
            const tituloSimple = $('#titulo-simple');
            const textoSimple = $('#texto-simple'); // elemento <p>, incluir html con span para modificarlo
            const botonConfirmacion = $('#btnConfirmarSimple');

            botonConfirmacion.data('pedido', infoDia.eventos[0].id);
            $('.fecha-seleccionada').text(`${dia} de ${infoMes.nombre}  de ${infoMes.anio}`);
            // Asignar funcionalidad al boton de confirmación
            if (esPendiente) {
                tituloSimple.text("Solicitar Permiso");
                // textoSimple.text(`¿Deseas pedir permiso para la fecha ${dia} de ${infoMes.nombre}  de ${infoMes.anio}?`);
                textoSimple.html(`¿Deseas pedir permiso para la fecha <strong>${dia} de ${infoMes.nombre}  de ${infoMes.anio}</strong>?`);
                $('#btnConfirmarSimple').off('click').on('click', async function(e) {
                    e.preventDefault();
                    
                    const cantidadPermisos = 1;
                    const $btn = $(this);
                    const $span = $btn.find('span');
                    const textoOriginal = $span.text();
                    
                    // Deshabilitar mientras se procesa
                    botonConfirmacion.prop('disabled', true);
                    $span.html('<i class="fa fa-spinner fa-spin me-1"></i>Procesando');
                    
                    try {
                        const response = await PlanesService.asignarPermisosVarios(infoDia.start, cantidadPermisos, {{ $plan->id }});
                        mostrarToastSuccess("Se asignó el permiso solicitado");
                        await renderizarCalendario(infoDia.start);
                        // console.log('Permisos marcados exitosamente:', response.data);
                        ocultarMenusPermisos();
                        ocultarTodosMenus();
                        
                    } catch (error) {
                        console.error('Error al marcar permisos:', error);
                        if (error.response.data.error) {
                            mostrarToastError(error.response.data.error);
                        }
                        else {
                            mostrarToastError("Ocurrió un error con los permisos");
                        }
                        await new Promise(resolve => setTimeout(resolve, 3000));
                        window.location.reload();
                    } finally {
                        // Re-habilitar
                        $btn.prop('disabled', false);
                        $span.text(textoOriginal);
                    }
                });
            } else {
                tituloSimple.text("Deshacer Permiso");
                // textoSimple.text(`¿Deseas retirar el permiso para la fecha ${dia} de ${infoMes.nombre}  de ${infoMes.anio}?`);
                textoSimple.html(`¿Deseas retirar el permiso para la fecha <strong>${dia} de ${infoMes.nombre}  de ${infoMes.anio}</strong>?`);

                $('#btnConfirmarSimple').off('click').on('click', async function(e) {
                    e.preventDefault();
                    
                    const cantidadPermisos = 1;
                    const $btn = $(this);
                    const $span = $btn.find('span');
                    const textoOriginal = $span.text();
                    
                    // Deshabilitar mientras se procesa
                    botonConfirmacion.prop('disabled', true);
                    $span.html('<i class="fa fa-spinner fa-spin me-1"></i>Procesando');
                    
                    try {
                        const response = await PlanesService.deshacerPermisosVarios(infoDia.start, cantidadPermisos, {{ $plan->id }});
                        mostrarToastSuccess("Se retiró el permiso solicitado");
                        await renderizarCalendario(infoDia.start);
                        // console.log('Permisos retirados exitosamente:', response.data);
                        ocultarMenusPermisos();
                        ocultarTodosMenus();
                        
                    } catch (error) {
                        console.error('Error al retirar permisos:', error);
                        mostrarToastError(error.response.data.error);
                        await new Promise(resolve => setTimeout(resolve, 3000));
                        window.location.reload();
                    } finally {
                        // Re-habilitar
                        $btn.prop('disabled', false);
                        $span.text(textoOriginal);
                    }
                });
            }
        }

        const construirMenuPedidosDia = (infoDia, infoMes) => {
            // // console.log("Informacion para construir el modal:", infoDia);
            // // console.log("Informacion del mes pal modal", infoMes);

            // CAMBIAR LA FECHA DEL MODAL
            const stringFecha = `${infoDia.start}T00:00:00Z`;
            const dateObj = new Date(stringFecha);
            const dia = dateObj.getUTCDate();
            $('.fecha-seleccionada').text(`${dia} de ${infoMes.nombre}  de ${infoMes.anio}`);

            // Agrupar pedidos
            const pendientes = infoDia.eventos.filter((pedido) => pedido.estado == "pendiente");
            const permisos = infoDia.eventos.filter((pedido) => {
                if (pedido.estado !== "permiso") return false;

                const fechaSeleccionada = new Date(infoDia.start + "T00:00:00");
                const fechaHoy = new Date();

                // Normalizar los inicios de hoy y la fecha seleccionada
                const inicioSeleccionado = new Date(fechaSeleccionada.setHours(0, 0, 0, 0));
                const inicioDeHoy = new Date(fechaHoy.setHours(0, 0, 0, 0));

                // Rechazar fechas pasadas
                if (inicioSeleccionado < inicioDeHoy) return false;

                // Rechazar la fecha de hoy si excede las 9:00 AM
                const horaActual = new Date().getHours();
                if (inicioSeleccionado.getTime() === inicioDeHoy.getTime() && horaActual >= 9) {
                    return false;
                }

                // Retornar permisos validos
                return true;
            });

            $('#contador-pendientes-menu').text(pendientes.length);
            $('#contador-permisos-menu').text(permisos.length);

            if (!pendientes.length && !permisos.length) {
                $('#texto-dia-permisos').removeClass('d-none');
                $('#texto-dia-permisos').addClass('d-block');
            } else {
                $('#texto-dia-permisos').removeClass('d-block');
                $('#texto-dia-permisos').addClass('d-none');
            }

            if (pendientes.length) {
                $('#pendientes-anchor').off('click').on('click', async function (e) {
                    e.preventDefault();
                    
                    construirSelectorPermisos(infoDia, infoMes, pendientes, {
                        mensaje: 'Tienes {cantidad} pedidos disponibles para marcar con permiso este día.',
                        accion: async (fecha, cantidad) => {
                            return await PlanesService.asignarPermisosVarios(fecha, cantidad, {{ $plan->id }});
                        },
                        mensajeExito: 'Se asignaron los permisos solicitados.',
                        mensajeError: 'Error al marcar permisos'
                    });
                    revelarMenuPermisos();
                });
                $('#pendientes-anchor').removeClass('d-none').addClass('d-flex');
            } else {
                $('#pendientes-anchor').removeClass('d-flex').addClass('d-none');
            }
            if (permisos.length) {
                $('#permisos-anchor').off('click').on('click', async function (e) {
                    e.preventDefault();                    
                    construirSelectorPermisos(infoDia, infoMes, permisos, {
                        mensaje: 'Tienes {cantidad} permisos asignados que puedes deshacer este día.',
                        accion: async (fecha, cantidad) => {
                            return await PlanesService.deshacerPermisosVarios(fecha, cantidad, {{ $plan->id }});
                        },
                        mensajeExito: 'Se deshicieron los permisos solicitados.',
                        mensajeError: 'Error al deshacer permisos'
                    });
                    revelarMenuPermisos();
                });
                $('#permisos-anchor').removeClass('d-none').addClass('d-flex');
            } else {
                $('#permisos-anchor').removeClass('d-flex').addClass('d-none');
            }
        }

const menuItems = {
    "SOPA": {
        "label": "Sopa",
        "icon": "soup",
        "color": "color-yellow-dark"
    },
    "PLATO": {
        "label": "Principal",
        "icon": "utensils-crossed",
        "color": "color-delight-red"
    },
    "CARBOHIDRATO": {
        "label": "Carbohidrato",
        "icon": "sprout",
        "color": "color-green-dark"
    },
    "ENSALADA": {
        "label": "Ensalada",
        "icon": "salad",
        "color": "color-teal-dark"
    },
    "JUGO": {
        "label": "Jugo",
        "icon": "glass-water",
        "color": "color-blue-light"
    },
    "EMPAQUE": {
        "label": "Empaque",
        "icon": "package-2",
        "color": "color-gray-dark"
    },
    "ENVIO": {
        "label": "Envío",
        "icon": "truck",
        "color": "color-gray-dark"
    }
};

const construirListadoFinalizados = (finalizados, infoDia, infoMes) => {
    // CAMBIAR LA FECHA DEL MODAL
    const stringFecha = `${infoDia.start}T00:00:00Z`;
    const dateObj = new Date(stringFecha);
    const dia = dateObj.getUTCDate();
    $('#fecha-historial-permisos').text(`${dia} de ${infoMes.nombre}  de ${infoMes.anio}`);
    const contenedorListado = $('#lista-historial-finalizado');
    let listadoHTML = '';

    finalizados.forEach((pedido, index) => {
        listadoHTML += construirDetalleFinalizado(index + 1, pedido);
    });

    contenedorListado.html(listadoHTML);
    
    reinitializeLucideIcons();
};

const construirDetalleFinalizado = (indice, pedido) => {
    // Check if detalle is null or undefined
    if (!pedido.detalle) {
        return `
            <li>
                <h3 class="font-18">Pedido ${indice}</h3>
                <div class="d-flex flex-column align-items-center">
                    <div class="row m-0">
                        <div class="col-12 px-0 py-2">
                            <p class="text-center color-gray-dark font-14 mb-0">
                                No hay detalles disponibles para este pedido
                            </p>
                        </div>
                    </div>
                </div>
            </li>
        `;
    }
    
    let detalleDecoded;
    console.log("Pedido a listarse:", pedido);
    try {
        detalleDecoded = typeof pedido.detalle === 'string' 
            ? JSON.parse(pedido.detalle) 
            : pedido.detalle;
    } catch (e) {
        console.error("Error parseando detalle:", e);
        detalleDecoded = {};
    }
    
    let validItemCount = 0;
    for (const plato in detalleDecoded) {
        const valor = detalleDecoded[plato];
        if (valor && valor !== '' && menuItems[plato]) {
            validItemCount++;
        }
    }
    
    // If no valid items after parsing, show no details message
    if (validItemCount === 0) {
        return `
            <li>
                <h3 class="font-18">Pedido ${indice}</h3>
                <div class="d-flex flex-column align-items-center">
                    <div class="row m-0">
                        <div class="col-12 px-0 py-2">
                            <p class="text-center color-gray-dark font-14 mb-0">
                                No hay detalles disponibles para este pedido
                            </p>
                        </div>
                    </div>
                </div>
            </li>
        `;
    }
    
    // Build menu items HTML
    let menuItemsHTML = '';
    for (const plato in detalleDecoded) {
        const valor = detalleDecoded[plato];
        
        // Only render if value exists and plato is in menuItems
        if (valor && valor !== '' && menuItems[plato]) {
            const item = menuItems[plato];
            const valorCapitalizado = valor.charAt(0).toUpperCase() + valor.slice(1);
            
            menuItemsHTML += `
                <div class="col-6 px-0 py-1">
                    <div class="d-flex flex-row gap-2 align-items-center">
                        <i data-lucide="${item.icon}" class="lucide-icon ${item.color}" style="min-height: 1.8rem; min-width: 1.8rem"></i>
                        <div class="d-flex flex-column">
                            <span class="item-value line-height-xs font-12 m-0 color-theme">
                                ${valorCapitalizado}
                            </span>
                        </div>
                    </div>
                </div>
            `;
        }
    }
    
    return `
        <li>
            <h3 class="font-18">Pedido ${indice}</h3>
            <div class="d-flex flex-column align-items-center">
                <div class="row m-0">
                    ${menuItemsHTML}
                </div>
            </div>
        </li>
    `;
};

        const construirSelectorPermisos = (infoDia, infoMes, items, config) => {
            reiniciarBackButton(infoDia, infoMes);
            
            const maxItems = items.length;
            
            // Actualizar mensaje
            $('#mensaje-permisos').html(config.mensaje.replace('{cantidad}', `<span class="font-700">${maxItems}</span>`));
            
            const $input = $('#cantidad-permisos');
            const $btnSub = $('.stepper-restar');
            const $btnAdd = $('.stepper-agregar');
            const $btnConfirmar = $('#btn-confirmar-permisos');
            
            // Establecer minimos y máximos
            $input.attr('min', 1);
            $input.attr('max', maxItems);
            $input.val(maxItems);
            
            // Control botón reducir
            $btnSub.off('click').on('click', function(e) {
                e.preventDefault();
                let currentValue = parseInt($input.val());
                if (currentValue > 1) {
                    $input.val(currentValue - 1);
                }
            });
            
            // Control botón incrementar
            $btnAdd.off('click').on('click', function(e) {
                e.preventDefault();
                let currentValue = parseInt($input.val());
                if (currentValue < maxItems) {
                    $input.val(currentValue + 1);
                }
            });
            
            // Control de cambios en input
            $input.on('input change', function() {
                let valor = parseInt($(this).val());
                
                // Validar y limitar valores seleccionables
                if (isNaN(valor) || valor < 1) {
                    valor = 1;
                } else if (valor > maxItems) {
                    valor = maxItems;
                }
                
                $(this).val(valor);
            });
            
            // Controlar submit y funcionalidad del botón confirmación
            $btnConfirmar.off('click').on('click', async function(e) {
                e.preventDefault();
                
                const cantidad = parseInt($input.val());
                const $btn = $(this);
                const $span = $btn.find('span');
                const textoOriginal = $span.text();
                
                // Validar el input de cantidad
                if (cantidad < 1 || cantidad > maxItems) {
                    console.error('Cantidad inválida');
                    mostrarToastError('La cantidad seleccionada es inválida')
                    return;
                }
                
                // Deshabilitar controles hasta terminar la solicitud
                $btn.prop('disabled', true);
                $input.prop('disabled', true);
                $btnSub.css('pointer-events', 'none');
                $btnAdd.css('pointer-events', 'none');
                $span.html('<i class="fa fa-spinner fa-spin me-1"></i>Procesando');
                
                try {
                    await config.accion(infoDia.start, cantidad);
                    mostrarToastSuccess(config.mensajeExito);
                    await renderizarCalendario(infoDia.start);
                    
                    ocultarMenusPermisos();
                    ocultarTodosMenus();
                    await new Promise(resolve => setTimeout(resolve, 3000));
                    window.location.reload();
                } 
                catch (error) {
                    console.error(config.mensajeError, error);
                    mostrarToastError("Ocurrió un error con el permiso");
                    await new Promise(resolve => setTimeout(resolve, 3000));
                    window.location.reload();
                }
                finally {
                    $btn.prop('disabled', false);
                    $input.prop('disabled', false);
                    $btnSub.css('pointer-events', '');
                    $btnAdd.css('pointer-events', '');
                    $span.text(textoOriginal);
                }
            });
        };

        const revelarMenuPermisos = () => {
            ocultarrMenuDiaSinHider();
            $('#menu-pedir-permisos').addClass('menu-active')
        }

        const revelarMenuFinalizados = () => {
            $('#menu-historial-finalizados').addClass('menu-active'); 
            $('.menu-hider').addClass('menu-active');
        }

        const revelarMenuPermisoSimple = () => {
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
        }

        const ocultarMenusPermisos = () => {
            $('#menu-pedir-permisos').removeClass('menu-active');
            $('#menu-deshacer-permisos').removeClass('menu-active');
            $('#menu-historial-finalizados').removeClass('menu-active');
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