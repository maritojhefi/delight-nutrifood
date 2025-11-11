<div class="row">
    <div class="col-6 m-0 pe-1">
        <div onclick="showSweetAlertVentasHoy()" style="cursor: pointer;" class="alert alert-primary solid row fade show p-1 align-items-center mx-auto">
            <strong class="letra12 text-center">Ventas hoy</strong>
            <strong class="text-center">
                {{ $cajaActiva->ventas->sum('total_pagado') }} Bs <i class="fa fa-info-circle"></i>
            </strong>
        </div>
    </div>
    <div class="col-6 m-0 ps-1">
        <div onclick="showSweetAlertGastosHoy()" style="cursor: pointer;" class="alert alert-info solid row  fade show p-1 align-items-center mx-auto">
            <strong class="letra12 text-center">Gastos hoy</strong>
            <strong class="text-center">
                {{ $cajaActiva->egresos->sum('monto') }} Bs <i class="fa fa-info-circle"></i>
            </strong>
           
        </div>
    </div>
</div>
@push('css')
    <style>
        .swal-popup-ventas, .swal-popup-gastos, .swal-popup-stock {
            padding: 0 !important;
            background: transparent !important;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15) !important;
        }
        .swal2-content{
            padding: 0 !important;
            margin: 0 !important;
        }
        .swal-popup-ventas .swal2-close, .swal-popup-gastos .swal2-close, .swal-popup-stock .swal2-close {
            background: white;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        /* Responsive styles for mobile */
        @media (max-width: 768px) {
            .swal-popup-ventas, .swal-popup-gastos, .swal-popup-stock {
                width: 95vw !important;
                max-width: 95vw !important;
                margin: 10px !important;
            }
            
            .swal2-popup {
                font-size: 13px !important;
            }

            /* Hacer tablas scrollables horizontalmente */
            .swal2-html-container table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch;
            }

            /* Ajustar padding en móviles */
            .swal2-html-container > div {
                padding: 10px !important;
            }

            /* Títulos más pequeños */
            .swal2-html-container h3 {
                font-size: 18px !important;
            }

            .swal2-html-container h5 {
                font-size: 14px !important;
            }

            /* Reducir padding de celdas */
            .swal2-html-container table th,
            .swal2-html-container table td {
                padding: 8px 6px !important;
                font-size: 12px !important;
            }

            /* Botones más pequeños en móviles */
            .swal2-html-container button {
                padding: 10px 12px !important;
                font-size: 13px !important;
            }

            /* Inputs más pequeños */
            .swal2-html-container input,
            .swal2-html-container select,
            .swal2-html-container textarea {
                padding: 8px !important;
                font-size: 13px !important;
            }
        }

        @media (max-width: 480px) {
            .swal-popup-ventas, .swal-popup-gastos, .swal-popup-stock {
                width: 98vw !important;
                max-width: 98vw !important;
                margin: 5px !important;
            }
            
            .swal2-popup {
                font-size: 12px !important;
            }

            /* Ajustar padding en móviles pequeños */
            .swal2-html-container > div {
                padding: 8px !important;
            }

            /* Títulos aún más pequeños */
            .swal2-html-container h3 {
                font-size: 16px !important;
            }

            .swal2-html-container h5 {
                font-size: 13px !important;
            }

            /* Reducir más el padding de celdas */
            .swal2-html-container table th,
            .swal2-html-container table td {
                padding: 6px 4px !important;
                font-size: 11px !important;
            }

            /* Hacer botón de control de stock más pequeño */
            .swal2-html-container button[onclick*="abrirControlDeStock"] {
                padding: 6px 10px !important;
                font-size: 11px !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Función helper para obtener ancho responsive
        function getResponsiveWidth(defaultWidth) {
            if (window.innerWidth < 480) {
                return '98%';
            } else if (window.innerWidth < 768) {
                return '95%';
            } else if (window.innerWidth < 1024) {
                return '90%';
            }
            return defaultWidth;
        }

        function showSweetAlertVentasHoy() {
            Livewire.emit('ventasHoy');
        }

        window.addEventListener('mostrarResumenVentas', event => {
            let data = event.detail;
            
            // Construir HTML para el resumen
            let htmlContent = `
                <div style="text-align: left; background: white; padding: 20px; border-radius: 10px;">
                    <!-- Total General -->
                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 25px; border-radius: 10px; margin-bottom: 25px; text-align: center; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); position: relative;">
                        <div style="color: white; font-size: 14px; margin-bottom: 8px; opacity: 0.9;">
                            <i class="fa fa-shopping-cart"></i> TOTAL VENTAS HOY
                        </div>
                        <div style="color: white; font-size: 36px; font-weight: bold; letter-spacing: 1px;">
                            ${parseFloat(data.totalVentas).toFixed(2)} <span style="font-size: 24px;">Bs</span>
                        </div>
                        <button onclick="abrirControlDeStock()" 
                            style="position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.2); border: 2px solid white; color: white; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 12px; transition: all 0.3s;"
                            onmouseover="this.style.background='rgba(255,255,255,0.3)'"
                            onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                            <i class="fa fa-cubes"></i> Control Stock
                        </button>
                    </div>

                    <!-- Ventas por Método de Pago -->
                    <div style="margin-bottom: 25px; background: #f8f9fa; padding: 15px; border-radius: 8px;">
                        <h5 style="color: #495057; margin: 0 0 15px 0; font-size: 16px; font-weight: 600;">
                            <i class="fa fa-credit-card" style="color: #667eea;"></i> Por Método de Pago
                        </h5>
                        <table style="width: 100%; border-collapse: separate; border-spacing: 0; background: white; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <thead>
                                <tr style="background: linear-gradient(to right, #667eea, #764ba2);">
                                    <th style="padding: 12px; text-align: left; color: white; font-weight: 600; font-size: 13px;">MÉTODO</th>
                                    <th style="padding: 12px; text-align: right; color: white; font-weight: 600; font-size: 13px;">MONTO (Bs)</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${Object.entries(data.ventasPorMetodo).map(([metodo, monto], index) => `
                                    <tr style="background: ${index % 2 === 0 ? '#ffffff' : '#f8f9fa'}; transition: all 0.2s;">
                                        <td style="padding: 12px; color: #495057; border-bottom: 1px solid #e9ecef;">
                                            <i class="fa fa-circle" style="font-size: 6px; color: #667eea; margin-right: 8px;"></i>
                                            ${metodo}
                                        </td>
                                        <td style="padding: 12px; text-align: right; font-weight: 600; color: #667eea; border-bottom: 1px solid #e9ecef;">
                                            ${parseFloat(monto).toFixed(2)}
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>

                    <!-- Ventas por Cajero -->
                    <div style="margin-bottom: 25px; background: #f8f9fa; padding: 15px; border-radius: 8px;">
                        <h5 style="color: #495057; margin: 0 0 15px 0; font-size: 16px; font-weight: 600;">
                            <i class="fa fa-user-tie" style="color: #667eea;"></i> Por Cajero
                        </h5>
                        <table style="width: 100%; border-collapse: separate; border-spacing: 0; background: white; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <thead>
                                <tr style="background: linear-gradient(to right, #667eea, #764ba2);">
                                    <th style="padding: 12px; text-align: left; color: white; font-weight: 600; font-size: 13px;">CAJERO</th>
                                    <th style="padding: 12px; text-align: right; color: white; font-weight: 600; font-size: 13px;">MONTO (Bs)</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.ventasPorCajero.map((cajero, index) => `
                                    <tr style="background: ${index % 2 === 0 ? '#ffffff' : '#f8f9fa'}; transition: all 0.2s;">
                                        <td style="padding: 12px; color: #495057; border-bottom: 1px solid #e9ecef;">
                                            <i class="fa fa-user" style="font-size: 10px; color: #667eea; margin-right: 8px;"></i>
                                            ${cajero.cajero_nombre}
                                        </td>
                                        <td style="padding: 12px; text-align: right; font-weight: 600; color: #667eea; border-bottom: 1px solid #e9ecef;">
                                            ${parseFloat(cajero.total_pagado).toFixed(2)}
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>

                    <!-- Productos Vendidos -->
                    <div style="margin-bottom: 10px; background: #f8f9fa; padding: 15px; border-radius: 8px;">
                        <h5 style="color: #495057; margin: 0 0 15px 0; font-size: 16px; font-weight: 600;">
                            <i class="fa fa-box-open" style="color: #667eea;"></i> Top 10 Productos Vendidos
                        </h5>
                        <div style="max-height: 320px; overflow-y: auto; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <table style="width: 100%; border-collapse: separate; border-spacing: 0; background: white;">
                                <thead style="position: sticky; top: 0; z-index: 10;">
                                    <tr style="background: linear-gradient(to right, #667eea, #764ba2);">
                                        <th style="padding: 12px; text-align: left; color: white; font-weight: 600; font-size: 13px; border-top-left-radius: 6px;">PRODUCTO</th>
                                        <th style="padding: 12px; text-align: center; color: white; font-weight: 600; font-size: 13px;">CANT.</th>
                                        <th style="padding: 12px; text-align: right; color: white; font-weight: 600; font-size: 13px; border-top-right-radius: 6px;">TOTAL (Bs)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.productosVendidos.slice(0, 10).map((producto, index) => `
                                        <tr style="background: ${index % 2 === 0 ? '#ffffff' : '#f8f9fa'};">
                                            <td style="padding: 12px; color: #495057; border-bottom: 1px solid #e9ecef;">
                                                <span style="background: ${index < 3 ? '#667eea' : '#e9ecef'}; color: ${index < 3 ? 'white' : '#495057'}; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; margin-right: 8px;">
                                                    ${index + 1}
                                                </span>
                                                ${producto.nombre}
                                            </td>
                                            <td style="padding: 12px; text-align: center; border-bottom: 1px solid #e9ecef;">
                                                <span style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 4px 12px; border-radius: 20px; font-weight: 700; font-size: 13px;">
                                                    ${producto.cantidad_total}
                                                </span>
                                            </td>
                                            <td style="padding: 12px; text-align: right; font-weight: 600; color: #667eea; border-bottom: 1px solid #e9ecef;">
                                                ${parseFloat(producto.suma_total).toFixed(2)}
                                            </td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;

            Swal.fire({
                html: htmlContent,
                width: getResponsiveWidth('850px'),
                showCloseButton: true,
                showConfirmButton: false,
                backdrop: true,
                allowOutsideClick: true,
                background: '#ffffff',
                customClass: {
                    popup: 'swal-popup-ventas',
                    title: 'swal-title-ventas'
                }
            });
        });

        function showSweetAlertGastosHoy() {
            Livewire.emit('gastosHoy');
        }

        window.addEventListener('mostrarResumenGastos', event => {
            let data = event.detail;
            
            // Construir HTML para el resumen de gastos
            let htmlContent = `
                <div style="text-align: left; background: white; padding: 20px; border-radius: 10px;">
                    <!-- Total de Egresos -->
                    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 25px; border-radius: 10px; margin-bottom: 25px; text-align: center; box-shadow: 0 4px 15px rgba(240, 147, 251, 0.4);">
                        <div style="color: white; font-size: 14px; margin-bottom: 8px; opacity: 0.9;">
                            <i class="fa fa-money"></i> TOTAL EGRESOS HOY
                        </div>
                        <div style="color: white; font-size: 36px; font-weight: bold; letter-spacing: 1px;">
                            ${parseFloat(data.totalEgresos).toFixed(2)} <span style="font-size: 24px;">Bs</span>
                        </div>
                    </div>

                    <!-- Listado de Egresos -->
                    <div style="margin-bottom: 25px; background: #f8f9fa; padding: 15px; border-radius: 8px;">
                        <h5 style="color: #495057; margin: 0 0 15px 0; font-size: 16px; font-weight: 600;">
                            <i class="fa fa-list" style="color: #f5576c;"></i> Registro de Egresos
                        </h5>
                        <div style="max-height: 350px; overflow-y: auto; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            ${data.egresos.length > 0 ? `
                                <table style="width: 100%; border-collapse: separate; border-spacing: 0; background: white;">
                                    <thead style="position: sticky; top: 0; z-index: 10;">
                                        <tr style="background: linear-gradient(to right, #f093fb, #f5576c);">
                                            <th style="padding: 12px; text-align: left; color: white; font-weight: 600; font-size: 13px; width: 40%;">DETALLE</th>
                                            <th style="padding: 12px; text-align: center; color: white; font-weight: 600; font-size: 13px; width: 25%;">MÉTODO</th>
                                            <th style="padding: 12px; text-align: right; color: white; font-weight: 600; font-size: 13px; width: 20%;">MONTO</th>
                                            <th style="padding: 12px; text-align: center; color: white; font-weight: 600; font-size: 13px; width: 15%;">HORA</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${data.egresos.map((egreso, index) => {
                                            const fecha = new Date(egreso.created_at);
                                            const hora = fecha.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
                                            return `
                                                <tr style="background: ${index % 2 === 0 ? '#ffffff' : '#f8f9fa'};">
                                                    <td style="padding: 12px; color: #495057; border-bottom: 1px solid #e9ecef;">
                                                        <i class="fa fa-circle" style="font-size: 6px; color: #f5576c; margin-right: 8px;"></i>
                                                        ${egreso.detalle || 'Sin detalle'}
                                                    </td>
                                                    <td style="padding: 12px; text-align: center; border-bottom: 1px solid #e9ecef;">
                                                        <span style="background: #f093fb; color: white; padding: 4px 10px; border-radius: 15px; font-size: 11px; font-weight: 600;">
                                                            ${egreso.metodo_pago ? egreso.metodo_pago.nombre_metodo_pago : 'N/A'}
                                                        </span>
                                                    </td>
                                                    <td style="padding: 12px; text-align: right; font-weight: 600; color: #f5576c; border-bottom: 1px solid #e9ecef;">
                                                        ${parseFloat(egreso.monto).toFixed(2)}
                                                    </td>
                                                    <td style="padding: 12px; text-align: center; color: #6c757d; font-size: 12px; border-bottom: 1px solid #e9ecef;">
                                                        ${hora}
                                                    </td>
                                                </tr>
                                            `;
                                        }).join('')}
                                    </tbody>
                                </table>
                            ` : `
                                <div style="text-align: center; padding: 40px; color: #6c757d; background: white; border-radius: 6px;">
                                    <i class="fa fa-inbox" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i>
                                    <p style="margin: 0; font-size: 14px;">No hay egresos registrados hoy</p>
                                </div>
                            `}
                        </div>
                    </div>

                    <!-- Formulario de Registro -->
                    <div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 20px; border-radius: 8px; border: 2px solid #f093fb;">
                        <h5 style="color: #495057; margin: 0 0 15px 0; font-size: 16px; font-weight: 600;">
                            <i class="fa fa-plus-circle" style="color: #f5576c;"></i> Registrar Nuevo Egreso
                        </h5>
                        
                        <div style="display: grid; gap: 12px;">
                            <!-- Detalle -->
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; color: #495057; margin-bottom: 5px;">
                                    Detalle
                                </label>
                                <input type="text" id="detalle_egreso" 
                                    style="width: 100%; padding: 10px; border: 1px solid #dee2e6; border-radius: 6px; font-size: 14px;"
                                    placeholder="Descripción del gasto (opcional)">
                            </div>

                            <!-- Grid 2 columnas -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                <!-- Método de Pago -->
                                <div>
                                    <label style="display: block; font-size: 13px; font-weight: 600; color: #495057; margin-bottom: 5px;">
                                        Método de Pago <span style="color: #f5576c;">*</span>
                                    </label>
                                    <select id="metodo_pago_egreso" 
                                        style="width: 100%; padding: 10px; border: 1px solid #dee2e6; border-radius: 6px; font-size: 14px;">
                                        <option value="">Seleccione...</option>
                                        ${data.metodosPago.map(metodo => `
                                            <option value="${metodo.id}">${metodo.nombre_metodo_pago}</option>
                                        `).join('')}
                                    </select>
                                </div>

                                <!-- Monto -->
                                <div>
                                    <label style="display: block; font-size: 13px; font-weight: 600; color: #495057; margin-bottom: 5px;">
                                        Monto (Bs) <span style="color: #f5576c;">*</span>
                                    </label>
                                    <input type="number" id="monto_egreso" step="0.01" min="0"
                                        style="width: 100%; padding: 10px; border: 1px solid #dee2e6; border-radius: 6px; font-size: 14px;"
                                        placeholder="0.00">
                                </div>
                            </div>

                            <!-- Botón Guardar -->
                            <button onclick="guardarNuevoEgreso()" 
                                style="width: 100%; padding: 12px; background: linear-gradient(135deg, #f093fb, #f5576c); color: white; border: none; border-radius: 6px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.3s;">
                                <i class="fa fa-save"></i> Guardar Egreso
                            </button>
                        </div>
                    </div>
                </div>
            `;

            Swal.fire({
                html: htmlContent,
                width: getResponsiveWidth('900px'),
                showCloseButton: true,
                showConfirmButton: false,
                backdrop: true,
                allowOutsideClick: true,
                background: '#ffffff',
                customClass: {
                    popup: 'swal-popup-gastos',
                    title: 'swal-title-gastos'
                },
                didOpen: () => {
                    // Focus en el primer campo
                    document.getElementById('detalle_egreso').focus();
                }
            });
        });

        // Función para guardar nuevo egreso
        function guardarNuevoEgreso() {
            const detalle = document.getElementById('detalle_egreso').value;
            const metodoPago = document.getElementById('metodo_pago_egreso').value;
            const monto = document.getElementById('monto_egreso').value;

            // Validaciones
            if (!metodoPago) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo requerido',
                    text: 'Debe seleccionar un método de pago',
                    confirmButtonColor: '#f5576c'
                });
                return;
            }

            if (!monto || parseFloat(monto) <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Monto inválido',
                    text: 'Debe ingresar un monto mayor a 0',
                    confirmButtonColor: '#f5576c'
                });
                return;
            }

            // Llamar al método de Livewire
            @this.call('guardarEgreso',detalle, monto, metodoPago).then(() => {
                // El modal se recargará automáticamente desde el método gastosHoy()
            });
        }

        // ==================== CONTROL DE STOCK ====================
        
        let productosSeleccionados = [];
        
        function abrirControlDeStock() {
            Swal.close(); // Cerrar el modal de ventas
            setTimeout(() => {
                productosSeleccionados = [];
                @this.call('abrirControlStock');
            }, 300);
        }

        window.addEventListener('mostrarControlStock', event => {
            let htmlContent = `
                <div style="text-align: left; background: white; padding: 20px; border-radius: 10px;">
                    <!-- Título -->
                    <div style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); padding: 20px; border-radius: 10px; margin-bottom: 25px; text-align: center; box-shadow: 0 4px 15px rgba(17, 153, 142, 0.4);">
                        <div style="color: white; font-size: 18px; font-weight: bold;">
                            <i class="fa fa-cubes"></i> Control de Stock de Productos
                        </div>
                    </div>

                    <!-- Buscador -->
                    <div style="margin-bottom: 20px; background: #f8f9fa; padding: 15px; border-radius: 8px;">
                        <label style="display: block; font-size: 14px; font-weight: 600; color: #495057; margin-bottom: 8px;">
                            <i class="fa fa-search"></i> Buscar Producto
                        </label>
                        <input type="text" id="buscador_producto" 
                            oninput="buscarProductoStock(this.value)"
                            style="width: 100%; padding: 12px; border: 2px solid #11998e; border-radius: 6px; font-size: 14px;"
                            placeholder="Nombre o código de barras...">
                        <div id="resultados_busqueda" style="margin-top: 10px;"></div>
                    </div>

                    <!-- Tabla de Productos Seleccionados -->
                    <div style="margin-bottom: 20px; background: #f8f9fa; padding: 15px; border-radius: 8px;">
                        <h5 style="color: #495057; margin: 0 0 15px 0; font-size: 16px; font-weight: 600;">
                            <i class="fa fa-list-alt" style="color: #11998e;"></i> Productos Seleccionados
                        </h5>
                        <div id="tabla_productos_stock" style="max-height: 400px; overflow-y: auto; border-radius: 6px;">
                            <p style="text-align: center; color: #6c757d; padding: 40px;">
                                No hay productos seleccionados. Use el buscador para agregar productos.
                            </p>
                        </div>
                    </div>

                    <!-- Detalle General -->
                    <div style="margin-bottom: 20px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 15px; border-radius: 8px; border: 2px solid #11998e;">
                        <label style="display: block; font-size: 14px; font-weight: 600; color: #495057; margin-bottom: 8px;">
                            Detalle General del Registro <span style="color: #11998e;">*</span>
                        </label>
                        <textarea id="detalle_general_stock" rows="2"
                            style="width: 100%; padding: 10px; border: 1px solid #dee2e6; border-radius: 6px; font-size: 14px; resize: vertical;"
                            placeholder="Motivo del reajuste de stock..."></textarea>
                    </div>

                    <!-- Botón Confirmar -->
                    <button onclick="confirmarReajustesStock()" 
                        style="width: 100%; padding: 14px; background: linear-gradient(135deg, #11998e, #38ef7d); color: white; border: none; border-radius: 6px; font-weight: 600; font-size: 15px; cursor: pointer;">
                        <i class="fa fa-check-circle"></i> Confirmar Reajustes
                    </button>
                </div>
            `;

            Swal.fire({
                html: htmlContent,
                width: getResponsiveWidth('1000px'),
                showCloseButton: true,
                showConfirmButton: false,
                backdrop: true,
                allowOutsideClick: true,
                background: '#ffffff',
                customClass: {
                    popup: 'swal-popup-stock'
                },
                didOpen: () => {
                    document.getElementById('buscador_producto').focus();
                }
            });
        });

        let timeoutBusqueda = null;
        function buscarProductoStock(termino) {
            clearTimeout(timeoutBusqueda);
            
            if (termino.length < 2) {
                document.getElementById('resultados_busqueda').innerHTML = '';
                return;
            }

            timeoutBusqueda = setTimeout(() => {
                @this.call('buscarProducto', termino);
            }, 300);
        }

        window.addEventListener('resultadosBusqueda', event => {
            let productos = event.detail.productos;
            let html = '';

            if (productos.length === 0) {
                html = '<p style="color: #6c757d; font-size: 13px; padding: 10px;">No se encontraron productos</p>';
            } else {
                html = '<div style="background: white; border-radius: 6px; border: 1px solid #dee2e6; max-height: 200px; overflow-y: auto;">';
                productos.forEach(producto => {
                    // Verificar si ya está seleccionado
                    let yaSeleccionado = productosSeleccionados.some(p => p.id === producto.id);
                    let sinStock = !producto.tiene_stock;
                    
                    // Determinar si se puede hacer click
                    let onClick = '';
                    let cursor = 'pointer';
                    let opacity = '';
                    let backgroundColor = '';
                    let mensaje = '';
                    
                    if (sinStock) {
                        onClick = "onclick=\"mostrarToast('error', 'Este producto no tiene stock. Debe agregar stock desde la sección de Almacén')\"";
                        cursor = 'not-allowed';
                        opacity = 'opacity: 0.6;';
                        backgroundColor = 'background: #ffebee;';
                        mensaje = '<span style="color: #f5576c; font-weight: bold;"> - Sin stock disponible</span>';
                    } else if (yaSeleccionado) {
                        onClick = '';
                        cursor = 'not-allowed';
                        opacity = 'opacity: 0.5;';
                        backgroundColor = 'background: #f8f9fa;';
                        mensaje = '<span style="color: #11998e; font-weight: bold;"> - Ya seleccionado</span>';
                    } else {
                        onClick = "onclick=\"agregarProductoStock(" + producto.id + ", '" + producto.nombre.replace(/'/g, "\\'") + "', " + producto.stock_actual + ", " + producto.vendidos_caja + ")\"";
                    }
                    
                    let hoverEvents = (!yaSeleccionado && !sinStock) ? 'onmouseover="this.style.background=\'#e3f2fd\'" onmouseout="this.style.background=\'white\'"' : '';
                    
                    html += `
                        <div ${onClick}
                            style="padding: 10px; border-bottom: 1px solid #e9ecef; cursor: ${cursor}; ${opacity} ${backgroundColor}; color: black;"
                            ${hoverEvents}>
                            <strong>${producto.nombre}</strong><br>
                            <small style="color: #6c757d;">
                                Stock: ${producto.stock_actual} | Vendidos hoy: ${producto.vendidos_caja}
                                ${mensaje}
                            </small>
                        </div>
                    `;
                });
                html += '</div>';
            }

            document.getElementById('resultados_busqueda').innerHTML = html;
        });

        function agregarProductoStock(id, nombre, stockActual, vendidosCaja) {
            // Verificar si ya existe
            if (productosSeleccionados.some(p => p.id === id)) {
                return;
            }

            productosSeleccionados.push({
                id: id,
                nombre: nombre,
                stock_actual: stockActual,
                vendidos_caja: vendidosCaja,
                nuevo_stock: stockActual,
                detalle: '',
                reajustando: false
            });

            actualizarTablaProductosStock();
            document.getElementById('buscador_producto').value = '';
            document.getElementById('resultados_busqueda').innerHTML = '';
        }

        function actualizarTablaProductosStock() {
            if (productosSeleccionados.length === 0) {
                document.getElementById('tabla_productos_stock').innerHTML = `
                    <p style="text-align: center; color: #6c757d; padding: 40px;">
                        No hay productos seleccionados. Use el buscador para agregar productos.
                    </p>
                `;
                return;
            }

            let html = `
                <table style="width: 100%; border-collapse: separate; border-spacing: 0; background: white; border-radius: 6px; overflow: hidden;">
                    <thead style="position: sticky; top: 0; z-index: 10;">
                        <tr style="background: linear-gradient(to right, #11998e, #38ef7d);">
                            <th style="padding: 12px; text-align: left; color: white; font-weight: 600; font-size: 13px; width: 30%;">PRODUCTO</th>
                            <th style="padding: 12px; text-align: center; color: white; font-weight: 600; font-size: 13px; width: 12%;">STOCK ACTUAL</th>
                            <th style="padding: 12px; text-align: center; color: white; font-weight: 600; font-size: 13px; width: 12%;">VENDIDOS</th>
                            <th style="padding: 12px; text-align: center; color: white; font-weight: 600; font-size: 13px; width: 20%;">NUEVO STOCK</th>
                            <th style="padding: 12px; text-align: left; color: white; font-weight: 600; font-size: 13px; width: 22%;">MOTIVO</th>
                            <th style="padding: 12px; text-align: center; color: white; font-weight: 600; font-size: 13px; width: 12%;">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            productosSeleccionados.forEach((producto, index) => {
                let cambioStock = producto.nuevo_stock - producto.stock_actual;
                let colorCambio = cambioStock > 0 ? '#11998e' : (cambioStock < 0 ? '#f5576c' : '#6c757d');
                let textoCambio = cambioStock > 0 ? '+' + cambioStock : cambioStock;

                html += `
                    <tr style="background: ${index % 2 === 0 ? '#ffffff' : '#f8f9fa'}; border-bottom: 1px solid #e9ecef;">
                        <td style="padding: 12px;">
                            <strong style="color: #495057;">${producto.nombre}</strong>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="background: #e9ecef; padding: 4px 10px; border-radius: 15px; font-weight: 600;">
                                ${producto.stock_actual}
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="background: #667eea; color: white; padding: 4px 10px; border-radius: 15px; font-weight: 600;">
                                ${producto.vendidos_caja}
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            ${producto.reajustando ? `
                                <input type="number" value="${producto.nuevo_stock}" 
                                    onchange="actualizarNuevoStock(${index}, this.value)"
                                    style="width: 80px; padding: 6px; border: 2px solid #11998e; border-radius: 4px; text-align: center; font-weight: 600;">
                            ` : `
                                <span style="background: ${colorCambio}; color: white; padding: 4px 10px; border-radius: 15px; font-weight: 600;">
                                    ${producto.nuevo_stock} ${cambioStock !== 0 ? '(' + textoCambio + ')' : ''}
                                </span>
                            `}
                        </td>
                        <td style="padding: 12px;">
                            ${producto.reajustando ? `
                                <input type="text" value="${producto.detalle}" 
                                    onchange="actualizarDetalleProducto(${index}, this.value)"
                                    placeholder="Motivo del cambio"
                                    style="width: 100%; padding: 6px; border: 1px solid #dee2e6; border-radius: 4px; font-size: 12px;">
                            ` : `
                                <span style="color: #6c757d; font-size: 12px;">
                                    ${producto.detalle || '-'}
                                </span>
                            `}
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            ${producto.reajustando ? `
                                <button onclick="guardarReajuste(${index})" 
                                    style="background: #11998e; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px; margin-right: 4px;">
                                    <i class="fa fa-check"></i>
                                </button>
                                <button onclick="cancelarReajuste(${index})" 
                                    style="background: #6c757d; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px;">
                                    <i class="fa fa-times"></i>
                                </button>
                            ` : `
                                <button onclick="iniciarReajuste(${index})" 
                                    style="background: #11998e; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px; margin-right: 4px;">
                                    <i class="fa fa-edit"></i> Reajustar
                                </button>
                                <button onclick="eliminarProductoStock(${index})" 
                                    style="background: #f5576c; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px;">
                                    <i class="fa fa-trash"></i>
                                </button>
                            `}
                        </td>
                    </tr>
                `;
            });

            html += '</tbody></table>';
            document.getElementById('tabla_productos_stock').innerHTML = html;
        }

        function iniciarReajuste(index) {
            productosSeleccionados[index].reajustando = true;
            productosSeleccionados[index].nuevo_stock_temp = productosSeleccionados[index].nuevo_stock;
            actualizarTablaProductosStock();
        }

        function cancelarReajuste(index) {
            productosSeleccionados[index].reajustando = false;
            productosSeleccionados[index].nuevo_stock = productosSeleccionados[index].stock_actual;
            productosSeleccionados[index].detalle = '';
            actualizarTablaProductosStock();
        }

        function guardarReajuste(index) {
            productosSeleccionados[index].reajustando = false;
            actualizarTablaProductosStock();
        }

        function actualizarNuevoStock(index, valor) {
            productosSeleccionados[index].nuevo_stock = parseInt(valor) || 0;
        }

        function actualizarDetalleProducto(index, valor) {
            productosSeleccionados[index].detalle = valor;
        }

        function eliminarProductoStock(index) {
            productosSeleccionados.splice(index, 1);
            actualizarTablaProductosStock();
        }

        function confirmarReajustesStock() {
            let detalleGeneral = document.getElementById('detalle_general_stock').value.trim();

            // Validación: Debe haber productos seleccionados
            if (productosSeleccionados.length === 0) {
                mostrarToast('warning', 'Debe seleccionar al menos un producto');
                return;
            }

            // Validación: Detalle general requerido
            if (!detalleGeneral) {
                mostrarToast('warning', 'Debe ingresar un detalle general del registro');
                document.getElementById('detalle_general_stock').focus();
                document.getElementById('detalle_general_stock').style.borderColor = '#f5576c';
                setTimeout(() => {
                    document.getElementById('detalle_general_stock').style.borderColor = '#dee2e6';
                }, 2000);
                return;
            }

            // Validación: Si hay productos en modo reajuste, advertir
            let hayReajustando = productosSeleccionados.some(p => p.reajustando);
            if (hayReajustando) {
                mostrarToast('warning', 'Hay productos en modo edición. Guarde o cancele los cambios antes de confirmar');
                return;
            }

            // Validación: Si hay cambios de stock, debe haber detalle específico
            let productosConCambioSinDetalle = productosSeleccionados.filter(p => {
                let cambio = p.nuevo_stock - p.stock_actual;
                return cambio !== 0 && (!p.detalle || p.detalle.trim() === '');
            });

            if (productosConCambioSinDetalle.length > 0) {
                let nombres = productosConCambioSinDetalle.map(p => p.nombre).join(', ');
                mostrarToast('warning', 'Los productos con cambios de stock requieren un motivo: ' + nombres);
                return;
            }

            // Validación: Nuevo stock no puede ser negativo
            let productosNegativos = productosSeleccionados.filter(p => p.nuevo_stock < 0);
            if (productosNegativos.length > 0) {
                let nombres = productosNegativos.map(p => p.nombre).join(', ');
                mostrarToast('error', 'El nuevo stock no puede ser negativo: ' + nombres);
                return;
            }

            // Mostrar previsualización
            mostrarPrevisualizacionReajustes(detalleGeneral);
        }

        function mostrarPrevisualizacionReajustes(detalleGeneral) {
            // Separar productos por acción
            let aumentos = [];
            let disminuciones = [];
            let sinCambio = [];

            productosSeleccionados.forEach(p => {
                let cambio = p.nuevo_stock - p.stock_actual;
                if (cambio > 0) {
                    aumentos.push({ ...p, cambio: cambio });
                } else if (cambio < 0) {
                    disminuciones.push({ ...p, cambio: Math.abs(cambio) });
                } else {
                    sinCambio.push(p);
                }
            });

            let htmlContent = `
                <div style="text-align: left; background: white; padding: 20px; border-radius: 10px;">
                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 10px; margin-bottom: 20px; text-align: center;">
                        <h3 style="color: white; margin: 0;">
                            <i class="fa fa-eye"></i> Previsualización de Reajustes
                        </h3>
                        <p style="color: white; margin: 10px 0 0 0; opacity: 0.9;">
                            ${detalleGeneral}
                        </p>
                    </div>

                    ${aumentos.length > 0 ? `
                        <div style="margin-bottom: 20px;">
                            <h5 style="color: #11998e; margin-bottom: 10px;">
                                <i class="fa fa-arrow-up"></i> Aumentos de Stock (${aumentos.length})
                            </h5>
                            <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <thead>
                                    <tr style="background: #11998e; color: white;">
                                        <th style="padding: 10px; text-align: left;">Producto</th>
                                        <th style="padding: 10px; text-align: center;">Stock Actual</th>
                                        <th style="padding: 10px; text-align: center;">Aumento</th>
                                        <th style="padding: 10px; text-align: center;">Nuevo Stock</th>
                                        <th style="padding: 10px; text-align: left;">Motivo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${aumentos.map((p, idx) => `
                                        <tr style="background: ${idx % 2 === 0 ? '#ffffff' : '#f8f9fa'}; border-bottom: 1px solid #e9ecef;">
                                            <td style="padding: 10px;">${p.nombre}</td>
                                            <td style="padding: 10px; text-align: center;">${p.stock_actual}</td>
                                            <td style="padding: 10px; text-align: center;">
                                                <strong style="color: #11998e;">+${p.cambio}</strong>
                                            </td>
                                            <td style="padding: 10px; text-align: center;">
                                                <strong>${p.nuevo_stock}</strong>
                                            </td>
                                            <td style="padding: 10px; font-size: 12px;">${p.detalle}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    ` : ''}

                    ${disminuciones.length > 0 ? `
                        <div style="margin-bottom: 20px;">
                            <h5 style="color: #f5576c; margin-bottom: 10px;">
                                <i class="fa fa-arrow-down"></i> Disminuciones de Stock (${disminuciones.length})
                            </h5>
                            <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <thead>
                                    <tr style="background: #f5576c; color: white;">
                                        <th style="padding: 10px; text-align: left;">Producto</th>
                                        <th style="padding: 10px; text-align: center;">Stock Actual</th>
                                        <th style="padding: 10px; text-align: center;">Disminución</th>
                                        <th style="padding: 10px; text-align: center;">Nuevo Stock</th>
                                        <th style="padding: 10px; text-align: left;">Motivo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${disminuciones.map((p, idx) => `
                                        <tr style="background: ${idx % 2 === 0 ? '#ffffff' : '#f8f9fa'}; border-bottom: 1px solid #e9ecef;">
                                            <td style="padding: 10px;">${p.nombre}</td>
                                            <td style="padding: 10px; text-align: center;">${p.stock_actual}</td>
                                            <td style="padding: 10px; text-align: center;">
                                                <strong style="color: #f5576c;">-${p.cambio}</strong>
                                            </td>
                                            <td style="padding: 10px; text-align: center;">
                                                <strong>${p.nuevo_stock}</strong>
                                            </td>
                                            <td style="padding: 10px; font-size: 12px;">${p.detalle}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    ` : ''}

                    ${sinCambio.length > 0 ? `
                        <div style="margin-bottom: 20px;">
                            <h5 style="color: #6c757d; margin-bottom: 10px;">
                                <i class="fa fa-minus"></i> Sin Cambios (${sinCambio.length})
                            </h5>
                            <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <thead>
                                    <tr style="background: #6c757d; color: white;">
                                        <th style="padding: 10px; text-align: left;">Producto</th>
                                        <th style="padding: 10px; text-align: center;">Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${sinCambio.map((p, idx) => `
                                        <tr style="background: ${idx % 2 === 0 ? '#ffffff' : '#f8f9fa'}; border-bottom: 1px solid #e9ecef;">
                                            <td style="padding: 10px;">${p.nombre}</td>
                                            <td style="padding: 10px; text-align: center;">${p.stock_actual}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    ` : ''}

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 20px;">
                        <button onclick="Swal.close()" 
                            style="padding: 12px; background: #6c757d; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                            <i class="fa fa-times"></i> Cancelar
                        </button>
                        <button onclick="ejecutarReajustes('${detalleGeneral.replace(/'/g, "\\'")}')" 
                            style="padding: 12px; background: linear-gradient(135deg, #11998e, #38ef7d); color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                            <i class="fa fa-check"></i> Confirmar y Guardar
                        </button>
                    </div>
                </div>
            `;

            Swal.fire({
                html: htmlContent,
                width: getResponsiveWidth('900px'),
                showCloseButton: false,
                showConfirmButton: false,
                backdrop: true,
                allowOutsideClick: false,
                background: '#ffffff',
                customClass: {
                    popup: 'swal-popup-stock'
                }
            });
        }

        function ejecutarReajustes(detalleGeneral) {
            // Cerrar previsualización
            Swal.close();

            // Preparar datos para enviar
            let productosReajustados = productosSeleccionados.map(p => ({
                producto_id: p.id,
                nuevo_stock: parseInt(p.nuevo_stock),
                detalle: p.detalle || 'Sin detalle específico'
            }));

            // Mostrar loading
            Swal.fire({
                html: '<div style="text-align: center; padding: 40px;"><i class="fa fa-spinner fa-spin" style="font-size: 48px; color: #11998e;"></i><p style="margin-top: 20px; font-size: 16px;">Procesando reajustes...</p></div>',
                width: getResponsiveWidth('500px'),
                showConfirmButton: false,
                backdrop: true,
                allowOutsideClick: false,
                background: '#ffffff',
                customClass: {
                    popup: 'swal-popup-stock'
                }
            });

            @this.call('confirmarReajustes', productosReajustados, detalleGeneral);
        }

        function mostrarToast(tipo, mensaje) {
            window.dispatchEvent(new CustomEvent('toastAlert', {
                detail: { type: tipo, message: mensaje }
            }));
        }

        window.addEventListener('cerrarControlStock', event => {
            // Cerrar cualquier modal abierto y limpiar
            Swal.close();
            productosSeleccionados = [];
            
            // Volver a abrir el modal de ventas si se desea
            // setTimeout(() => {
            //     Livewire.emit('ventasHoy');
            // }, 300);
        });

        // Listener para re-habilitar el botón cuando hay toast (error o cualquier mensaje)
        // y el modal no se cierra
        window.addEventListener('toastAlert', event => {
            setTimeout(() => {
                let botones = document.querySelectorAll('button');
                botones.forEach(btn => {
                    if (btn.disabled && btn.innerHTML.includes('Procesando')) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa fa-check-circle"></i> Confirmar Reajustes';
                    }
                });
            }, 100);
        });
    </script>
@endpush