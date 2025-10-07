import { checkProductStock } from "../productos/producto-service";

// ESTRUCTURA ESPERADA DEL CARRITO
// items: [
//   {
//     // Producto con adicionales, ordenes de un mismo producto varian entre si
//     id: 280, // ID del Producto
//     detalles: [ // Separacion de productos por orden
//       {
//         key: "1-2-3",
//         adicionales: [1, 2, 3], // IDs of extras
//         cantidad: 2 // Cantidad correspondiente a la orden
//       },
//       {
//         key: "1-2",
//         adicionales: [1, 2],
//         cantidad: 3
//       }
//     ]
//   },
//   {
//     // Producto sin adicionales
//     id: 263,
//     detalles: [
//         {
//             key: "base", 
//             adicionales : [],
//             cantidad: 4
//         }
//     ]
//   }
// ]





// OBTENER CARRITO
export const obtenerCarrito = () => {
  const data = localStorage.getItem('carrito');
  return data ? JSON.parse(data) : {items:[]};
}

// OBTENER ITEM CARRITO
export const obtenerItemCarrito = (productId) => {
    const carrito = obtenerCarrito();
    // try {
        const itemCarrito = carrito.items.find(item => item.id ==productId);
        return itemCarrito;
    // } catch (error) {
    //     console.error("Error al intentar obtener el item en carrito con ID: ", productId);
    //     throw new Error(error);
    // }
}

// CONTAR LAS EXISTENCIAS EN EL CARRITO
export const cantidadOrdenesProducto = (producto_id) => {
    const itemCarrito = obtenerItemCarrito(producto_id);

    if (itemCarrito && itemCarrito.adicionales) {
        const cantidad = Object.keys(itemCarrito.adicionales).length;
        return cantidad;
    }

    return 0;
}

// OBTENER ADICIONALES PERTENECIENTES A UN ITEM CARRITO EN UN INDICE ESPECIFICO
export const adicionalesOrdenIndice = (id_producto, indice) => {
    try {
        const itemCarrito = obtenerItemCarrito(id_producto);
        const adicionales = itemCarrito.adicionales[indice.toString()];
        return adicionales || [];
    } catch (error) {
        console.error(`Error al obtener adicionales del producto ${id_producto}, índice ${indice}:`, error);
        return [];
    }
}

// VACIAR CARRITO
export const vaciarCarrito = () => {
    localStorage.removeItem('carrito');
    actualizarContadorCarrito();
}


// export function encontrarEnCarrito(carrito, productoID, adicionales = []) {
//     const checkKey = (adicionales && adicionales.length > 0) 
//     ? adicionales.slice().sort((a, b) => a - b).join("-") 
//     : "base";

//     // Buscar el producto por ID (Es importante usar un carrito proporcionado como parametro)
//     // De tal manera que se devuelvan producto y detalle pertenecientes a la misma instancia.
//     const producto = carrito.items.find(item => item.id === productoID);
//     if (!producto) {
//         return { producto: null, detalle: null }; // No existe el producto
//     }

//     // De existir el producto, buscar entre sus detalles
//     const detalle = producto.detalles.find(d => d.key === checkKey) || null;

//     // Retornar los objetos
//     return { producto, detalle };
// }

// REMOVER PRODUCTO
export const eliminarProducto = (productId) => {
    const carrito = obtenerCarrito();
    // // console.log("Eliminando el producto con id", productId);
    carrito.items = carrito.items.filter(item => item.id !== productId);
    localStorage.setItem('carrito', JSON.stringify(carrito));
    // // console.log("El producto deberia haber sido eliminado");
}

