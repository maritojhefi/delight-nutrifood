# 📚 API REST de Ventas - Documentación Completa

Esta API REST permite gestionar el flujo completo de ventas utilizando los services desarrollados para el sistema. Todas las rutas requieren autenticación mediante el sistema de autenticación estándar de Laravel.

## 🔐 Autenticación

Todas las rutas requieren el middleware `auth` estándar de Laravel. Puedes autenticarte de las siguientes maneras:

### 1. Autenticación por Sesión (Recomendado para frontend web)
Si ya estás autenticado en la aplicación web, las peticiones AJAX funcionarán automáticamente:

```javascript
// Con fetch
fetch('/api/ventas', {
    method: 'GET',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    credentials: 'include'  // Importante para incluir cookies de sesión
})

// Con axios (configuración global recomendada)
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
axios.defaults.withCredentials = true;
```

### 2. Autenticación por API Token (Para aplicaciones externas)
Si necesitas acceso desde una aplicación externa, puedes usar tokens de API:

```http
Authorization: Bearer {api_token_del_usuario}
Content-Type: application/json
Accept: application/json
```

### 3. Autenticación HTTP Basic (Para testing)
```http
Authorization: Basic {base64(email:password)}
Content-Type: application/json
Accept: application/json
```

## 📋 Índice de Endpoints

