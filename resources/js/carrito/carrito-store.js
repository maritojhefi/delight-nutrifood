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
export const getCart = () => {
  const data = localStorage.getItem('cart');
  return data ? JSON.parse(data) : {items:[]};
}

// OBTENER PRODUCTO
export const getCartItem = (productId) => {
    // console.log("retireving cart-item with id: ",productId)
    const cart = getCart();
    try {
        const cartItem = cart.items.find(item => item.id ==productId);
        // console.log("Retrieved cart item: ", cartItem);
        return cartItem;
    } catch (error) {
        console.error("Error retrieving cart-item with id: ", productId);
        throw new Error(error);
    }
}

// VACIAR CARRITO
export const emptyCart = () => {
    // console.log("emptyCart fue llamado")
    localStorage.removeItem('cart');
}

export function encontrarEnCarrito(carrito, productoID, adicionales = []) {
    // const cart = getCart();

    const checkKey = (adicionales && adicionales.length > 0) 
        ? adicionales.slice().sort().join("-") 
        : "base";

    // Buscar el producto por ID
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
export const removeProduct = (productId) => {
    const cart = getCart();
    cart.items = cart.items.filter(item => item.id.toString() !== productId);
    localStorage.setItem('cart', JSON.stringify(cart));
}

export const showAddedToast = () => {
    // console.log("Showing Added to cart toast");
    var toaster = document.getElementById('toast-cart-added');
    const cart = new bootstrap.Toast(toaster);
    cart.show()
    setTimeout(() => {
        cart.hide();
    }, 3000); // Ocultar tras 1 segundo
}

export const showLimitToast = () => {
    // console.log("Showing cart item limit reached toast");
    var toaster = document.getElementById('toast-cart-item-limit');
    const cart = new bootstrap.Toast(toaster);
    cart.show()
    setTimeout(() => {
        cart.hide();
    }, 3000); // Ocultar tras 1 segundo
}

export const updateCartItemDetailCounter = (ProductId) => {
    const cart = JSON.parse(localStorage.getItem('cart')) || { items: [] };
    const detailCounter = document.getElementById('details-cart-counter');
    const itemData = getCartItem(ProductId);
    if (cart.items && cart.items.length > 0) {
        detailCounter.textContent = itemData ? itemData.cantidad : 0;
        detailCounter.style.display = 'inline-block'; 
    } else {
        detailCounter.textContent = '0';
        detailCounter.style.display = 'none'; 
    }
}

export const updateCartCounterEX = () => {
    const cart = JSON.parse(localStorage.getItem('cart')) || { items: [] };
    const cartCounter = document.getElementById('cart-counter');
    if (cart.items && cart.items.length > 0) {
        // // console.log("CartItemsRender:",cart.items)
        const totalQuantity = cart.items.reduce((sum, item) => sum + item.cantidad, 0);
        // // console.log("TotalQuantity: ", totalQuantity)
        cartCounter.textContent = totalQuantity;
        cartCounter.style.display = 'inline-block'; 
    } else {
        cartCounter.textContent = '';
        cartCounter.style.display = 'none';
    }
}

// AGREGAR ITEMS AL CARRITO
// export async function addToCart(productId, cantidad, isUpdate = false, adicionales = null) {
//     const cart = getCart();
//     const checkKey = (adicionales && adicionales.length > 0) 
//     ? adicionales.slice().sort().join("-") 
//     : "base";

//     const registroExistente = encontrarEnCarrito(productId, adicionales);
//     const { producto, detalle } = encontrarEnCarrito(productId, adicionales);
//     //   const existingItem = cart.items.find(item => item.id == productId && item.adicionales == adicionales );
//     // const existingItem = cart.items.find(item => {
//     //     // Revisar IdProducto
//     //     console.log("Item existente a evaluar: ", item);
//     //     console.log("ProductoID recibido: ",productId, "Datatype: ", typeof(productId), "Adicionales: ", adicionales);
//     //     if (item.id != productId) {
//     //         return false;
//     //     }

//     //     // Revisar Adicionales Inexistentes
//     //     if (!item.adicionales && !adicionales) {
//     //         return true; // Si ambos son null, es la misma orden
//     //     }
//     //     if (!item.adicionales || !adicionales) {
//     //         return false; // Si difieren, son ordenes distintas
//     //     }

//     //     // Revisar numero de adicionales
//     //     if (item.adicionales.length !== adicionales.length) {
//     //         return false; // Si difieren, son ordenes distintas
//     //     }

//     //     // Crear copias ordenadas para su comparacion.
//     //     const existentesAdicionalesOrdenados = [...item.adicionales].sort();
//     //     const nuevosAdicionalesOrdenados = [...adicionales].sort();

//     //     // Revisar si cada adicional coincide
//     //     return existentesAdicionalesOrdenados.every((value, index) => value === nuevosAdicionalesOrdenados[index]);
//     // });

//     // const currentQty = existingItem ? existingItem.cantidad : 0;

//     const cantidadActual = detalle ? detalle.cantidad : 0;
//     // console.log("Cantidad actual del item: ", currentQty);
//     const cantidadNueva = cantidadActual + cantidad;
//     // const newQty = currentQty + cantidad;
//     // console.log("Nueva cantidad: ", newQty);

//     try {
//         const stockResponse = await checkProductStock(productId);
//         // console.log("Respuesta del backend: ", stockResponse);
        
//         // Si el stock es ilimitado, saltar validacion
//         if (stockResponse.unlimited) {
//         // console.log("El stock del item es ilimitado");
//             if (producto && detalle) {
//                 // Existe registro, aumentar cantidad;
//                 detalle.cantidad = cantidadNueva;
//             } else if (producto && !detalle) {
//                 // Existe registro, pero no un detalle de la orden
//                 producto.detalles.push({
//                     key: checkKey,
//                     adicionales,
//                     cantidad
//                 });
//                 // // cart.items.push({id: productId, cantidad: cantidad, adicionales: adicionales});
//             } else {
//                 cart.items.push({
//                     id: productId,
//                     detalles: [{
//                         key: checkKey,
//                         adicionales,
//                         cantidad
//                     }]
//                 });
//             }
//             localStorage.setItem('cart', JSON.stringify(cart));
//             // updateCartCounterEX()
//             if (!isUpdate) {
//                 // console.log("No es update")
//                 showAddedToast();
//             }
//             // Controlar reespuesta final
//             return {success: true, newQuantity: cantidadNueva, cart};
//         }
        
//         // CASO STOCK LIMITADO
//         // Comparar con el stock disponible
//         const stockDisponible = stockResponse.stock;
//         if (cantidadNueva > stockDisponible) {
//             console.error(`El stock del producto es ${stockDisponible}, la cantidad que se solicita es de ${cantidadNueva}`);
//             showLimitToast();
//             return {
//                 success: false, 
//                 message: `Solo hay ${stockDisponible}u disponibles`,
//                 cart
//             };
//         }
        
//         // SI EXISTE REGISTRO EN EL CARRITO
//         if (producto && detalle) {
//             detalle.cantidad = cantidadNueva;
//         } else if (producto && !detalle) {
//             producto.detalles.push({
//                 key: checkKey,
//                 adicionales,
//                 cantidad
//             });
//             // cart.items.push({id: productId, cantidad: cantidad, adicionales: adicionales});
//         } else {
//             cart.items.push({
//                 id: productId,
//                 detalles: [{
//                     key: checkKey,
//                     adicionales,
//                     cantidad
//                 }]
//             });
//         }
        
//         localStorage.setItem('cart', JSON.stringify(cart));
//         // updateCartCounterEX();
//         if (!isUpdate) {
//                 showAddedToast();
//         }
//         return {success: true, newQuantity: cantidadNueva, cart};
//     } catch (error) {
//         console.error('Error agregando el producto al carrito:', error);
//         return {
//             success: false, 
//             message: 'Error al agregar el producto al carrito',
//             cart
//         };
//     }
// }

// AGREGAR ITEMS AL CARRITO
export async function addToCart(productId, cantidad, isUpdate = false, adicionales = null) {
    const carrito = getCart();
    const checkKey = (adicionales && adicionales.length > 0) 
        ? adicionales.slice().sort().join("-") 
        : "base";

    // const { producto, detalle } = encontrarEnCarrito(productId, adicionales);
    const resultado = encontrarEnCarrito(carrito, productId, adicionales);
    const { producto, detalle } = resultado;
    const cantidadActual = detalle ? detalle.cantidad : 0;
    console.log("Cantidad Actual: ", cantidadActual)
    const cantidadNueva = cantidadActual + cantidad;
    console.log("Cantidad Nueva: ", cantidadNueva);

    try {
        const stockResponse = await checkProductStock(productId);
        
        // Validar stock solo si es limitado
        if (!stockResponse.unlimited) {
            const stockDisponible = stockResponse.stock;
            if (cantidadNueva > stockDisponible) {
                console.error(`El stock del producto es ${stockDisponible}, la cantidad que se solicita es de ${cantidadNueva}`);
                showLimitToast();
                return {
                    success: false, 
                    message: `Solo hay ${stockDisponible}u disponibles`,
                    cart: carrito
                };
            }
        }
        
        // Agregar o actualizar el item en el carrito
        if (producto && detalle) {
            // Existe registro, actualizar cantidad
            detalle.cantidad = cantidadNueva;
            console.log("Actualizando cantidad");
            console.log("Detalle después del update:", detalle);
            console.log("Cart después del update:", carrito);
        } else if (producto && !detalle) {
            // Existe producto, agregar nuevo detalle
            producto.detalles.push({
                key: checkKey,
                adicionales,
                cantidad
            });
        } else {
            // Crear nuevo producto con detalle
            carrito.items.push({
                id: productId,
                detalles: [{
                    key: checkKey,
                    adicionales,
                    cantidad
                }]
            });
        }
        
        // Guardar cambios y mostrar notificación
        console.log("Cart before save:", JSON.stringify(carrito))
        localStorage.setItem('cart', JSON.stringify(carrito));
        console.log("Los cambios se deberian haber guardado")
        // updateCartCounterEX();
        if (!isUpdate) {
            showAddedToast();
        }
        
        return {success: true, newQuantity: cantidadNueva, cart: carrito};
        
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
    const cart = getCart();
    console.log("Carrito actual:", cart);
    console.log("Id item a buscar", productId);
    const existingItem = cart.items.find(item => item.id == productId); // Use the same cart reference

    console.log("Item encontrado:", existingItem);
    console.log("Item a reducir: ", existingItem);
    const currentQty = existingItem.cantidad;
    if (cantidad >= currentQty) {
        return {success:false,message:"El valor no puede ser menor que 1"}
    }
    const newQty = currentQty - cantidad;

    existingItem.cantidad = newQty;
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCounterEX();
    console.log("Substraccion Exitosa, nueva cantidad: ", existingItem.cantidad)
    return {success: true,message:"Substraccion exitosa",newQuantity: newQty}
    } catch (error) {
        console.log(error)
    }

}

export const updateProductToMax = async (productId) => {
    const cart = getCart();
    const itemToMax = cart.items.find(item => item.id == productId); 
    try {
        const itemStockResponse = await checkProductStock(productId);
        const availableStock = Number(itemStockResponse.stock);
        itemToMax.cantidad = availableStock;
        localStorage.setItem('cart', JSON.stringify(cart));
        return {success: true, cantidad: itemToMax.cantidad}
    } catch (error) {
        console.error("Ocurrio un error al actualizar el stock solicitado al maximo: ", productId);
        return {success: false}
    }
}

window.carritoStorage = {
    getCart,
    getCartItem,
    addToCart,
    substractFromCart,
    removeProduct,
    updateProductToMax,
    emptyCart,
    updateCartCounterEX,
    updateCartItemDetailCounter
}