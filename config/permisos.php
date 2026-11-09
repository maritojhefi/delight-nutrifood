<?php

/*
|--------------------------------------------------------------------------
| Catálogo de permisos del panel admin
|--------------------------------------------------------------------------
|
| Dos niveles: módulo (grupo del menú lateral) => submódulos (opciones del
| grupo, identificadas por el nombre de la ruta). Un rol puede tener el
| módulo completo o solo algunos submódulos (tabla permisos_roles).
|
| El rol admin tiene siempre todos los permisos. Las rutas de /admin que no
| aparecen aquí no se restringen por permiso (solo por el middleware checkrol).
|
*/

return [
    'modulos' => [
        'cocina' => [
            'icono' => 'flaticon-025-dashboard',
            'descripcion' => 'Pantallas de trabajo de la cocina: despachar pedidos, cargar stock y paneles por área.',
            'titulo' => 'Cocina',
            'items' => [
                'Despachar pedidos' => 'reporte.cocina',
                'Agregar stock' => 'sucursal.stock',
                'Áreas de despacho' => 'areapanel.index',
            ],
        ],
        'inicio' => [
            'icono' => 'flaticon-025-dashboard',
            'descripcion' => 'Resumen del día con las ventas y la caja de hoy.',
            'titulo' => 'Inicio',
            'items' => [
                'Resumen de hoy' => 'caja.diaria',
            ],
        ],
        'estadisticas' => [
            'icono' => 'flaticon-041-graph',
            'descripcion' => 'Reportes de ventas diarios y mensuales.',
            'titulo' => 'Estadisticas',
            'items' => [
                'Reporte Diario' => 'caja.reportes.v2',
                'Reporte Mensual' => 'caja.reporte.mensual',
            ],
        ],
        'nutritips' => [
            'icono' => 'fa fa-camera',
            'descripcion' => 'Fotos, videos y novedades que se muestran en la tienda.',
            'titulo' => 'Nutri-Tips',
            'items' => [
                'Galeria de fotos' => 'tienda.galeria',
                'Videos' => 'tienda.tutoriales',
                'Novedades' => 'tienda.novedades',
            ],
        ],
        'puntos' => [
            'icono' => 'flaticon-064-pin',
            'descripcion' => 'Perfiles del programa de puntos y su historial.',
            'titulo' => 'Gestión de puntos',
            'items' => [
                'Perfiles' => 'perfiles.index',
                'Registros Históricos' => 'registros.index',
            ],
        ],
        'productos' => [
            'icono' => 'flaticon-043-menu',
            'descripcion' => 'Catálogo: productos, categorías, adicionales y stock.',
            'titulo' => 'Productos',
            'items' => [
                'Listar Productos' => 'producto.listar',
                'Crear Nuevo' => 'producto.crear',
                'Almacen y stock' => 'producto.expiracion',
                'Tags' => 'producto.tags',
                'Categorias' => 'producto.categoria',
                'Subcategorias' => 'producto.subcategoria',
                'Adicionales' => 'producto.adicionales',
                'Agregar adicional a subcategoria' => 'producto.agregar.adicional',
            ],
        ],
        'usuarios' => [
            'icono' => 'flaticon-381-user',
            'descripcion' => 'Clientes y personal, roles, saldos, asistencia y cumpleaños.',
            'titulo' => 'Usuarios',
            'items' => [
                'Listar Usuarios' => 'usuario.listar',
                'Roles' => 'usuario.roles',
                'Colaboradores' => 'usuario.empleo',
                'Billeteras y saldos' => 'usuario.saldos',
                'Asistencia' => 'usuario.asistencia',
                'Cumpleaños' => 'usuario.cumpleanos',
            ],
        ],
        'produccion' => [
            'icono' => 'flaticon-381-photo',
            'descripcion' => 'Menú semanal, planes de almuerzo y despacho por áreas.',
            'titulo' => 'Produccion',
            'items' => [
                'Personalizar menú semanal' => 'almuerzos.listar',
                'Reporte Diario' => 'almuerzos.reporte',
                'Planes' => 'crear.plan',
                'Agregar Plan a Usuario' => 'planes',
                'Planes por expirar' => 'planes.expirar',
                'Cocina' => 'reporte.cocina',
                'Áreas de despacho' => 'areapanel.index',
                'Planes en desarrollo(whatsapp)' => 'reporte.whatsapp',
            ],
        ],
        'ventas' => [
            'icono' => 'flaticon-013-checkmark',
            'descripcion' => 'Punto de venta, prospectos y mesas.',
            'titulo' => 'Ventas',
            'items' => [
                'Ventas diarias' => 'ventas.listar',
                'Prospectos de Clientes' => 'ventas.prospectos',
                'Mesas' => 'ventas.mesas',
            ],
        ],
        'convenios' => [
            'icono' => 'flaticon-381-panel',
            'descripcion' => 'Descuentos por convenio y los clientes vinculados.',
            'titulo' => 'Convenios',
            'items' => [
                'Crear' => 'convenio.index',
                'Vincular Usuario' => 'convenio.vincular.usuario',
            ],
        ],
        'perifericos' => [
            'icono' => 'flaticon-072-printer',
            'descripcion' => 'Impresoras de tickets y comandas.',
            'titulo' => 'Perifericos',
            'items' => [
                'Impresoras' => 'impresoras.index',
            ],
        ],
        'otros' => [
            'icono' => 'flaticon-022-copy',
            'descripcion' => 'Importar Excel y mensajes de WhatsApp.',
            'titulo' => 'Otros',
            'items' => [
                'Importar excel' => 'importar.index',
                'Historial enviados' => 'whatsapp.historial',
                'Tickets whatsapp' => 'whatsapp.index',
            ],
        ],
        'configuraciones' => [
            'icono' => 'fa fa-gears',
            'descripcion' => 'Ajustes generales del sistema.',
            'titulo' => 'Configuraciones',
            'items' => [
                'Sistema' => 'sistema.index',
            ],
        ],
        'horarios' => [
            'icono' => 'fa fa-clock-o',
            'descripcion' => 'Horarios de atención y de los menús.',
            'titulo' => 'Horarios',
            'items' => [
                'Horarios' => 'index.horarios',
            ],
        ],
        // Acceso a la caja diaria desde el menú del encabezado (no está en el menú lateral)
        'caja_diaria' => [
            'icono' => 'la la-dollar',
            'descripcion' => 'Enlace a la caja diaria en el menú de perfil (arriba a la derecha).',
            'titulo' => 'Caja diaria (menú del encabezado)',
            'items' => [
                'Caja Diaria' => 'caja.diaria',
            ],
        ],
    ],
];
