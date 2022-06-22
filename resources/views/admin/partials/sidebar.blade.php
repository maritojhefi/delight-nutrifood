<div class="deznav">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">
            @if (auth()->user()->role->nombre == 'cocina')
                <x-sidebar-elements titulo="Cocina" linkglobal="cocina" :lista="[
                    'Agregar stock' => 'sucursal.stock',
                    'Personalizar dias' => 'almuerzos.listar',
                    'Reporte Diario' => 'almuerzos.reporte',
                    'Reporte Semanal' => 'reporte.semana',
                ]">
                    <i class="flaticon-025-dashboard"></i>
                </x-sidebar-elements>
            @endif

            @if (auth()->user()->role->nombre == 'admin')
                <x-sidebar-elements titulo="Inicio" linkglobal="admin/inicio" :lista="['Resumen de hoy' => 'caja.diaria']">
                    <i class="flaticon-025-dashboard"></i>
                </x-sidebar-elements>

                <x-sidebar-elements titulo="Estadisticas" linkglobal="admin/caja" :lista="['Todas las ventas' => 'caja.reportes']">
                    <i class="flaticon-041-graph"></i>
                </x-sidebar-elements>

                <x-sidebar-elements titulo="Sucursales" linkglobal="admin/sucursales" :lista="['Todas' => 'sucursal.listar', 'Agregar Stock' => 'sucursal.stock']">
                    <i class="flaticon-086-star"></i>
                </x-sidebar-elements>
                <x-sidebar-elements titulo="Tienda" linkglobal="admin/tienda" :lista="['Galeria de fotos' => 'tienda.galeria']">
                    <i class="fa fa-camera"></i>
                </x-sidebar-elements>
            @endif
            <x-sidebar-elements titulo="Productos" linkglobal="admin/productos" :lista="[
                'Listar Productos' => 'producto.listar',
                'Crear Nuevo' => 'producto.crear',
                'Categorias' => 'producto.categoria',
                'Subcategorias' => 'producto.subcategoria',
                'Adicionales' => 'producto.adicionales',
                'Agregar adicional a subcategoria' => 'producto.agregar.adicional',
            ]">
                <i class="flaticon-043-menu"></i>
            </x-sidebar-elements>
            @if (auth()->user()->role->nombre != 'cocina')
                <x-sidebar-elements titulo="Usuarios" linkglobal="admin/usuarios" :lista="[
                    'Listar Usuarios' => 'usuario.listar',
                    'Roles' => 'usuario.roles',
                    'Agregar Plan a Usuario' => 'planes',
                    'Crear Plan' => 'crear.plan',
                    'Asistencia' => 'usuario.asistencia',
                ]">
                    <i class="flaticon-045-heart"></i>
                </x-sidebar-elements>
                <x-sidebar-elements titulo="Almuerzos" linkglobal="admin/almuerzos" :lista="[
                    'Personalizar dias' => 'almuerzos.listar',
                    'Reporte Diario' => 'almuerzos.reporte',
                    'Reporte Semanal' => 'reporte.semana',
                ]">
                    <i class="flaticon-022-copy"></i>
                </x-sidebar-elements>

                <x-sidebar-elements titulo="Ventas" linkglobal="admin/ventas" :lista="['Ventas diarias' => 'ventas.listar']">
                    <i class="flaticon-013-checkmark"></i>
                </x-sidebar-elements>

                <x-sidebar-elements titulo="Perifericos" linkglobal="admin/perifericos" :lista="['Impresoras' => 'impresoras.index']">
                    <i class="flaticon-072-printer"></i>
                </x-sidebar-elements>



                <x-sidebar-elements titulo="Otros" linkglobal="admin/otros" :lista="['Importar excel' => 'importar.index',
                'Tickets whatsapp' => 'whatsapp.index']">
                    <i class="flaticon-022-copy"></i>
                </x-sidebar-elements>
            @endif

        </ul>


        <div class="plus-box" style="padding:20px 20px 30px">
            <p class="fs-16 font-w300 mb-4">Estas en el panel principal!</p>
            <a class="text-white fs-14" href="javascript:void(0);">Eres parte del equipo DELIGHT!</a>
        </div>
        <div class="copyright">
            <p><strong>Delight</strong> © 2021 Todos los derechos reservados</p>
            <p class="fs-12">Hecho con <span class="heart"></span> por Macrobyte</p>
        </div>
    </div>
</div>
