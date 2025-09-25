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

items: [
  {
    // Producto con adicionales, ordenes de un mismo producto varian entre si
    id: 280, // ID del Producto
    detalles: [ // Separacion de productos por orden
      {
        key: "1-2-3",
        adicionales: [1, 2, 3], // IDs of extras
        cantidad: 2 // Cantidad correspondiente a la orden
      },
      {
        key: "1-2",
        adicionales: [1, 2],
        cantidad: 3
      }
    ]
  },
  {
    // Producto sin adicionales
    id: 263,
    detalles: [
        {
            key: "base", 
            adicionales : [],
            cantidad: 4
        }
    ]
  }
]





// OBTENER CARRITO
export const obtenerCarrito = () => {
  const data = localStorage.getItem('carrito');
  return data ? JSON.parse(data) : {items:[]};
}

// OBTENER ITEM CARRITO
export const obtenerItemCarrito = (productId) => {
    const carrito = obtenerCarrito();
    try {
        const itemCarrito = carrito.items.find(item => item.id ==productId);
        return itemCarrito;
    } catch (error) {
        console.error("Error al intentar obtener el item en carrito con ID: ", productId);
        throw new Error(error);
    }
}

// VACIAR CARRITO
export const vaciarCarrito = () => {
    localStorage.removeItem('carrito');
    actualizarContadorCarrito();
}

export function encontrarEnCarrito(carrito, productoID, adicionales = []) {
    const checkKey = (adicionales && adicionales.length > 0) 
    ? adicionales.slice().sort((a, b) => a - b).join("-") 
    : "base";

    // Buscar el producto por ID (Es importante usar un carrito proporcionado como parametro)
    // De tal manera que se devuelvan producto y detalle pertenecientes a la misma instancia.
    const producto = carrito.items.find(item => item.id === productoID);
    if (!producto) {
        return { producto: null, detalle: null }; // No existe el producto
    }

    // De existir el producto, buscar entre sus detalles
    const detalle = producto.detalles.find(d => d.key === checkKey) || null;

    // Retornar los objetos
    return { producto, detalle };
}

// REMOVER PRODUCTO
export const eliminarProducto = (productId) => {
    const carrito = obtenerCarrito();
    carrito.items = carrito.items.filter(item => item.id.toString() !== productId);
    localStorage.setItem('carrito', JSON.stringify(carrito));
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
    const elementoNumero = document.getElementById('details-cart-counter');
    const elementoTextoOrden = document.getElementById('order-info-text');

    // Buscar el producto a contarse en el carrito
    const productoContable = obtenerItemCarrito(ProductId);

    if (productoContable && productoContable.detalles) {
        // Si existe el producto correspondiente, sumar las cantidades
        const totalQuantity = productoContable.detalles.reduce((sum, detail) => {
            return sum + (detail.cantidad || 0);
        }, 0);
        // Actualizar el contador y revelar el texto
        elementoNumero.textContent = totalQuantity;
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
        // Iterar los registros del carrito usando un reduce
        const cantidadTotal = carrito.items.reduce((sum, item) => {
            // Revisar si en cada item existe un array "detalles"
            if (item.detalles && Array.isArray(item.detalles)) {
                // De existir, usar otro reduce para sumar las 'cantidad' de 'detalles'
                const cantidadDetalles = item.detalles.reduce((detSum, detail) => {
                    return detSum + (detail.cantidad || 0);
                }, 0);
                // Adicionar la suma de detalles al total general
                return sum + cantidadDetalles;
            }
            // De no existir "detalles", retornar el valor actual sin modificar
            return sum;
        }, 0);

        console.log("Cantidad Carrito: ",cantidadTotal)

        elementoContador.textContent = cantidadTotal;
        elementoContador.style.display = 'inline-block';
    } else {
        elementoContador.textContent = '';
        elementoContador.style.display = 'none';
    }
}

// // AGREGAR ITEMS AL CARRITO
// export async function agregarAlCarrito(productId, cantidad, isUpdate = false, adicionales = null) {
//     const carrito = obtenerCarrito();
//     console.log("Adicionales: ", adicionales);

