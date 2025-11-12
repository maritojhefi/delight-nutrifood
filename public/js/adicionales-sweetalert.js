// SweetAlert para selección de adicionales de productos
document.addEventListener("DOMContentLoaded", function () {
    // Escuchar el evento de Livewire para mostrar el SweetAlert
    window.addEventListener("mostrarSweetAlertAdicionales", function (event) {
        const { producto, grupos } = event.detail;
        mostrarSweetAlertAdicionales(producto, grupos);
    });
});

function mostrarSweetAlertAdicionales(producto, grupos) {
    // Crear el HTML del modal
    const html = crearHTMLModal(producto, grupos);

    Swal.fire({
        customClass: {
            popup: "swal-fondo-blanco swal-compacto",
            confirmButton: "swal-btn-confirmar",
            cancelButton: "swal-btn-cancelar",
        },
        title: "Personaliza tu orden",
        html: html,
        width: "95%",
        maxWidth: "700px",
        showCancelButton: true,
        confirmButtonText: "AGREGAR PEDIDO",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#20c996",
        cancelButtonColor: "#dc3545",
        allowOutsideClick: false,
        showLoaderOnConfirm: true,
        buttonsStyling: true,
        preConfirm: () => {
            return validarYProcesarSeleccion(producto, grupos);
        },
        didOpen: () => {
            inicializarEventosModal();
        },
    });
}

function crearHTMLModal(producto, grupos) {
    let html = `
        <div class="modal-adicionales">
            <!-- Cabecera con información del producto -->
            <div class="producto-header mb-3">
                <div class="producto-info-wrapper">
                    <div class="producto-detalle">
                        <img src="${producto.imagen}" alt="${
        producto.nombre
    }" class="producto-imagen">
                        <div class="producto-texto">
                            <h6 class="producto-nombre mb-1">${
                                producto.nombre
                            }</h6>
                            <p class="producto-precio mb-0">Bs. ${producto.precio.toFixed(
                                2
                            )}</p>
                          
                        </div>
                    </div>
                    
                    <div class="cantidad-control">
                      <strong class="producto-cantidad mb-0 text-white">Existencias: ${
                          producto.cantidad
                              ? producto.cantidad + " disp."
                              : "Ilimitado"
                      } </strong>
                        <label class="cantidad-label">Cantidad</label>
                        <div class="cantidad-buttons">
                            <button type="button" class="btn-cantidad btn-menos" id="btn-disminuir">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </button>
                            <input type="number" class="cantidad-input" id="cantidad" value="1" min="1" max="${
                                producto.cantidad
                            }" readonly>
                            <button type="button" class="btn-cantidad btn-mas" id="btn-aumentar">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Grupos de adicionales -->
            <div class="adicionales-container">
    `;

    // Agregar grupos de adicionales
    grupos.forEach((grupo) => {
        const esObligatorio = grupo.es_obligatorio;
        const maximoSeleccionable = grupo.maximo_seleccionable;
        const tipoInput = maximoSeleccionable === 1 ? "radio" : "checkbox";

        html += `
            <div class="grupo-wrapper">
                <div class="grupo-adicionales">
                    <div class="grupo-header">
                        <h6 class="grupo-titulo">
                            ${grupo.nombre ? grupo.nombre : "OTROS"}
                            ${
                                esObligatorio
                                    ? '<span class="badge-obligatorio">Requerido</span>'
                                    : ""
                            }
                        </h6>
                        ${
                            maximoSeleccionable > 1
                                ? `<span class="grupo-max">Máx. ${maximoSeleccionable}</span>`
                                : ""
                        }
                    </div>
                    
                    <div class="grupo-opciones" data-grupo-id="${
                        grupo.id
                    }" data-maximo="${maximoSeleccionable}" data-obligatorio="${esObligatorio}">
        `;

        grupo.adicionales.forEach((adicional) => {
            const precioTexto =
                adicional.precio > 0
                    ? `+Bs. ${adicional.precio.toFixed(2)}`
                    : "";
            const sinStock = adicional.contable && adicional.cantidad <= 0;

            // Mostrar cantidad disponible si es contable
            let cantidadHTML = "";
            if (adicional.contable) {
                const colorCantidad =
                    adicional.cantidad <= 0
                        ? "sin-stock"
                        : adicional.cantidad <= 5
                        ? "poco-stock"
                        : "con-stock";
                cantidadHTML = `<span class="stock-badge ${colorCantidad}">${adicional.cantidad} disp.</span>`;
            }

            html += `
                <label class="opcion-item ${
                    sinStock ? "sin-stock-item" : ""
                }" for="adicional_${adicional.id}">
                    <input class="opcion-input" type="${tipoInput}" 
                           name="grupo_${grupo.id}" 
                           id="adicional_${adicional.id}" 
                           value="${adicional.id}"
                           ${sinStock ? "disabled" : ""}>
                    <div class="opcion-content">
                        <span class="opcion-nombre">${
                            adicional.nombre
                        } ${cantidadHTML}</span>
                        ${
                            precioTexto
                                ? `<span class="opcion-precio">${precioTexto}</span>`
                                : ""
                        }
                    </div>
                </label>
            `;
        });

        html += `
                    </div>
                    <div class="grupo-contador" id="contador_${grupo.id}"></div>
                </div>
            </div>
        `;
    });

    html += `
            </div>
            
            <!-- Resumen y observaciones -->
            <div class="footer-section">
                <div class="resumen-card">
                    <div class="resumen-header">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                            <path d="M3 9h18M9 21V9"></path>
                        </svg>
                        <span>Resumen</span>
                    </div>
                    <div class="resumen-linea">
                        <span>Costo Unitario:</span>
                        <span id="precio-producto">Bs. ${producto.precio.toFixed(
                            2
                        )}</span>
                    </div>
                    <div class="resumen-linea">
                        <span>Adicionales:</span>
                        <span id="precio-adicionales">Bs. 0.00</span>
                    </div>
                    <div class="resumen-divider"></div>
                    <div class="resumen-total">
                        <span>Total:</span>
                        <span id="precio-total">Bs. ${producto.precio.toFixed(
                            2
                        )}</span>
                    </div>
                </div>
                
                <div class="observaciones-card">
                    <label class="observaciones-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        Observaciones (opcional)
                    </label>
                    <textarea class="observaciones-textarea" id="observacion-producto" placeholder="Ej: Sin cebolla, poco sal, etc." rows="2"></textarea>
                </div>
            </div>
        </div>
    `;

    return html;
}

