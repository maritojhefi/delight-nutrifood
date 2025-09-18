<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Configuración API de Ventas</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <h1>Ejemplo de Configuración API de Ventas</h1>
    
    <div id="app">
        <button onclick="testAPI()">Probar API</button>
        <div id="results"></div>
    </div>

    <script>
        // ===================================================
        // CONFIGURACIÓN INICIAL PARA TU FRONTEND
        // ===================================================
        
        // 1. Configurar axios globalmente
        axios.defaults.baseURL = '/api';
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['Accept'] = 'application/json';
        axios.defaults.headers.common['Content-Type'] = 'application/json';
        axios.defaults.withCredentials = true;

        // 2. Interceptor para manejar errores globalmente
        axios.interceptors.response.use(
            response => response,
            error => {
                if (error.response.status === 401) {
                    window.location.href = '/login';
                }
                return Promise.reject(error);
            }
        );

        // ===================================================
        // CLASE PARA MANEJAR API DE VENTAS
        // ===================================================
        class VentaAPI {
            // Obtener todas las ventas
            async obtenerVentas() {
                try {
                    const response = await axios.get('/ventas');
                    return response.data;
                } catch (error) {
                    throw this.handleError(error);
                }
            }

            // Crear nueva venta
            async crearVenta(sucursaleId, clienteId = null) {
                try {
                    const response = await axios.post('/ventas', {
                        sucursale_id: sucursaleId,
                        cliente_id: clienteId
                    });
                    return response.data;
                } catch (error) {
                    throw this.handleError(error);
                }
            }

            // Obtener venta específica
            async obtenerVenta(ventaId) {
                try {
                    const response = await axios.get(`/ventas/${ventaId}`);
                    return response.data;
                } catch (error) {
                    throw this.handleError(error);
                }
            }

            // Agregar producto a venta
            async agregarProducto(ventaId, productoId, cantidad = 1) {
                try {
                    const response = await axios.post(`/ventas/${ventaId}/productos`, {
                        producto_id: productoId,
                        cantidad: cantidad
                    });
                    return response.data;
                } catch (error) {
                    throw this.handleError(error);
                }
            }

            // Eliminar producto de venta
            async eliminarProducto(ventaId, productoId) {
                try {
                    const response = await axios.delete(`/ventas/${ventaId}/productos`, {
                        data: { producto_id: productoId }
                    });
                    return response.data;
                } catch (error) {
                    throw this.handleError(error);
                }
            }

            // Actualizar descuento
            async actualizarDescuento(ventaId, descuento) {
                try {
                    const response = await axios.patch(`/ventas/${ventaId}/descuento`, {
                        descuento: descuento
                    });
                    return response.data;
                } catch (error) {
                    throw this.handleError(error);
                }
            }

            // Cambiar cliente
            async cambiarCliente(ventaId, clienteId) {
                try {
                    const response = await axios.patch(`/ventas/${ventaId}/cliente`, {
                        cliente_id: clienteId
                    });
                    return response.data;
                } catch (error) {
                    throw this.handleError(error);
                }
            }

            // Enviar a cocina
            async enviarACocina(ventaId) {
                try {
                    const response = await axios.post(`/ventas/${ventaId}/enviar-cocina`);
                    return response.data;
                } catch (error) {
                    throw this.handleError(error);
                }
            }

            // Cobrar venta
            async cobrarVenta(ventaId, datosCobranza) {
                try {
                    const response = await axios.post(`/ventas/${ventaId}/cobrar`, datosCobranza);
                    return response.data;
                } catch (error) {
                    throw this.handleError(error);
                }
            }

            // Cerrar venta
            async cerrarVenta(ventaId) {
                try {
                    const response = await axios.post(`/ventas/${ventaId}/cerrar`);
                    return response.data;
                } catch (error) {
                    throw this.handleError(error);
                }
            }

            // Manejador de errores
            handleError(error) {
                if (error.response) {
                    return {
                        success: false,
                        message: error.response.data.message || 'Error en la petición',
                        errors: error.response.data.errors || {},
                        status: error.response.status
                    };
                } else {
                    return {
                        success: false,
                        message: 'Error de conexión',
                        errors: {},
                        status: 0
                    };
                }
            }
        }

        // ===================================================
        // FUNCIÓN DE PRUEBA
        // ===================================================
        async function testAPI() {
            const ventaAPI = new VentaAPI();
            const results = document.getElementById('results');
            
            try {
                results.innerHTML = '<p>Probando API...</p>';
                
                // Obtener ventas
                const ventas = await ventaAPI.obtenerVentas();
                
                results.innerHTML = `
                    <h3>✅ API funcionando correctamente</h3>
                    <p><strong>Ventas encontradas:</strong> ${ventas.data.total}</p>
                    <pre>${JSON.stringify(ventas, null, 2)}</pre>
                `;
                
            } catch (error) {
                results.innerHTML = `
                    <h3>❌ Error en la API</h3>
                    <p><strong>Mensaje:</strong> ${error.message}</p>
                    <p><strong>Status:</strong> ${error.status}</p>
                    <pre>${JSON.stringify(error, null, 2)}</pre>
                `;
            }
        }

        // ===================================================
        // EJEMPLO DE USO COMPLETO
        // ===================================================
        
        // Crear instancia de la API
        const ventaAPI = new VentaAPI();

        // Ejemplo de flujo completo de venta
        async function ejemploFlujocompleto() {
            try {
                // 1. Crear venta
                console.log('1. Creando venta...');
                const venta = await ventaAPI.crearVenta(1, 5);
                console.log('Venta creada:', venta);

                // 2. Agregar productos
                console.log('2. Agregando productos...');
                const productoAgregado = await ventaAPI.agregarProducto(venta.data.id, 15, 2);
                console.log('Producto agregado:', productoAgregado);

                // 3. Aplicar descuento
                console.log('3. Aplicando descuento...');
                const descuentoAplicado = await ventaAPI.actualizarDescuento(venta.data.id, 5.00);
                console.log('Descuento aplicado:', descuentoAplicado);

                // 4. Enviar a cocina
                console.log('4. Enviando a cocina...');
                const enviadoCocina = await ventaAPI.enviarACocina(venta.data.id);
                console.log('Enviado a cocina:', enviadoCocina);

                // 5. Cobrar venta
                console.log('5. Cobrando venta...');
                const ventaCobrada = await ventaAPI.cobrarVenta(venta.data.id, {
                    metodos_seleccionados: {
                        'EF': { activo: true, valor: 20.00 }
                    },
                    total_acumulado: 20.00,
                    subtotal_con_descuento: 20.00,
                    descuento_saldo: 0
                });
                console.log('Venta cobrada:', ventaCobrada);

                // 6. Cerrar venta
                console.log('6. Cerrando venta...');
                const ventaCerrada = await ventaAPI.cerrarVenta(venta.data.id);
                console.log('Venta cerrada:', ventaCerrada);

                console.log('🎉 Flujo completo ejecutado exitosamente!');

            } catch (error) {
                console.error('❌ Error en el flujo:', error);
            }
        }

        // Descomentar para probar el flujo completo
        // ejemploFlujocompleto();

        console.log('🚀 API de Ventas configurada y lista para usar!');
    </script>
</body>
</html>