### 🛒 [Gestión de Ventas](#-gestión-de-ventas-1)
- [GET /api/ventas](#obtener-todas-las-ventas) - Listar ventas
- [POST /api/ventas](#crear-una-nueva-venta) - Crear venta
- [GET /api/ventas/{id}](#obtener-una-venta-específica) - Obtener venta
- [PUT /api/ventas/{id}](#actualizar-venta) - Actualizar venta
- [DELETE /api/ventas/{id}](#eliminar-venta) - Eliminar venta

### ⚙️ [Operaciones de Venta](#️-operaciones-de-venta-1)
- [PATCH /api/ventas/{id}/descuento](#actualizar-descuento) - Actualizar descuento
- [PATCH /api/ventas/{id}/cliente](#cambiar-cliente) - Cambiar cliente
- [PATCH /api/ventas/{id}/usuario-manual](#agregar-usuario-manual) - Usuario manual
- [POST /api/ventas/{id}/enviar-cocina](#enviar-a-cocina) - Enviar a cocina
- [POST /api/ventas/{id}/cobrar](#cobrar-venta) - Cobrar venta
- [POST /api/ventas/{id}/cerrar](#cerrar-venta) - Cerrar venta

### 🛍️ [Productos en Venta](#️-productos-en-venta-1)
- [POST /api/ventas/{id}/productos](#agregar-producto) - Agregar producto
- [DELETE /api/ventas/{id}/productos/eliminar-uno](#eliminar-una-unidad) - Eliminar una unidad
- [DELETE /api/ventas/{id}/productos](#eliminar-producto-completo) - Eliminar producto completo
- [POST /api/ventas/{id}/productos/adicional](#agregar-adicional) - Agregar adicional
- [DELETE /api/ventas/{id}/productos/item](#eliminar-item) - Eliminar item
- [PATCH /api/ventas/{id}/productos/observacion](#guardar-observación) - Guardar observación
- [POST /api/ventas/{id}/productos/desde-plan](#agregar-desde-plan) - Agregar desde plan

### 💰 [Gestión de Saldos](#-gestión-de-saldos-1)
- [POST /api/ventas/{id}/saldos](#registrar-saldo) - Registrar saldo
- [PATCH /api/saldos/{id}/anular](#anular-saldo) - Anular saldo
- [GET /api/ventas/{id}/saldos/maximo-descuento](#calcular-máximo-descuento) - Máximo descuento
- [POST /api/ventas/{id}/saldos/validar-descuento](#validar-descuento) - Validar descuento

---

## 🛒 Gestión de Ventas

### Obtener todas las ventas

```http
GET /api/ventas
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "usuario_id": 1,
        "sucursale_id": 1,
        "cliente_id": 1,
        "total": 25.50,
        "pagado": false,
        "created_at": "2024-01-15T10:30:00.000000Z",
        "cliente": {
          "id": 1,
          "name": "Juan Pérez",
          "email": "juan@email.com"
        },
        "sucursale": {
          "id": 1,
          "nombre": "Sucursal Centro"
        },
        "productos": [...]
      }
    ],
    "per_page": 15,
    "total": 1
  },
  "message": "Ventas obtenidas correctamente"
}
```

### Crear una nueva venta

```http
POST /api/ventas
```

**Cuerpo de la petición:**
```json
{
  "sucursale_id": 1,
  "cliente_id": 5
}
```

**Campos:**
- `sucursale_id` (requerido): ID de la sucursal
- `cliente_id` (opcional): ID del cliente

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": {
    "id": 123,
    "usuario_id": 1,
    "sucursale_id": 1,
    "cliente_id": 5,
    "total": 0,
    "pagado": false,
    "created_at": "2024-01-15T10:30:00.000000Z"
  },
  "message": "Nueva venta creada"
}
```

### Obtener una venta específica

```http
GET /api/ventas/{id}
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": {
    "venta": {
      "id": 1,
      "usuario_id": 1,
      "sucursale_id": 1,
      "cliente_id": 1,
      "total": 25.50,
      "pagado": false,
      "productos": [...]
    },
    "calculos": {
      "listaCuenta": [...],
      "subtotal": 30.00,
      "itemsCuenta": 3,
      "puntos": 10,
      "descuentoProductos": 4.50,
      "subtotalConDescuento": 25.50
    }
  },
  "message": "Venta obtenida correctamente"
}
```

### Actualizar venta

```http
PUT /api/ventas/{id}
```

*(Utilizando el endpoint estándar de Laravel, puedes extender según necesidades)*

### Eliminar venta

```http
DELETE /api/ventas/{id}
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": null,
  "message": "Venta eliminada"
}
```

---

## ⚙️ Operaciones de Venta

### Actualizar descuento

```http
PATCH /api/ventas/{id}/descuento
```

**Cuerpo de la petición:**
```json
{
  "descuento": 5.50
}
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "descuento": 5.50,
    "total": 20.00
  },
  "message": "Descuento actualizado!"
}
```

### Cambiar cliente

```http
PATCH /api/ventas/{id}/cliente
```

**Cuerpo de la petición:**
```json
{
  "cliente_id": 8
}
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "cliente_id": 8,
    "cliente": {
      "id": 8,
      "name": "María García"
    }
  },
  "message": "Se asignó a esta venta el cliente: María García"
}
```

### Agregar usuario manual

```http
PATCH /api/ventas/{id}/usuario-manual
```

**Cuerpo de la petición:**
```json
{
  "usuario_manual": "Cliente sin registro - Mesa 5"
}
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "usuario_manual": "Cliente sin registro - Mesa 5"
  },
  "message": "Hecho!"
}
```

### Enviar a cocina

```http
POST /api/ventas/{id}/enviar-cocina
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "cocina": true,
    "cocina_at": "2024-01-15T10:30:00.000000Z"
  },
  "message": "Se envió a cocina!"
}
```

### Cobrar venta

```http
POST /api/ventas/{id}/cobrar
```

**Cuerpo de la petición:**
```json
{
  "metodos_seleccionados": {
    "EF": {
      "activo": true,
      "valor": 15.00
    },
    "TC": {
      "activo": true,
      "valor": 10.50
    }
  },
  "total_acumulado": 25.50,
  "subtotal_con_descuento": 25.50,
  "descuento_saldo": 0
}
```

**Campos:**
- `metodos_seleccionados`: Objeto con métodos de pago y montos
- `total_acumulado`: Total acumulado de todos los métodos
- `subtotal_con_descuento`: Subtotal con descuentos aplicados
- `descuento_saldo`: Descuento por saldo del cliente (opcional)

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "pagado": true,
    "historial_venta_id": 45
  },
  "message": "Esta venta ahora se encuentra pagada!"
}
```

### Cerrar venta

```http
POST /api/ventas/{id}/cerrar
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": null,
  "message": "Se finalizó esta venta"
}
```

---

## 🛍️ Productos en Venta

### Agregar producto

```http
POST /api/ventas/{id}/productos
```

**Cuerpo de la petición:**
```json
{
  "producto_id": 15,
  "cantidad": 2
}
```

**Campos:**
- `producto_id` (requerido): ID del producto
- `cantidad` (opcional): Cantidad a agregar (default: 1)

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": {
    "venta": {
      "id": 1,
      "productos": [...]
    },
    "calculos": {
      "subtotal": 35.00,
      "itemsCuenta": 4,
      "puntos": 15
    }
  },
  "message": "Se agregó 2 Pizza Margherita a esta venta"
}
```

### Eliminar una unidad

```http
DELETE /api/ventas/{id}/productos/eliminar-uno
```

**Cuerpo de la petición:**
```json
{
  "producto_id": 15
}
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": {
    "venta": {...},
    "calculos": {...}
  },
  "message": "Se eliminó 1 Pizza Margherita de esta venta"
}
```

### Eliminar producto completo

```http
DELETE /api/ventas/{id}/productos
```

**Cuerpo de la petición:**
```json
{
  "producto_id": 15
}
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": {
    "venta": {...},
    "calculos": {...}
  },
  "message": "Se eliminó Pizza Margherita de esta venta"
}
```

### Agregar adicional

```http
POST /api/ventas/{id}/productos/adicional
```

**Cuerpo de la petición:**
```json
{
  "producto_id": 15,
  "adicional_id": 8,
  "item": 1
}
```

**Campos:**
- `producto_id`: ID del producto al que agregar el adicional
- `adicional_id`: ID del adicional
- `item`: Número del item del producto (para productos por unidad)

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": {
    "venta": {...},
    "calculos": {...}
  },
  "message": "Adicional agregado correctamente"
}
```

### Eliminar item

```http
DELETE /api/ventas/{id}/productos/item
```

**Cuerpo de la petición:**
```json
{
  "producto_id": 15,
  "posicion": 2
}
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": {
    "venta": {...},
    "calculos": {...}
  },
  "message": "Item eliminado correctamente"
}
```

### Guardar observación

```http
PATCH /api/ventas/{id}/productos/observacion
```

**Cuerpo de la petición:**
```json
{
  "producto_id": 15,
  "observacion": "Sin cebolla, extra queso"
}
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": null,
  "message": "Observación guardada"
}
```

### Agregar desde plan

```http
POST /api/ventas/{id}/productos/desde-plan
```

**Cuerpo de la petición:**
```json
{
  "user_id": 5,
  "plan_id": 3,
  "producto_id": 15
}
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": {
    "venta": {...},
    "calculos": {...}
  },
  "message": "Se restó una unidad al plan y se agregó el producto"
}
```

---

## 💰 Gestión de Saldos

### Registrar saldo

```http
POST /api/ventas/{id}/saldos
```

**Cuerpo de la petición:**
```json
{
  "monto": 50.00,
  "detalle": "Abono de cliente por productos defectuosos",
  "tipo": 1,
  "es_deuda": false
}
```

**Campos:**
- `monto`: Monto del saldo
- `detalle`: Descripción del saldo
- `tipo`: ID del método de pago
- `es_deuda`: Si es deuda del cliente (true) o a favor (false)

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": {
    "id": 25,
    "monto": 50.00,
    "es_deuda": false,
    "detalle": "Abono de cliente por productos defectuosos"
  },
  "message": "Se editó el saldo a favor de este cliente!"
}
```

### Anular saldo

```http
PATCH /api/saldos/{id}/anular
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": {
    "id": 25,
    "anulado": true
  },
  "message": "El saldo fue anulado!"
}
```

### Calcular máximo descuento

```http
GET /api/ventas/{id}/saldos/maximo-descuento
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": {
    "maximo_descuento_saldo": 15.50
  },
  "message": "Máximo descuento calculado"
}
```

### Validar descuento

```http
POST /api/ventas/{id}/saldos/validar-descuento
```

**Cuerpo de la petición:**
```json
{
  "descuento": 10.00
}
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": null,
  "message": "Descuento de saldo válido"
}
```

---

## 🚨 Manejo de Errores

### Códigos de Estado HTTP

- **200**: Operación exitosa
- **201**: Recurso creado exitosamente
- **400**: Error en la lógica de negocio
- **422**: Error de validación
- **500**: Error interno del servidor

### Formato de Respuesta de Error

```json
{
  "success": false,
  "message": "Descripción del error",
  "errors": {
    "campo": ["Error específico del campo"]
  }
}
```

### Errores Comunes

#### Error de Validación (422)
```json
{
  "success": false,
  "message": "Datos de validación incorrectos",
  "errors": {
    "producto_id": ["El campo producto_id es requerido."],
    "cantidad": ["La cantidad debe ser un número entero."]
  }
}
```

#### Error de Negocio (400)
```json
{
  "success": false,
  "message": "La venta ya ha sido pagada, no se puede modificar",
  "data": null,
  "errors": [],
  "type": "warning"
}
```

#### Error de Stock (400)
```json
{
  "success": false,
  "message": "No existe stock suficiente para Pizza Margherita",
  "data": null,
  "errors": [],
  "type": "warning"
}
```

---

## 🔍 Ejemplos de Uso Completo

### Flujo Completo de Venta

#### 1. Crear nueva venta
```bash
# Con autenticación por sesión (desde frontend autenticado)
curl -X POST http://tu-app.com/api/ventas \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: tu_csrf_token" \
  -b "cookies.txt" \
  -d '{
    "sucursale_id": 1,
    "cliente_id": 5
  }'