function inicializarEventosModal() {
    // Eventos para cantidad
    document.getElementById("btn-disminuir").addEventListener("click", () => {
        const cantidad = document.getElementById("cantidad");
        if (cantidad.value > 1) {
            cantidad.value = parseInt(cantidad.value) - 1;
            actualizarPrecios();
        }
    });

    document.getElementById("btn-aumentar").addEventListener("click", () => {
        const cantidad = document.getElementById("cantidad");
        const valorActual = parseInt(cantidad.value);
        const valorMaximo = parseInt(cantidad.max);

        // Verificar si hay un máximo establecido y no excederlo
        if (!valorMaximo || valorActual < valorMaximo) {
            cantidad.value = valorActual + 1;
            actualizarPrecios();
        } else {
            // Mostrar mensaje cuando se alcanza el máximo
            Swal.showValidationMessage(
                `Cantidad máxima disponible: ${valorMaximo}`
            );
            setTimeout(() => {
                Swal.resetValidationMessage();
            }, 2000);
        }
    });

    document
        .getElementById("cantidad")
        .addEventListener("change", actualizarPrecios);

    // Eventos para adicionales
    document
        .querySelectorAll('input[type="radio"], input[type="checkbox"]')
        .forEach((input) => {
            input.addEventListener("change", function () {
                const grupo = this.closest(".grupo-opciones");
                const grupoId = grupo.dataset.grupoId;
                const maximo = parseInt(
                    grupo.dataset.maximo ? grupo.dataset.maximo : 5
                );
                const grupoContainer = this.closest(".grupo-adicionales");

                // Quitar el error visual al seleccionar
                if (grupoContainer) {
                    grupoContainer.classList.remove("grupo-error");
                }

                if (this.type === "checkbox") {
                    const seleccionados =
                        grupo.querySelectorAll("input:checked");
                    if (seleccionados.length > maximo) {
                        this.checked = false;
                        Swal.showValidationMessage(
                            `Solo puedes seleccionar máximo ${maximo} opciones en ${grupo.previousElementSibling.textContent.trim()}`
                        );
                        return;
                    }
                }

                actualizarContadorGrupo(grupoId);
                actualizarPrecios();
            });
        });
}