//     const checkKey = (adicionales && adicionales.length > 0) 
//     ? adicionales.slice().sort((a, b) => a - b).join("-") 
//     : "base";

//     const resultado = encontrarEnCarrito(carrito, productId, adicionales);
//     const { producto, detalle } = resultado;
//     const cantidadActual = detalle ? detalle.cantidad : 0;
//     console.log("Cantidad Actual: ", cantidadActual)
//     const cantidadNueva = cantidadActual + cantidad;
//     console.log("Cantidad Nueva: ", cantidadNueva);

//     try {
//         const stockResponse = await checkProductStock(productId);
        
//         // Validar stock solo si es limitado
//         if (!stockResponse.unlimited) {
//             const stockDisponible = stockResponse.stock;
//             if (cantidadNueva > stockDisponible) {
//                 console.error(`El stock del producto es ${stockDisponible}, la cantidad que se solicita es de ${cantidadNueva}`);
//                 mostrarToastLimite();
//                 return {
//                     success: false, 
//                     message: `Solo hay ${stockDisponible}u disponibles`,
//                     cart: carrito
//                 };
//             }
//         }
        
//         // Agregar o actualizar el item en el carrito
//         if (producto && detalle) {
//             // Existe registro, actualizar cantidad
//             detalle.cantidad = cantidadNueva;
//             console.log("Actualizando cantidad");
//             console.log("Detalle después del update:", detalle);
//             console.log("Cart después del update:", carrito);
//         } else if (producto && !detalle) {
//             // Existe producto, agregar nuevo detalle
//             producto.detalles.push({
//                 key: checkKey,
//                 adicionales,
//                 cantidad
//             });
//         } else {
//             // Crear nuevo producto con detalle
//             carrito.items.push({
//                 id: productId,
//                 detalles: [{
//                     key: checkKey,
//                     adicionales,
//                     cantidad
//                 }]
//             });
//         }
        
//         // Guardar cambios y mostrar notificación
//         console.log("Cart before save:", JSON.stringify(carrito))
//         localStorage.setItem('carrito', JSON.stringify(carrito));
//         console.log("Los cambios se deberian haber guardado")
//         actualizarContadorCarrito();
//         if (!isUpdate) {
//             mostrarToastAgregado();
//         }
        
//         return {success: true, newQuantity: cantidadNueva, cart: carrito};
        
//     } catch (error) {
//         console.error('Error agregando el producto al carrito:', error);
//         return {
//             success: false, 
//             message: 'Error al agregar el producto al carrito',
//             cart: carrito
//         };
//     }
// }

