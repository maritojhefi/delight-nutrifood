# SweetAlert para Adicionales de Productos - POS

## Descripción

Esta funcionalidad permite que los cajeros configuren adicionales para productos antes de agregarlos a la venta, utilizando un SweetAlert modal interactivo que valida las reglas de grupos de adicionales.

## Características Principales

### 1. Detección Automática de Adicionales

-   El sistema detecta automáticamente si un producto tiene adicionales basándose en su subcategoría
-   Solo productos con adicionales muestran el SweetAlert
-   Productos sin adicionales se agregan directamente a la venta

### 2. Validación de Reglas de Grupos

-   **Grupos Obligatorios**: El cajero debe seleccionar al menos una opción
-   **Máximo Seleccionable**: Controla cuántas opciones se pueden seleccionar por grupo
-   **Radio Buttons**: Para grupos con máximo 1 selección
-   **Checkboxes**: Para grupos con máximo > 1 selección

### 3. Interfaz Intuitiva

-   Modal responsive que se adapta a diferentes tamaños de pantalla
-   Información del producto (nombre, precio, imagen)
-   Selector de cantidad con botones +/-
-   Cálculo en tiempo real del precio total
-   Validación visual de selecciones

## Archivos Modificados

### 1. `app/Http/Livewire/Admin/Ventas/VentasIndex.php`

**Métodos agregados:**

-   `productoTieneAdicionales()`: Verifica si un producto tiene adicionales
-   `mostrarSweetAlertAdicionales()`: Dispara el evento para mostrar el modal
-   `organizarAdicionalesPorGrupos()`: Organiza los adicionales por grupos con sus reglas
-   `agregarProductoConAdicionales()`: Procesa la selección del SweetAlert
-   `agregarProductoDirecto()`: Agrega productos sin adicionales

**Método modificado:**

-   `adicionar()`: Ahora verifica si el producto tiene adicionales antes de agregarlo

### 2. `public/js/adicionales-sweetalert.js`

**Funciones principales:**

-   `mostrarSweetAlertAdicionales()`: Crea y muestra el modal
-   `crearHTMLModal()`: Genera el HTML del modal dinámicamente
-   `inicializarEventosModal()`: Configura los event listeners
-   `validarYProcesarSeleccion()`: Valida las reglas y envía datos a Livewire
-   `actualizarPrecios()`: Calcula precios en tiempo real

### 3. `resources/views/livewire/admin/ventas/ventas-index.blade.php`

-   Incluye el archivo JavaScript del SweetAlert

## Estructura de Datos

### Grupos de Adicionales

```php
[
    'id' => 1,
    'nombre' => 'Endulzante',
    'es_obligatorio' => true,
    'maximo_seleccionable' => 1,
    'adicionales' => [
        [
            'id' => 1,
            'nombre' => 'con stevia',
            'precio' => 0.00,
            'contable' => false,
            'cantidad' => 0
        ]
    ]
]
```

### Validaciones Implementadas

1. **Grupos Obligatorios**: Si `es_obligatorio = true`, debe seleccionarse al menos una opción
2. **Máximo Seleccionable**: No se puede exceder el `maximo_seleccionable` por grupo
3. **Stock de Adicionales**: Se verifica si el adicional tiene stock disponible (`contable = true` y `cantidad > 0`)

## Flujo de Trabajo

1. **Click en Producto**: El cajero hace click en un producto en la grilla
2. **Verificación**: El sistema verifica si el producto tiene adicionales
3. **SweetAlert**: Si tiene adicionales, se muestra el modal de configuración
4. **Selección**: El cajero configura los adicionales según las reglas
5. **Validación**: El sistema valida que se cumplan todas las reglas
6. **Agregado**: El producto se agrega a la venta con los adicionales seleccionados

## Estilos CSS

El modal incluye estilos personalizados para:

-   Diseño responsive
-   Indicadores visuales de selección
-   Cálculo de precios en tiempo real
-   Validación de grupos obligatorios
-   Contadores de selección por grupo

## Integración con Servicios Existentes

La funcionalidad se integra con:

-   `ProductoVentaService::agregarProductoCliente()`: Para agregar productos con adicionales
-   `ProductoVentaService::agregarProducto()`: Para productos sin adicionales
-   Modelos existentes: `Producto`, `Adicionale`, `GrupoAdicionales`, `Subcategoria`

## Consideraciones Técnicas

1. **Performance**: Los adicionales se cargan solo cuando es necesario
2. **Validación**: Toda la validación se hace tanto en frontend como backend
3. **Responsive**: El modal se adapta a diferentes tamaños de pantalla
4. **Accesibilidad**: Incluye indicadores visuales claros para el usuario

## Uso

1. El cajero hace click en cualquier producto de la grilla
2. Si el producto tiene adicionales, aparece el SweetAlert
3. El cajero configura los adicionales según las reglas mostradas
4. Hace click en "AGREGAR PEDIDO" para confirmar
5. El producto se agrega a la venta con la configuración seleccionada

## Mantenimiento

Para agregar nuevos grupos de adicionales:

1. Crear el grupo en la tabla `grupos_adicionales`
2. Asociar adicionales al grupo en la tabla `adicionale_subcategoria`
3. La funcionalidad se actualiza automáticamente

Para modificar reglas:

1. Actualizar los campos `es_obligatorio` y `maximo_seleccionable` en `grupos_adicionales`
2. Los cambios se reflejan inmediatamente en el SweetAlert