export const eliminarOrdenProducto = (productId, indice) => {
    try {
        const carrito = obtenerCarrito();
        
        // Encontrar el item en el carrito
        const itemIndex = carrito.items.findIndex(item => item.id == productId);
        
        if (itemIndex === -1) {
            throw new Error(`Producto con ID ${productId} no encontrado en el carrito`);
        }
        
        const item = carrito.items[itemIndex];
        
        // Verificar que el índice existe
        if (!item.adicionales[indice.toString()]) {
            throw new Error(`Índice ${indice} no encontrado en producto ${productId}`);
        }
        
        // Crear nuevo objeto de adicionales reindexados
        const nuevosAdicionales = {};
        let nuevoIndice = 1;
        
        // Obtener el número de entradas en adicionales
        const totalEntradas = Object.keys(item.adicionales).length;
        
        // Recorrer los índices en orden y reindexar, saltando el que se elimina
        for (let i = 1; i <= totalEntradas; i++) {
            if (i !== indice) {
                nuevosAdicionales[nuevoIndice.toString()] = item.adicionales[i.toString()];
                nuevoIndice++;
            }
        }
        
        // Contar cuántas entradas quedan en adicionales
        const nuevaCantidad = Object.keys(nuevosAdicionales).length;
        
        // Si no quedan órdenes, eliminar el producto completo
        if (nuevaCantidad === 0) {
            const err = new Error("No se puede eliminar el unico pedido existente.");
            err.name = "LastOrderCartDeletionError"; 
            throw err;
            // La línea siguiente (carrito.items = ...) nunca se ejecutará, de momento
            carrito.items = carrito.items.filter(item => item.id !== productId);
        } else {
            // Actualizar el item con la nueva cantidad basada en las entradas reales
            carrito.items[itemIndex].adicionales = nuevosAdicionales;
            carrito.items[itemIndex].cantidad = nuevaCantidad;
        }
        
        // Guardar el carrito actualizado
        localStorage.setItem('carrito', JSON.stringify(carrito));
        
        return carrito;
    } catch (error) {
        console.error(`Error al eliminar orden del producto ${productId}, índice ${indice}:`, error);
        throw error;
    }
}

// MOSTRAR TOAST PRODUCTO AGREGADO AL CARRITO
export const mostrarToastAgregado = () => {
    var toaster = document.getElementById('toast-cart-added');
    const toast = new bootstrap.Toast(toaster);
    toast.show()
    setTimeout(() => {
        toast.hide();
    }, 3000); // Ocultar tras 3 segundos
}

// MOSTRAR TOAST LIMITE STOCK ALCANZADO (EN CARRITO)
export const mostrarToastLimite = () => {
    var toaster = document.getElementById('toast-cart-item-limit');
    const toast = new bootstrap.Toast(toaster);
    toast.show()
    setTimeout(() => {
        toast.hide();
    }, 3000); // Ocultar tras 3 segundos
}

// ACTUALIZAR CONTADOR EN DETALLE-PRODUCTO
export const actualizarContadorDetalleProducto = (ProductId) => {
    if (!ProductId) {
        return;
    }
    const elementoNumero = document.getElementById('details-cart-counter');
    const elementoTextoOrden = document.getElementById('order-info-text');

    // Buscar el producto a contarse en el carrito
    const productoContable = obtenerItemCarrito(ProductId);

    
    if (productoContable && productoContable.cantidad > 0) {
        // Si existe el producto correspondiente, usar la cantidad directamente
        const cantidadProducto = productoContable.cantidad;
        
        // Actualizar el contador y revelar el texto
        elementoNumero.textContent = cantidadProducto;
        elementoTextoOrden.style.display = 'inline-block';
    } else {
        // De no encontrarse, cambiar el contador a 0 y ocultar el texto
        elementoNumero.textContent = '0';
        elementoTextoOrden.style.display = 'none';
    }
}

// ACTUALIZAR CONTADOR GENERAL (FOOTER)
export const actualizarContadorCarrito = () => {
    const carrito = obtenerCarrito();
    const elementoContador = document.getElementById('cart-counter');

    if (carrito.items && carrito.items.length > 0) {
        // Sumar los valores de cantidad de cada producto
        const cantidadTotal = carrito.items.reduce((sum, item) => {
            return sum + (item.cantidad || 0);
        }, 0);

        // // console.log("Cantidad Carrito: ", cantidadTotal);

        elementoContador.textContent = cantidadTotal;
        elementoContador.style.display = 'inline-block';
    } else {
        elementoContador.textContent = '';
        elementoContador.style.display = 'none';
    }
}

