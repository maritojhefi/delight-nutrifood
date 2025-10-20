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
        },
        title: "Personaliza tu orden",
        html: html,
        width: "70%",
        maxWidth: "500px",
        showCancelButton: true,
        confirmButtonText: "AGREGAR PEDIDO",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#20c996",
        cancelButtonColor: "#dc3545",
        showLoaderOnConfirm: true,
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
            <div class="producto-info mb-2">
                <div class="d-flex align-items-center">
                    <img src="${producto.imagen}" alt="${
        producto.nombre
    }" class="producto-imagen me-2" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;">
                    <div>
                        <h6 class="mb-0 producto-nombre">${producto.nombre}</h6>
                        <small class="text-muted">Bs. ${producto.precio.toFixed(
                            2
                        )}</small>
                    </div>
                </div>
            </div>
            
            <div class="cantidad-section mb-2">
                <label class="form-label small">Cantidad:</label>
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-outline-secondary btn-sm btn-cantidad" id="btn-disminuir">-</button>
                    <input type="number" class="form-control form-control-sm text-center mx-1" id="cantidad" value="1" min="1" style="width: 60px;">
                    <button type="button" class="btn btn-outline-secondary btn-sm btn-cantidad" id="btn-aumentar">+</button>
                </div>
            </div>
            
            <div class="row g-2">
    `;

    // Agregar grupos de adicionales en columnas de 6
    grupos.forEach((grupo) => {
        const esObligatorio = grupo.es_obligatorio;
        const maximoSeleccionable = grupo.maximo_seleccionable;
        const tipoInput = maximoSeleccionable === 1 ? "radio" : "checkbox";

        html += `
            <div class="col-6">
                <div class="grupo-adicionales">
                    <h6 class="grupo-titulo">
                        ${grupo.nombre}
                        ${
                            esObligatorio
                                ? '<span class="text-danger">*</span>'
                                : ""
                        }
                        ${
                            maximoSeleccionable > 1
                                ? `<small class="text-muted">(máx. ${maximoSeleccionable})</small>`
                                : ""
                        }
                    </h6>
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

            html += `
                <div class="form-check form-check-sm ${
                    sinStock ? "opacity-50" : ""
                }">
                    <input class="form-check-input" type="${tipoInput}" 
                           name="grupo_${grupo.id}" 
                           id="adicional_${adicional.id}" 
                           value="${adicional.id}"
                           ${sinStock ? "disabled" : ""}>
                    <label class="form-check-label d-flex justify-content-between align-items-center" for="adicional_${
                        adicional.id
                    }">
                        <span class="adicional-nombre">${
                            adicional.nombre
                        }</span>
                        <span class="text-success fw-bold precio-adicional">${precioTexto}</span>
                    </label>
                </div>
            `;
        });

        html += `
                    </div>
                    <div class="grupo-contador text-muted" id="contador_${grupo.id}"></div>
                </div>
            </div>
        `;
    });

    html += `
            </div>
            
            <div class="resumen-precio mt-2 p-2 bg-light rounded">
                <div class="d-flex justify-content-between small">
                    <span>Costo Unitario:</span>
                    <span id="precio-producto">Bs. ${producto.precio.toFixed(
                        2
                    )}</span>
                </div>
                <div class="d-flex justify-content-between small">
                    <span>Adicionales:</span>
                    <span id="precio-adicionales">Bs. 0.00</span>
                </div>
                <hr class="my-1">
                <div class="d-flex justify-content-between fw-bold small">
                    <span>Total:</span>
                    <span id="precio-total">Bs. ${producto.precio.toFixed(
                        2
                    )}</span>
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
        cantidad.value = parseInt(cantidad.value) + 1;
        actualizarPrecios();
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
                const maximo = parseInt(grupo.dataset.maximo);

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
        const precioTexto =
            input.parentElement.querySelector(".text-success").textContent;
        if (precioTexto.includes("+Bs.")) {
            const precio = parseFloat(precioTexto.replace("+Bs. ", ""));
            precioAdicionales += precio;
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
    const adicionalesSeleccionados = [];

    // Validar grupos obligatorios
    for (const grupo of grupos) {
        const grupoElement = document.querySelector(
            `[data-grupo-id="${grupo.id}"]`
        );
        const seleccionados = grupoElement.querySelectorAll("input:checked");

        if (grupo.es_obligatorio && seleccionados.length === 0) {
            Swal.showValidationMessage(
                `Debes seleccionar al menos una opción en ${grupo.nombre}`
            );
            return false;
        }

        if (seleccionados.length > grupo.maximo_seleccionable) {
            Swal.showValidationMessage(
                `Solo puedes seleccionar máximo ${grupo.maximo_seleccionable} opciones en ${grupo.nombre}`
            );
            return false;
        }

        // Agregar adicionales seleccionados
        seleccionados.forEach((input) => {
            adicionalesSeleccionados.push(parseInt(input.value));
        });
    }

    // Enviar datos a Livewire
    Livewire.emit(
        "agregarProductoConAdicionales",
        producto.id,
        adicionalesSeleccionados,
        cantidad
    );

    return true;
}

// Estilos CSS para el modal
const style = document.createElement("style");
style.textContent = `
    /* Modal compacto */
    .swal-compacto {
        font-size: 0.875rem !important;
    }
    
    .swal-compacto .swal2-title {
        font-size: 1.1rem !important;
        margin-bottom: 0.5rem !important;
    }
    
    .swal-compacto .swal2-html-container {
        margin: 0.5rem 0 !important;
    }
    
    /* Información del producto */
    .modal-adicionales .producto-imagen {
        border: 1px solid #dee2e6;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .modal-adicionales .producto-nombre {
        font-size: 0.9rem;
        font-weight: 600;
        color: #495057;
        line-height: 1.2;
    }
    
    /* Sección de cantidad */
    .modal-adicionales .btn-cantidad {
        width: 28px;
        height: 28px;
        padding: 0;
        font-size: 0.8rem;
        border-radius: 4px;
    }
    
    /* Grupos de adicionales */
    .modal-adicionales .grupo-titulo {
        color: #495057;
        font-weight: 600;
        font-size: 0.8rem;
        margin-bottom: 0.3rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .modal-adicionales .grupo-opciones {
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 0.5rem;
        background-color: #f8f9fa;
        min-height: 120px;
    }
    
    .modal-adicionales .form-check {
        padding: 0.25rem 0;
        margin-bottom: 0.2rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .modal-adicionales .form-check:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
    
    .modal-adicionales .form-check-input {
        margin-top: 0.1rem;
        transform: scale(0.8);
    }
    
    .modal-adicionales .form-check-label {
        width: 100%;
        cursor: pointer;
        font-size: 0.75rem;
        line-height: 1.2;
        padding-left: 0.2rem;
    }
    
    .modal-adicionales .adicional-nombre {
        font-size: 0.75rem;
        color: #495057;
    }
    
    .modal-adicionales .precio-adicional {
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .modal-adicionales .grupo-contador {
        margin-top: 0.3rem;
        font-size: 0.65rem;
        font-style: italic;
        color: #6c757d;
    }
    
    /* Resumen de precios */
    .modal-adicionales .resumen-precio {
        border: 1px solid #20c996;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        font-size: 0.8rem;
    }
    
    .modal-adicionales .resumen-precio hr {
        margin: 0.3rem 0;
        border-color: #20c996;
    }
    
    /* Responsive para pantallas pequeñas */
    @media (max-width: 576px) {
        .modal-adicionales .col-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .modal-adicionales .grupo-opciones {
            min-height: auto;
        }
    }
    
    /* Hover effects */
    .modal-adicionales .form-check:hover {
        background-color: rgba(32, 201, 150, 0.05);
        border-radius: 4px;
    }
    
    .modal-adicionales .btn-cantidad:hover {
        background-color: #20c996;
        border-color: #20c996;
        color: white;
    }
    
    /* Animaciones suaves */
    .modal-adicionales .form-check {
        transition: all 0.2s ease;
    }
    
    .modal-adicionales .btn-cantidad {
        transition: all 0.2s ease;
    }
`;
document.head.appendChild(style);