# Con API Token
curl -X POST http://tu-app.com/api/ventas \
  -H "Authorization: Bearer tu_api_token" \
  -H "Content-Type: application/json" \
  -d '{
    "sucursale_id": 1,
    "cliente_id": 5
  }'
```

#### 2. Agregar productos
```bash
curl -X POST http://tu-app.com/api/ventas/123/productos \
  -H "Authorization: Bearer tu_api_token" \
  -H "Content-Type: application/json" \
  -d '{
    "producto_id": 15,
    "cantidad": 2
  }'
```

#### 3. Agregar adicional
```bash
curl -X POST http://tu-app.com/api/ventas/123/productos/adicional \
  -H "Authorization: Bearer tu_api_token" \
  -H "Content-Type: application/json" \
  -d '{
    "producto_id": 15,
    "adicional_id": 8,
    "item": 1
  }'
```

#### 4. Aplicar descuento
```bash
curl -X PATCH http://tu-app.com/api/ventas/123/descuento \
  -H "Authorization: Bearer tu_api_token" \
  -H "Content-Type: application/json" \
  -d '{
    "descuento": 5.00
  }'
```

#### 5. Cobrar venta
```bash
curl -X POST http://tu-app.com/api/ventas/123/cobrar \
  -H "Authorization: Bearer tu_api_token" \
  -H "Content-Type: application/json" \
  -d '{
    "metodos_seleccionados": {
      "EF": {"activo": true, "valor": 20.00}
    },
    "total_acumulado": 20.00,
    "subtotal_con_descuento": 20.00,
    "descuento_saldo": 0
  }'