function actualizarContadorGrupo(grupoId) {
    const grupo = document.querySelector(`[data-grupo-id="${grupoId}"]`);
    const seleccionados = grupo.querySelectorAll("input:checked");
    const contador = document.getElementById(`contador_${grupoId}`);
    const maximo = parseInt(grupo.dataset.maximo);

    if (maximo > 1) {
        contador.textContent = `${seleccionados.length} de ${maximo} seleccionados`;
    } else {
        contador.textContent = "";
    }
}

function actualizarPrecios() {
    const precioProducto = parseFloat(
        document
            .getElementById("precio-producto")
            .textContent.replace("Bs. ", "")
    );
    const cantidad = parseInt(document.getElementById("cantidad").value);

    let precioAdicionales = 0;
    document.querySelectorAll("input:checked").forEach((input) => {
        const precioElement =
            input.parentElement.querySelector(".opcion-precio");
        if (precioElement) {
            const precioTexto = precioElement.textContent;
            if (precioTexto.includes("+Bs.")) {
                const precio = parseFloat(precioTexto.replace("+Bs. ", ""));
                precioAdicionales += precio;
            }
        }
    });

    const precioTotal = (precioProducto + precioAdicionales) * cantidad;

    document.getElementById(
        "precio-adicionales"
    ).textContent = `Bs. ${precioAdicionales.toFixed(2)}`;
    document.getElementById(
        "precio-total"
    ).textContent = `Bs. ${precioTotal.toFixed(2)}`;
}

function validarYProcesarSeleccion(producto, grupos) {
    const cantidad = parseInt(document.getElementById("cantidad").value);
    const observacion = document
        .getElementById("observacion-producto")
        .value.trim();
    const adicionalesSeleccionados = [];

    // Limpiar errores previos
    document.querySelectorAll(".grupo-adicionales").forEach((grupo) => {
        grupo.classList.remove("grupo-error");
    });

    // Validar grupos obligatorios
    for (const grupo of grupos) {
        const grupoElement = document.querySelector(
            `[data-grupo-id="${grupo.id}"]`
        );
        const grupoContainer = grupoElement.closest(".grupo-adicionales");
        const seleccionados = grupoElement.querySelectorAll("input:checked");

        if (grupo.es_obligatorio && seleccionados.length === 0) {
            // Resaltar el grupo con error
            grupoContainer.classList.add("grupo-error");
            Swal.showValidationMessage(
                `Debes seleccionar al menos una opción en ${
                    grupo.nombre || "este grupo"
                }`
            );
            return false;
        }
        if (grupo.maximo_seleccionable == null) {
            grupo.maximo_seleccionable = 5;
        }
        if (seleccionados.length > grupo.maximo_seleccionable) {
            grupoContainer.classList.add("grupo-error");
            Swal.showValidationMessage(
                `Solo puedes seleccionar máximo ${
                    grupo.maximo_seleccionable
                } opciones en ${grupo.nombre || "este grupo"}`
            );
            return false;
        }

        // Agregar adicionales seleccionados
        seleccionados.forEach((input) => {
            adicionalesSeleccionados.push(parseInt(input.value));
        });
    }

    // Enviar datos a Livewire incluyendo observación
    Livewire.emit(
        "agregarProductoConAdicionales",
        producto.id,
        adicionalesSeleccionados,
        cantidad,
        observacion
    );

    return true;
}

