<div class="deznav">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">
            {{-- Cada bloque muestra solo las opciones permitidas al rol (config/permisos.php, Usuarios > Permisos) --}}
            {{-- El admin tiene todo, pero el bloque Cocina repite opciones de Produccion: se le oculta como antes --}}
            @if (!\App\Helpers\PermisoHelper::esAdmin())
                @php
                    $areasDespachoCocina = \App\Models\AreaDespacho::activos()
                        ->where('codigo_area', '!=', 'cocina')
                        ->get();
                    
                    $listaCocina = [
                        'Despachar pedidos' => 'reporte.cocina',
                        'Agregar stock' => 'sucursal.stock',
                    ];
                    
                    // Agregar dinámicamente las áreas de despacho (excepto Cocina)
                    foreach ($areasDespachoCocina as $area) {
                        $listaCocina[$area->nombre_area] = [
                            'ruta' => 'areapanel.index',
                            'params' => ['area' => $area->codigo_area]
                        ];
                    }
                @endphp
                <x-sidebar-elements titulo="Cocina" linkglobal="cocina" modulo="cocina" :lista="$listaCocina">
                    <i class="flaticon-025-dashboard"></i>
                </x-sidebar-elements>
            @endif

                <x-sidebar-elements titulo="Inicio" linkglobal="admin/inicio" modulo="inicio" :lista="['Resumen de hoy' => 'caja.diaria']">
                    <i class="flaticon-025-dashboard"></i>
                </x-sidebar-elements>

                <x-sidebar-elements titulo="Estadisticas" linkglobal="admin/caja" modulo="estadisticas" :lista="['Reporte Diario' => 'caja.reportes.v2', 'Reporte Mensual' => 'caja.reporte.mensual']">
                    <i class="flaticon-041-graph"></i>
                </x-sidebar-elements>

                {{-- <x-sidebar-elements titulo="Sucursales" linkglobal="admin/sucursales" :lista="['Todas' => 'sucursal.listar', 'Agregar Stock' => 'sucursal.stock']">
                    <i class="flaticon-086-star"></i>
                </x-sidebar-elements> --}}
                <x-sidebar-elements titulo="Nutri-Tips" linkglobal="admin/tienda" modulo="nutritips" :lista="[
                    'Galeria de fotos' => 'tienda.galeria',
                    'Videos' => 'tienda.tutoriales',
                    'Novedades' => 'tienda.novedades',
                ]">
                    <i class="fa fa-camera"></i>
                </x-sidebar-elements>

                <x-sidebar-elements titulo="Gestión de puntos" linkglobal="admin/puntos" modulo="puntos" :lista="[
                    'Perfiles' => 'perfiles.index',
                    'Registros Históricos' => 'registros.index',
                ]">
                    <i class="flaticon-064-pin"></i>
                </x-sidebar-elements>
            <x-sidebar-elements titulo="Productos" linkglobal="admin/productos" modulo="productos" :lista="[
                'Listar Productos' => 'producto.listar',
                'Crear Nuevo' => 'producto.crear',
                'Almacen y stock' => 'producto.expiracion',
                'Tags' => 'producto.tags',
                'Categorias' => 'producto.categoria',
                'Subcategorias' => 'producto.subcategoria',
                'Adicionales' => 'producto.adicionales',
                'Agregar adicional a subcategoria' => 'producto.agregar.adicional',
            ]">
                <i class="flaticon-043-menu"></i>
            </x-sidebar-elements>
                {{-- Permisos: solo admin, no forma parte del catálogo asignable --}}
                <x-sidebar-elements titulo="Usuarios" linkglobal="admin/usuarios" modulo="usuarios" :lista="array_merge([
                    'Listar Usuarios' => 'usuario.listar',
                    'Roles' => 'usuario.roles',
                    'Colaboradores' => 'usuario.empleo',
                    'Billeteras y saldos' => 'usuario.saldos',
                    'Asistencia' => 'usuario.asistencia',
                    'Cumpleaños' => 'usuario.cumpleanos',
                ], \App\Helpers\PermisoHelper::esAdmin() ? ['Permisos' => 'usuario.permisos'] : [])">
                    <i class="flaticon-381-user"></i>
                </x-sidebar-elements>



                @php
                    $areasDespacho = \App\Models\AreaDespacho::activos()
                        ->where('codigo_area', '!=', 'cocina')
                        ->get();
                    
                    $areaCocina = \App\Models\AreaDespacho::where('codigo_area', 'cocina')
                        ->where('activo', true)
                        ->first();
                    
                    $listaAlmuerzos = [
                        'Personalizar menú semanal' => 'almuerzos.listar',
                        'Reporte Diario' => 'almuerzos.reporte',
                        // 'Reporte Semanal' => 'reporte.semana',
                        'Planes' => 'crear.plan',
                        'Agregar Plan a Usuario' => 'planes',
                        'Planes por expirar' => 'planes.expirar',
                    ];
                    
                    // Agregar Cocina solo si está activa
                    if ($areaCocina) {
                        $listaAlmuerzos['Cocina'] = 'reporte.cocina';
                    }
                    
                    // Agregar dinámicamente las áreas de despacho (excepto Cocina)
                    foreach ($areasDespacho as $area) {
                        $listaAlmuerzos[$area->nombre_area] = [
                            'ruta' => 'areapanel.index',
                            'params' => ['area' => $area->codigo_area]
                        ];
                    }
                    
                    $listaAlmuerzos['Planes en desarrollo(whatsapp)'] = 'reporte.whatsapp';
                @endphp
                <x-sidebar-elements titulo="Produccion" linkglobal="admin/almuerzos" modulo="produccion" :lista="$listaAlmuerzos">
                    <i class="flaticon-381-photo"></i>
                </x-sidebar-elements>

                <x-sidebar-elements titulo="Ventas" linkglobal="admin/ventas" modulo="ventas" :lista="[
                    'Ventas diarias' => 'ventas.listar',
                    'Prospectos de Clientes' => 'ventas.prospectos',
                    'Mesas' => 'ventas.mesas',
                ]">
                    <i class="flaticon-013-checkmark"></i>
                </x-sidebar-elements>
                <x-sidebar-elements titulo="Convenios" linkglobal="admin/convenios" modulo="convenios" :lista="[
                    'Crear' => 'convenio.index',
                    'Vincular Usuario' => 'convenio.vincular.usuario',
                ]">
                    <i class="flaticon-381-panel"></i>
                </x-sidebar-elements>
                <x-sidebar-elements titulo="Perifericos" linkglobal="admin/perifericos" modulo="perifericos" :lista="['Impresoras' => 'impresoras.index']">
                    <i class="flaticon-072-printer"></i>
                </x-sidebar-elements>



                <x-sidebar-elements titulo="Otros" linkglobal="admin/otros" modulo="otros" :lista="[
                    'Importar excel' => 'importar.index',
                    'Historial enviados' => 'whatsapp.historial',
                    'Tickets whatsapp' => 'whatsapp.index',
                ]">
                    <i class="flaticon-022-copy"></i>
                </x-sidebar-elements>

                <x-sidebar-elements titulo="Configuraciones" linkglobal="admin/Configuraciones" modulo="configuraciones" :lista="['Sistema' => 'sistema.index']">
                    <i class="fa fa-gears"></i>
                </x-sidebar-elements>

                <x-sidebar-elements-simple titulo="Horarios" linkglobal="admin/Horarios" url="index.horarios" modulo="horarios">
                    <i class="fa fa-clock-o"></i>
                </x-sidebar-elements-simple>

        </ul>


        <div class="plus-box" style="padding:20px 20px 30px">
            <p class="fs-16 font-w300 mb-4">Estas en el panel principal!</p>
            <a class="text-white fs-14" href="javascript:void(0);">Eres parte del equipo
                {{ strtoupper(GlobalHelper::getValorAtributoSetting('nombre_sistema')) }}!</a>
        </div>
        <div class="copyright">
            <p><strong>{{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }}</strong> © {{ date('Y') }}
                Todos los derechos reservados</p>
            <p class="fs-12">Hecho con <span class="heart"></span> por Macrobyte</p>
        </div>
    </div>
</div>
