# Flujo de Adicionales con SweetAlert

## Diagrama de Flujo

```
┌─────────────────────────────────────────────────────────────────┐
│                    CAJERO HACE CLICK EN PRODUCTO                │
└─────────────────────────┬───────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────────┐
│              ¿PRODUCTO TIENE ADICIONALES?                      │
│                    (Verificar subcategoría)                    │
└─────────┬─────────────────────────────┬─────────────────────────┘
          │                             │
          │ NO                          │ SÍ
          ▼                             ▼
┌─────────────────────┐    ┌─────────────────────────────────────┐
│   AGREGAR PRODUCTO  │    │        MOSTRAR SWEETALERT           │
│      DIRECTAMENTE   │    │                                     │
│                     │    │  ┌─────────────────────────────────┐ │
│  - Verificar stock  │    │  │     CONFIGURAR ADICIONALES     │ │
│  - Agregar a venta  │    │  │                                 │ │
│  - Actualizar UI    │    │  │  • Seleccionar por grupos      │ │
│                     │    │  │  • Validar reglas              │ │
│                     │    │  │  • Calcular precios            │ │
│                     │    │  │  • Mostrar cantidad            │ │
│                     │    │  └─────────────────────────────────┘ │
└─────────────────────┘    │                                     │
                          │  ┌─────────────────────────────────┐ │
                          │  │      VALIDAR SELECCIÓN         │ │
                          │  │                                 │ │
                          │  │  • Grupos obligatorios         │ │
                          │  │  • Máximo seleccionable        │ │
                          │  │  • Stock disponible            │ │
                          │  └─────────────────────────────────┘ │
                          │                                     │
                          │  ┌─────────────────────────────────┐ │
                          │  │     PROCESAR SELECCIÓN         │ │
                          │  │                                 │ │
                          │  │  • Enviar a Livewire           │ │
                          │  │  • Agregar producto + adicionales │ │
                          │  │  • Actualizar UI                │ │
                          │  └─────────────────────────────────┘ │
                          └─────────────────────────────────────┘
```

## Estructura de Validaciones

```
┌─────────────────────────────────────────────────────────────────┐
│                    VALIDACIONES DEL SWEETALERT                 │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│ 1. GRUPOS OBLIGATORIOS                                         │
│    ┌─────────────────────────────────────────────────────────┐ │
│    │ Si es_obligatorio = true                               │ │
│    │   → Debe seleccionar al menos 1 opción                 │ │
│    │   → Mostrar error si no se selecciona                  │ │
│    └─────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│ 2. MÁXIMO SELECCIONABLE                                        │
│    ┌─────────────────────────────────────────────────────────┐ │
│    │ Si maximo_seleccionable = 1                            │ │
│    │   → Usar Radio Buttons (solo 1 selección)              │ │
│    │                                                         │ │
│    │ Si maximo_seleccionable > 1                            │ │
│    │   → Usar Checkboxes (múltiples selecciones)            │ │
│    │   → Validar que no exceda el máximo                    │ │
│    └─────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│ 3. STOCK DE ADICIONALES                                        │
│    ┌─────────────────────────────────────────────────────────┐ │
│    │ Si contable = true Y cantidad = 0                      │ │
│    │   → Deshabilitar opción                                │ │
│    │   → Mostrar como no disponible                         │ │
│    └─────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
```

## Componentes del Sistema

```
┌─────────────────────────────────────────────────────────────────┐
│                    COMPONENTES PRINCIPALES                     │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│ 1. VENTASINDEX.PHP (Livewire)                                  │
│    ┌─────────────────────────────────────────────────────────┐ │
│    │ • adicionar() - Punto de entrada                       │ │
│    │ • productoTieneAdicionales() - Verificación            │ │
│    │ • mostrarSweetAlertAdicionales() - Disparar modal      │ │
│    │ • organizarAdicionalesPorGrupos() - Estructurar datos  │ │
│    │ • agregarProductoConAdicionales() - Procesar selección │ │
│    └─────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│ 2. ADICIONALES-SWEETALERT.JS (Frontend)                        │
│    ┌─────────────────────────────────────────────────────────┐ │
│    │ • mostrarSweetAlertAdicionales() - Crear modal         │ │
│    │ • crearHTMLModal() - Generar HTML dinámico             │ │
│    │ • inicializarEventosModal() - Configurar eventos       │ │
│    │ • validarYProcesarSeleccion() - Validar y enviar       │ │
│    │ • actualizarPrecios() - Cálculo en tiempo real         │ │
│    └─────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│ 3. SERVICIOS EXISTENTES                                        │
│    ┌─────────────────────────────────────────────────────────┐ │
│    │ • ProductoVentaService::agregarProductoCliente()       │ │
│    │ • ProductoVentaService::agregarProducto()              │ │
│    │ • Subcategoria::adicionalesGrupo()                     │ │
│    └─────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
```

## Estados del Modal

```
┌─────────────────────────────────────────────────────────────────┐
│                      ESTADOS DEL MODAL                         │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│ 1. INICIALIZACIÓN                                              │
│    • Cargar datos del producto                                 │
│    • Organizar adicionales por grupos                         │
│    • Configurar validaciones                                  │
│    • Mostrar interfaz inicial                                 │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│ 2. CONFIGURACIÓN                                               │
│    • Usuario selecciona adicionales                            │
│    • Validación en tiempo real                                 │
│    • Cálculo de precios dinámico                               │
│    • Actualización de contadores                               │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│ 3. VALIDACIÓN                                                  │
│    • Verificar grupos obligatorios                             │
│    │ • Mostrar errores si faltan selecciones                  │
│    • Verificar máximo seleccionable                            │
│    │ • Mostrar errores si excede límite                       │
│    • Verificar stock disponible                                │
│    │ • Mostrar errores si no hay stock                        │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│ 4. PROCESAMIENTO                                               │
│    • Enviar datos a Livewire                                   │
│    • Agregar producto con adicionales                          │
│    • Actualizar interfaz de venta                              │
│    • Cerrar modal                                              │
└─────────────────────────────────────────────────────────────────┘
```

## Casos de Uso

### Caso 1: Producto sin Adicionales

```
Click en Producto → Verificar adicionales → NO → Agregar directamente
```

### Caso 2: Producto con Adicionales - Configuración Correcta

```
Click en Producto → Verificar adicionales → SÍ → Mostrar SweetAlert →
Configurar adicionales → Validar → Agregar con adicionales
```

### Caso 3: Producto con Adicionales - Error de Validación

```
Click en Producto → Verificar adicionales → SÍ → Mostrar SweetAlert →
Configurar adicionales → Error de validación → Mostrar mensaje →
Corregir selección → Validar → Agregar con adicionales
```

### Caso 4: Adicionales sin Stock

```
Click en Producto → Verificar adicionales → SÍ → Mostrar SweetAlert →
Adicionales sin stock → Deshabilitar opciones → Configurar disponibles →
Validar → Agregar con adicionales disponibles
```