// AGREGAR ITEMS AL CARRITO
export async function agregarAlCarrito(productId, cantidad, isUpdate = false, adicionales = null) {
    const carrito = obtenerCarrito();
    // // console.log("ID Producto a agregar al carrito: ", productId);
    // // console.log("cantidad a agregarse: ", cantidad);
    // // console.log("Adicionales Agregar: ", adicionales);

    // // const clave = adicionales ? "complejo" : "simple";

    // Normalizar los adicionales a un arrau vacío en caso de que se obtenga null o undefined
    const adicionalesNormalized = adicionales || [];
    
    try {
        // Encontrar el producto si es que ya existe en el carrito
        const productoIndex = carrito.items.findIndex(item => item.id === productId);
        let producto = productoIndex !== -1 ? carrito.items[productoIndex] : null;
        
        // Calcular la cantidad total del producto
        const cantidadActual = producto ? producto.cantidad : 0;
        const cantidadNueva = cantidadActual + cantidad;
        
        // // console.log("Cantidad Actual: ", cantidadActual);
        // // console.log("Cantidad Nueva: ", cantidadNueva);

        // Obtener el stock del producto
        const stockResponse = await checkProductStock(productId);
        
        // Validar el stock solo si es limitado
        if (!stockResponse.unlimited) {
            const stockDisponible = stockResponse.stock;
            if (cantidadNueva > stockDisponible) {
                console.error(`El stock del producto es ${stockDisponible}, la cantidad que se solicita es de ${cantidadNueva}`);
                mostrarToastLimite();
                return {
                    success: false, 
                    message: `Solo hay ${stockDisponible}u disponibles`,
                    cart: carrito,
                    totalSolicitado: cantidadNueva,
                    stockDisponible: stockDisponible
                };
            }
        }
        
        // Agregar o actualizar el item en el carrito
        if (producto) {
            // Si el producto existe, encontrar el próximo indice disponible.
            const indicesExistentes = Object.keys(producto.adicionales).map(Number);
            const proximoIndice = indicesExistentes.length > 0 ? Math.max(...indicesExistentes) + 1 : 1;
            
            // Agregar nuevos indices según la cantidad solicitada
            for (let i = 0; i < cantidad; i++) {
                producto.adicionales[proximoIndice + i] = adicionalesNormalized;
            }
            
            // Actualizar la cantidad total
            producto.cantidad = cantidadNueva;
            
            // // console.log("Actualizando producto existente");
            // // console.log("Producto después del update:", producto);
        } else {
            // Crear un nuevo registro en carrito del producto
            const nuevoProducto = {
                id: productId,
                cantidad: cantidad,
                adicionales: {},
                // // key: clave
            };
            
            // Agregar tantos indices como la cantidad solicitada
            for (let i = 1; i <= cantidad; i++) {
                nuevoProducto.adicionales[i] = adicionalesNormalized;
            }
            
            carrito.items.push(nuevoProducto);
            // // console.log("Creando nuevo producto en carrito");
        }
        
        // Guardar los cambios y mostrar la notificacion
        // // console.log("Cart before save:", JSON.stringify(carrito, null, 2));
        localStorage.setItem('carrito', JSON.stringify(carrito));
        // // console.log("Los cambios se deberían haber guardado");
        
        actualizarContadorCarrito();
        if (!isUpdate) {
            mostrarToastAgregado();
        }
        
        return { success: true, newQuantity: cantidadNueva, cart: carrito };
        
    } catch (error) {
        // // console.error('Error agregando el producto al carrito:', error);
        return {
            success: false, 
            message: 'Error al agregar el producto al carrito',
            cart: carrito,
            totalSolicitado: cantidadNueva,
            stockDisponible: stockDisponible
        };
    }
}

// ACTUALIZAR OBSERVACION DEL PEDIDO
export const actualizarObservacion = (producto_id, observacion) => {
    // // console.log(`Observacion a establecerse para el producto con id ${producto_id}: `, observacion);
    const carrito = obtenerCarrito();
    const itemCarrito = carrito.items.find(item => item.id == producto_id);
    itemCarrito.observacion = observacion;
    localStorage.setItem('carrito', JSON.stringify(carrito));
}