// AGREGAR ITEMS AL CARRITO
export async function agregarAlCarrito(productId, cantidad, isUpdate = false, adicionales = null) {
    const carrito = obtenerCarrito();
    console.log("Adicionales: ", adicionales);

    // Normalize adicionales to empty array if null/undefined
    const adicionalesNormalized = adicionales || [];
    
    try {
        // Find existing product in cart
        const productoIndex = carrito.items.findIndex(item => item.id === productId);
        let producto = productoIndex !== -1 ? carrito.items[productoIndex] : null;
        
        // Calculate current total quantity for this product
        const cantidadActual = producto ? producto.cantidad : 0;
        const cantidadNueva = cantidadActual + cantidad;
        
        console.log("Cantidad Actual: ", cantidadActual);
        console.log("Cantidad Nueva: ", cantidadNueva);

        // Check stock if product exists
        const stockResponse = await checkProductStock(productId);
        
        // Validate stock only if limited
        if (!stockResponse.unlimited) {
            const stockDisponible = stockResponse.stock;
            if (cantidadNueva > stockDisponible) {
                console.error(`El stock del producto es ${stockDisponible}, la cantidad que se solicita es de ${cantidadNueva}`);
                mostrarToastLimite();
                return {
                    success: false, 
                    message: `Solo hay ${stockDisponible}u disponibles`,
                    cart: carrito
                };
            }
        }
        
        // Add or update item in cart
        if (producto) {
            // Product exists, find next available index
            const existingIndexes = Object.keys(producto.adicionales).map(Number);
            const nextIndex = existingIndexes.length > 0 ? Math.max(...existingIndexes) + 1 : 1;
            
            // Add new indexes for the requested quantity
            for (let i = 0; i < cantidad; i++) {
                producto.adicionales[nextIndex + i] = adicionalesNormalized;
            }
            
            // Update total quantity
            producto.cantidad = cantidadNueva;
            
            console.log("Actualizando producto existente");
            console.log("Producto después del update:", producto);
        } else {
            // Create new product entry
            const newProducto = {
                id: productId,
                cantidad: cantidad,
                adicionales: {}
            };
            
            // Add indexes for the requested quantity
            for (let i = 1; i <= cantidad; i++) {
                newProducto.adicionales[i] = adicionalesNormalized;
            }
            
            carrito.items.push(newProducto);
            console.log("Creando nuevo producto en carrito");
        }
        
        // Save changes and show notification
        console.log("Cart before save:", JSON.stringify(carrito, null, 2));
        localStorage.setItem('carrito', JSON.stringify(carrito));
        console.log("Los cambios se deberían haber guardado");
        
        actualizarContadorCarrito();
        if (!isUpdate) {
            mostrarToastAgregado();
        }
        
        return { success: true, newQuantity: cantidadNueva, cart: carrito };
        
    } catch (error) {
        console.error('Error agregando el producto al carrito:', error);
        return {
            success: false, 
            message: 'Error al agregar el producto al carrito',
            cart: carrito
        };
    }
}

export const substractFromCart = (productId, cantidad) => {
    try {
        if (cantidad <= 0) {
        return {success:false,message:"El valor no puede ser menor que 1"}
    }
    const carrito = obtenerCarrito();
    console.log("Carrito actual:", carrito);
    console.log("Id item a buscar", productId);
    // const existingItem = carrito.items.find(item => item.id == productId); // Use the same cart reference
    const resultado = encontrarEnCarrito(carrito, productId, adicionales);
    const { producto, detalle } = resultado;

    // console.log("Item encontrado:", existingItem);
    // console.log("Item existente: ", existingItem);
    console.log("Item existente: ", producto);
    const cantidadActual = detalle.cantidad;
    // const currentQty = existingItem.cantidad;
    if (cantidad >= cantidadActual) {
        return {success:false,message:"El valor no puede ser menor que 1"};
    }
    // if (cantidad >= currentQty) {
    //     return {success:false,message:"El valor no puede ser menor que 1"}
    // }
    const newQty = currentQty - cantidad;
    const cantidadNueva = cantidadActual - cantidad;
    detalle.cantidad = cantidadNueva;
    // existingItem.cantidad = newQty;
    localStorage.setItem('carrito', JSON.stringify(carrito));
    actualizarContadorCarrito();
    // console.log("Substraccion Exitosa, nueva cantidad: ", existingItem.cantidad)
    console.log("Substraccion Exitosa, nueva cantidad: ", detalle.cantidad)
    return {success: true,message:"Substraccion exitosa",newQuantity: newQty}
    } catch (error) {
        console.log(error)
    }

}

export const updateProductToMax = async (productId) => {
    const cart = obtenerCarrito();
    const itemToMax = cart.items.find(item => item.id == productId); 
    try {
        const itemStockResponse = await checkProductStock(productId);
        const availableStock = Number(itemStockResponse.stock);
        itemToMax.cantidad = availableStock;
        localStorage.setItem('carrito', JSON.stringify(cart));
        return {success: true, cantidad: itemToMax.cantidad}
    } catch (error) {
        console.error("Ocurrio un error al actualizar el stock solicitado al maximo: ", productId);
        return {success: false}
    }
}

window.carritoStorage = {
    obtenerCarrito,
    obtenerItemCarrito,
    agregarAlCarrito,
    substractFromCart,
    eliminarProducto,
    updateProductToMax,
    vaciarCarrito,
    actualizarContadorCarrito,
    actualizarContadorDetalleProducto
}