// Estilos CSS para el modal
const style = document.createElement("style");
style.textContent = `
    /* ===== CONFIGURACIÓN GENERAL ===== */
    .swal-compacto {
        font-size: 0.875rem !important;
    }
    
    .swal-compacto .swal2-title {
        font-size: 1.25rem !important;
        margin-bottom: 1rem !important;
        font-weight: 700;
        color: #2c3e50;
    }
    
    .swal-compacto .swal2-html-container {
        margin: 0 !important;
        padding: 0 !important;
        overflow-y: auto;
        max-height: 70vh;
    }
    
    .modal-adicionales {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    
    /* ===== CABECERA DEL PRODUCTO ===== */
    .modal-adicionales .producto-header {
        background: linear-gradient(135deg, #20c996 0%, #17a679 100%);
        padding: 1rem;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(32, 201, 150, 0.15);
    }
    
    .modal-adicionales .producto-info-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }
    
    .modal-adicionales .producto-detalle {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex: 1;
    }
    
    .modal-adicionales .producto-imagen {
        width: 56px;
        height: 56px;
        object-fit: cover;
        border-radius: 10px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    .modal-adicionales .producto-texto {
        color: white;
    }
    
    .modal-adicionales .producto-nombre {
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.3;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }
    
    .modal-adicionales .producto-precio {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        opacity: 0.95;
    }
    
    /* ===== CONTROL DE CANTIDAD ===== */
    .modal-adicionales .cantidad-control {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.4rem;
    }
    
    .modal-adicionales .cantidad-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
    }
    
    .modal-adicionales .cantidad-buttons {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.95);
        padding: 0.35rem;
        border-radius: 50px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .modal-adicionales .btn-cantidad {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 50%;
        background: white;
        color: #20c996;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }
    
    .modal-adicionales .btn-cantidad:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    .modal-adicionales .btn-menos {
        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
        color: white;
    }
    
    .modal-adicionales .btn-mas {
        background: linear-gradient(135deg, #20c996, #17a679);
        color: white;
    }
    
    .modal-adicionales .cantidad-input {
        width: 50px;
        height: 36px;
        border: none;
        background: transparent;
        text-align: center;
        font-size: 1.1rem;
        font-weight: 700;
        color: #2c3e50;
        -moz-appearance: textfield;
    }
    
    .modal-adicionales .cantidad-input::-webkit-outer-spin-button,
    .modal-adicionales .cantidad-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    /* ===== CONTENEDOR DE ADICIONALES ===== */
    .modal-adicionales .adicionales-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1rem;
        margin: 1rem 0;
    }
    
    .modal-adicionales .grupo-wrapper {
        display: flex;
        flex-direction: column;
    }
    
    .modal-adicionales .grupo-adicionales {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 0.875rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .modal-adicionales .grupo-adicionales:hover {
        border-color: #20c996;
        box-shadow: 0 4px 12px rgba(32, 201, 150, 0.1);
    }
    
    /* ===== CABECERA DE GRUPO ===== */
    .modal-adicionales .grupo-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
    
    .modal-adicionales .grupo-titulo {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #2c3e50;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .modal-adicionales .badge-obligatorio {
        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
        color: white;
        font-size: 0.6rem;
        padding: 0.2rem 0.5rem;
        border-radius: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .modal-adicionales .grupo-max {
        font-size: 0.7rem;
        color: #6c757d;
        background: white;
        padding: 0.2rem 0.5rem;
        border-radius: 8px;
        font-weight: 600;
    }
    
    /* ===== OPCIONES DE ADICIONALES ===== */
    .modal-adicionales .grupo-opciones {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        flex: 1;
    }
    
    .modal-adicionales .opcion-item {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.65rem;
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin: 0;
    }
    
    .modal-adicionales .opcion-item:hover:not(.sin-stock-item) {
        background: #f0fdf8;
        border-color: #20c996;
        transform: translateX(4px);
    }
    
    .modal-adicionales .sin-stock-item {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f8f9fa;
    }
    
    .modal-adicionales .opcion-input {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #20c996;
        flex-shrink: 0;
    }
    
    .modal-adicionales .opcion-content {
        flex: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.5rem;
    }
    
    .modal-adicionales .opcion-nombre {
        font-size: 0.85rem;
        color: #2c3e50;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex-wrap: wrap;
    }
    
    .modal-adicionales .opcion-precio {
        font-size: 0.85rem;
        font-weight: 700;
        color: #20c996;
        white-space: nowrap;
    }
    
    /* ===== BADGES DE STOCK ===== */
    .modal-adicionales .stock-badge {
        font-size: 0.65rem;
        padding: 0.2rem 0.5rem;
        border-radius: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .modal-adicionales .con-stock {
        background: #d1f4e0;
        color: #0d7a43;
    }
    
    .modal-adicionales .poco-stock {
        background: #fff3cd;
        color: #856404;
    }
    
    .modal-adicionales .sin-stock {
        background: #f8d7da;
        color: #721c24;
    }
    
    /* ===== CONTADOR DE GRUPO ===== */
    .modal-adicionales .grupo-contador {
        margin-top: 0.5rem;
        padding-top: 0.5rem;
        border-top: 1px solid #e9ecef;
        font-size: 0.75rem;
        color: #6c757d;
        text-align: center;
        font-weight: 600;
    }
    
    /* ===== GRUPOS CON ERROR ===== */
    .modal-adicionales .grupo-error {
        border-color: #dc3545 !important;
        background: #fff5f5 !important;
        box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1) !important;
        animation: shake 0.4s ease;
    }
    
    .modal-adicionales .grupo-error .grupo-titulo {
        color: #dc3545;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-8px); }
        75% { transform: translateX(8px); }
    }
    
    /* ===== SECCIÓN FOOTER (RESUMEN Y OBSERVACIONES) ===== */
    .modal-adicionales .footer-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-top: 1.25rem;
    }
    
    /* ===== TARJETA DE RESUMEN ===== */
    .modal-adicionales .resumen-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 2px solid #20c996;
        border-radius: 10px;
        padding: 0.875rem;
    }
    
    .modal-adicionales .resumen-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .modal-adicionales .resumen-header svg {
        color: #20c996;
    }
    
    .modal-adicionales .resumen-linea {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        color: #495057;
    }
    
    .modal-adicionales .resumen-divider {
        height: 2px;
        background: linear-gradient(to right, transparent, #20c996, transparent);
        margin: 0.75rem 0;
    }
    
    .modal-adicionales .resumen-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 1rem;
        font-weight: 700;
        color: #20c996;
    }
    
    /* ===== TARJETA DE OBSERVACIONES ===== */
    .modal-adicionales .observaciones-card {
        background: #f8f9fa;
        border: 2px solid #dee2e6;
        border-radius: 10px;
        padding: 0.875rem;
    }
    
    .modal-adicionales .observaciones-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .modal-adicionales .observaciones-label svg {
        color: #6c757d;
    }
    
    .modal-adicionales .observaciones-textarea {
        width: 100%;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        padding: 0.5rem;
        font-size: 0.85rem;
        resize: none;
        transition: all 0.2s ease;
        font-family: inherit;
    }
    
    .modal-adicionales .observaciones-textarea:focus {
        outline: none;
        border-color: #20c996;
        box-shadow: 0 0 0 4px rgba(32, 201, 150, 0.1);
    }
    
    .modal-adicionales .observaciones-textarea::placeholder {
        color: #adb5bd;
    }
    
    /* ===== BOTONES DEL MODAL ===== */
    .swal-btn-confirmar {
        font-weight: 700 !important;
        padding: 0.75rem 2rem !important;
        border-radius: 8px !important;
        font-size: 0.9rem !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(32, 201, 150, 0.3) !important;
        transition: all 0.2s ease !important;
    }
    
    .swal-btn-confirmar:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 16px rgba(32, 201, 150, 0.4) !important;
    }
    
    .swal-btn-cancelar {
        font-weight: 600 !important;
        padding: 0.75rem 2rem !important;
        border-radius: 8px !important;
        font-size: 0.9rem !important;
        transition: all 0.2s ease !important;
    }
    
    .swal-btn-cancelar:hover {
        transform: translateY(-2px) !important;
    }
    
    /* ===== RESPONSIVE DESIGN ===== */
    
    /* Desktop grande */
    @media (min-width: 1200px) {
        .modal-adicionales .adicionales-container {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    /* Desktop */
    @media (min-width: 992px) and (max-width: 1199px) {
        .modal-adicionales .adicionales-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    /* Tablets */
    @media (max-width: 991px) {
        .swal-compacto {
            width: 95% !important;
            max-width: 95% !important;
        }
        
        .modal-adicionales .adicionales-container {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
    }
    
    @media (max-width: 768px) {
        .modal-adicionales .adicionales-container {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
        
        .modal-adicionales .footer-section {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
    }
    
    /* Móviles */
    @media (max-width: 576px) {
        .swal-compacto .swal2-title {
            font-size: 1.1rem !important;
        }
        
        .modal-adicionales .producto-info-wrapper {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .modal-adicionales .producto-detalle {
            width: 100%;
        }
        
        .modal-adicionales .cantidad-control {
            width: 100%;
            flex-direction: row;
            justify-content: space-between;
        }
        
        .modal-adicionales .producto-header {
            padding: 0.875rem;
        }
        
        .modal-adicionales .producto-imagen {
            width: 48px;
            height: 48px;
        }
        
        .modal-adicionales .producto-nombre {
            font-size: 0.9rem;
        }
        
        .modal-adicionales .producto-precio {
            font-size: 1rem;
        }
        
        .modal-adicionales .adicionales-container {
            grid-template-columns: 1fr;
            gap: 0.75rem;
            margin: 0.75rem 0;
        }
        
        .modal-adicionales .grupo-adicionales {
            padding: 0.75rem;
        }
        
        .modal-adicionales .footer-section {
            grid-template-columns: 1fr;
            gap: 0.75rem;
            margin-top: 1rem;
        }
        
        .modal-adicionales .btn-cantidad {
            width: 32px;
            height: 32px;
        }
        
        .modal-adicionales .opcion-item {
            padding: 0.6rem;
        }
        
        .modal-adicionales .opcion-nombre {
            font-size: 0.8rem;
        }
        
        .modal-adicionales .opcion-precio {
            font-size: 0.8rem;
        }
    }
    
    /* Móviles pequeños */
    @media (max-width: 400px) {
        .modal-adicionales .producto-imagen {
            width: 40px;
            height: 40px;
        }
        
        .modal-adicionales .btn-cantidad {
            width: 28px;
            height: 28px;
        }
        
        .modal-adicionales .cantidad-input {
            width: 40px;
            font-size: 1rem;
        }
    }
    
    /* ===== ANIMACIONES Y EFECTOS ===== */
    .modal-adicionales .opcion-item,
    .modal-adicionales .btn-cantidad,
    .modal-adicionales .grupo-adicionales,
    .modal-adicionales .observaciones-textarea {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Suavizado de scroll */
    .swal-compacto .swal2-html-container {
        scroll-behavior: smooth;
    }
    
    /* Scrollbar personalizada */
    .swal-compacto .swal2-html-container::-webkit-scrollbar {
        width: 8px;
    }
    
    .swal-compacto .swal2-html-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .swal-compacto .swal2-html-container::-webkit-scrollbar-thumb {
        background: #20c996;
        border-radius: 10px;
    }
    
    .swal-compacto .swal2-html-container::-webkit-scrollbar-thumb:hover {
        background: #17a679;
    }
`;
document.head.appendChild(style);
