# Services de Ventas

Esta arquitectura de services extrae la lógica de negocio del componente Livewire VentasIndex para permitir su reutilización en otras vistas y APIs.

## Estructura

```
app/Services/Ventas/
├── Contracts/                          # Interfaces
│   ├── VentaServiceInterface.php
│   ├── ProductoVentaServiceInterface.php
│   ├── SaldoServiceInterface.php
│   ├── StockServiceInterface.php
│   └── CalculadoraVentaServiceInterface.php
├── DTOs/                               # Objetos de transferencia de datos
│   ├── VentaResponse.php
│   └── VentaCalculosData.php
├── Exceptions/                         # Excepciones específicas
│   └── VentaException.php
├── VentaService.php                    # Operaciones principales de venta
├── ProductoVentaService.php           # Manejo de productos en ventas
├── SaldoService.php                   # Operaciones de saldo y pagos
├── StockService.php                   # Manejo de inventario
└── CalculadoraVentaService.php        # Cálculos de venta
```

## Servicios Principales

### 1. VentaService
Maneja las operaciones principales de una venta:
- Crear venta
- Cobrar venta
- Cerrar venta
- Eliminar venta
- Cambiar cliente
- Editar descuento
- Enviar a cocina

### 2. ProductoVentaService
Maneja los productos dentro de una venta:
- Agregar productos
- Eliminar productos
- Agregar adicionales
- Manejar observaciones
- Operaciones desde planes

### 3. CalculadoraVentaService
Realiza todos los cálculos de la venta:
- Calcular totales
- Calcular descuentos
- Calcular saldos resultantes

### 4. StockService
Maneja el inventario:
- Actualizar stock
- Verificar disponibilidad
- Operaciones de suma/resta de stock

### 5. SaldoService
Maneja los saldos y pagos:
- Registrar saldos
- Anular saldos
- Validar descuentos de saldo

## Uso en Livewire

```php
class VentasIndex extends Component
{
    private VentaServiceInterface $ventaService;
    private ProductoVentaServiceInterface $productoVentaService;

    public function boot(
        VentaServiceInterface $ventaService,
        ProductoVentaServiceInterface $productoVentaService
    ) {
        $this->ventaService = $ventaService;
        $this->productoVentaService = $productoVentaService;
    }

    public function adicionar(Producto $producto)
    {
        $response = $this->productoVentaService->agregarProducto($this->cuenta, $producto);
        
        $this->dispatchBrowserEvent('alert', [
            'type' => $response->type,
            'message' => $response->message,
        ]);

        if ($response->success) {
            // Actualizar UI
        }
    }
}
```

## Uso en API

```php
class VentaController extends Controller
{
    public function __construct(
        private VentaServiceInterface $ventaService,
        private ProductoVentaServiceInterface $productoVentaService
    ) {}

    public function agregarProducto(Request $request, Venta $venta)
    {
        $producto = Producto::find($request->producto_id);
        $response = $this->productoVentaService->agregarProducto($venta, $producto, $request->cantidad);
        
        return response()->json($response->toArray(), $response->success ? 200 : 400);
    }
}
```

## Objetos de Respuesta

Todos los services devuelven objetos `VentaResponse` con:
- `success`: boolean indicando si la operación fue exitosa
- `message`: mensaje descriptivo
- `data`: datos resultantes de la operación
- `errors`: array de errores si los hay
- `type`: tipo de respuesta (success, error, warning, info)

## Manejo de Excepciones

Se utilizan excepciones personalizadas `VentaException` para casos específicos del negocio:
- Venta ya pagada
- Stock insuficiente
- Caja cerrada
- Métodos de pago incorrectos

## Configuración

1. Registrar el ServiceProvider en `config/app.php`:
```php
'providers' => [
    // ...
    App\Providers\VentaServiceProvider::class,
],
```

2. Los services se inyectan automáticamente mediante dependency injection.

## Extensibilidad

Para agregar nuevas funcionalidades:

1. **Nuevos métodos**: Agregar a la interfaz correspondiente y su implementación
2. **Nuevos services**: Crear nueva interfaz y service, registrar en el ServiceProvider
3. **Nuevas validaciones**: Agregar en VentaException o crear nuevas excepciones
4. **Nuevos cálculos**: Extender CalculadoraVentaService

## Testing

Cada service puede ser testeado independientemente:

```php
class VentaServiceTest extends TestCase
{
    public function test_crear_venta()
    {
        $service = app(VentaServiceInterface::class);
        $response = $service->crearVenta(1, 1, 1);
        
        $this->assertTrue($response->success);
        $this->assertInstanceOf(Venta::class, $response->data);
    }
}
```