export const actualizarOrdenCarrito = (producto_id, indice, adicionales = []) => {
    try {
        const carrito = obtenerCarrito();
        
        // Encontrar el item en el carrito
        const itemIndex = carrito.items.findIndex(item => item.id == producto_id);
        
        if (itemIndex === -1) {
            throw new Error(`Producto con ID ${producto_id} no encontrado en el carrito`);
        }
        
        // Actualizar los adicionales en el índice específico
        carrito.items[itemIndex].adicionales[indice.toString()] = adicionales;
        
        // Guardar el carrito actualizado en localStorage
        localStorage.setItem('carrito', JSON.stringify(carrito));
        
        return carrito.items[itemIndex];
    } catch (error) {
        console.error(`Error al actualizar orden del producto ${producto_id}, índice ${indice}:`, error);
        throw error;
    }
}


const reconstruirAdicionales = (item, newQuantity) => {
    item.adicionales = {};
    for (let i = 1; i <= newQuantity; i++) {
        item.adicionales[i.toString()] = [];
    }
};

export const updateProductToMax = async (productId) => {
    const cart = obtenerCarrito();
    const itemToMax = cart.items.find(item => item.id == productId); 
    
    if (!itemToMax) {
        return {success: false, message: "Producto no encontrado en el carrito"};
    }
    
    try {
        const itemStockResponse = await checkProductStock(productId);
        const stockDisponible = Number(itemStockResponse.stock);
        
        // Actualizar cantidad
        itemToMax.cantidad = stockDisponible;
        
        // Reconstruir adicionales segun la nueva cantidad
        reconstruirAdicionales(itemToMax, stockDisponible);
        
        localStorage.setItem('carrito', JSON.stringify(cart));
        actualizarContadorCarrito(); // Actualizar el contador general del carrito.
        
        return {
            success: true, 
            cantidad: itemToMax.cantidad,
            adicionales: itemToMax.adicionales
        };
        
    } catch (error) {
        console.error("Ocurrio un error al actualizar el stock solicitado al maximo: ", productId, error);
        return {success: false, message: "Error al obtener el stock disponible"};
    }
}

export const restarDelCarrito = (productId, cantidad) => {
    try {
        if (cantidad <= 0) {
            return {success: false, message: "El valor no puede ser menor que 1"};
        }
        
        const carrito = obtenerCarrito();
        
        // Encontrar el item por su ID
        const itemExistente = carrito.items.find(item => item.id == productId);
        
        if (!itemExistente) {
            return {success: false, message: "Producto no encontrado en el carrito"};
        }
        
        const cantidadActual = itemExistente.cantidad;
        
        if (cantidad >= cantidadActual) {
            return {success: false, message: "No se puede reducir más, la cantidad resultante sería menor que 1"};
        }
        
        const cantidadNueva = cantidadActual - cantidad;
        
        // Actualizar la cantidad
        itemExistente.cantidad = cantidadNueva;
        
        // Reconstruir adicionales segun la nueva cantidad
        reconstruirAdicionales(itemExistente, cantidadNueva);
        
        // Save updated cart
        localStorage.setItem('carrito', JSON.stringify(carrito));
        actualizarContadorCarrito();
        
        return {
            success: true, 
            message: "Substraccion exitosa", 
            newQuantity: cantidadNueva
        };
        
    } catch (error) {
        console.log(error);
        return {success: false, message: "Error al procesar la substracción"};
    }
}


window.carritoStorage = {
    obtenerCarrito,
    obtenerItemCarrito,
    agregarAlCarrito,
    actualizarOrdenCarrito,
    actualizarObservacion,
    restarDelCarrito,
    eliminarProducto,
    eliminarOrdenProducto,
    updateProductToMax,
    vaciarCarrito,
    actualizarContadorCarrito,
    actualizarContadorDetalleProducto,
    mostrarToastAgregado,
    mostrarToastLimite,
    adicionalesOrdenIndice,
    cantidadOrdenesProducto
}