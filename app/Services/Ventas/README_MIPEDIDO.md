# Cambios relevantes actualizaciones al control de pedidos realizados por el cliente 

Fecha: 10/10/25

Se incluyeron multiples servicios para el proceso necesario de validar y añadir productos a una venta activa de clientes.
Los archivos de mayor importancia son:

## Controladores:

### VentasWebController:
Controlador encargado de procesar las solicitudes de ventas hechas por usuarios clientes.

### CarritoController:
Controlador responsable de validar la informacion de los productos incluidos en el carrito del cliente.

## Servicios:

### ProductoVentaService:
Se incluyeron nuevos servicios necesarios para el manejo de pedidos a traves de solicitudes realizadas por los clientes.

### StockService:
Inclusion del servicio verificarStockCompleto, utilizado para la validacion necesaria de productos disponibles, escasos y agotados.