```

#### 6. Cerrar venta
```bash
curl -X POST http://tu-app.com/api/ventas/123/cerrar \
  -H "Authorization: Bearer tu_api_token"
```

### Ejemplo con JavaScript (Frontend)

```javascript
// Configuración inicial de axios para tu frontend
axios.defaults.baseURL = '/api';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
axios.defaults.withCredentials = true;

// Ejemplo de uso
class VentaAPI {
  async crearVenta(sucursaleId, clienteId = null) {
    try {
      const response = await axios.post('/ventas', {
        sucursale_id: sucursaleId,
        cliente_id: clienteId
      });
      return response.data;
    } catch (error) {
      throw error.response.data;
    }
  }

  async agregarProducto(ventaId, productoId, cantidad = 1) {
    try {
      const response = await axios.post(`/ventas/${ventaId}/productos`, {
        producto_id: productoId,
        cantidad: cantidad
      });
      return response.data;
    } catch (error) {
      throw error.response.data;
    }
  }

  async cobrarVenta(ventaId, metodosSeleccionados, totalAcumulado, subtotalConDescuento, descuentoSaldo = 0) {
    try {
      const response = await axios.post(`/ventas/${ventaId}/cobrar`, {
        metodos_seleccionados: metodosSeleccionados,
        total_acumulado: totalAcumulado,
        subtotal_con_descuento: subtotalConDescuento,
        descuento_saldo: descuentoSaldo
      });
      return response.data;
    } catch (error) {
      throw error.response.data;
    }
  }
}

// Uso de la clase
const ventaAPI = new VentaAPI();

ventaAPI.crearVenta(1, 5)
  .then(venta => {
    console.log('Venta creada:', venta);
    return ventaAPI.agregarProducto(venta.data.id, 15, 2);
  })
  .then(resultado => {
    console.log('Producto agregado:', resultado);
  })
  .catch(error => {
    console.error('Error:', error);
  });
```

---

## 📝 Notas Importantes

1. **Autenticación Requerida**: Todos los endpoints requieren autenticación con el middleware `auth` estándar de Laravel
2. **CSRF Protection**: Para peticiones desde frontend web, incluye el token CSRF en el header `X-CSRF-TOKEN`
3. **Venta Pagada**: Una vez que una venta está pagada, no se pueden modificar sus productos
4. **Stock**: El sistema controla automáticamente el stock de productos contables
5. **Transacciones**: Las operaciones críticas (como cobrar) usan transacciones de base de datos
6. **Eventos**: Algunas operaciones disparan eventos (como enviar a cocina)
7. **Cálculos Automáticos**: Los totales se recalculan automáticamente después de cada operación
8. **Sesiones**: Si usas autenticación por sesión, asegúrate de incluir `credentials: 'include'` en fetch o `withCredentials: true` en axios

---

## 🔄 Estado de la Venta

Una venta puede estar en diferentes estados:
- **Pendiente**: Recién creada, se pueden agregar/quitar productos
- **En Cocina**: Enviada a cocina, no se puede modificar hasta que sea despachada
- **Pagada**: Ya fue cobrada, no se pueden modificar productos
- **Cerrada**: Finalizada y eliminada del sistema

El flujo típico es: **Pendiente** → **En Cocina** (opcional) → **Pagada** → **Cerrada**
