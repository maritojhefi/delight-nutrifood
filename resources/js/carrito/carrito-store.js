import { checkProductStock } from "../productos/producto-service";

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
        detailCounter.textContent = itemData ? itemData.quantity : 0;
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
        const totalQuantity = cart.items.reduce((sum, item) => sum + item.quantity, 0);
        // // console.log("TotalQuantity: ", totalQuantity)
        cartCounter.textContent = totalQuantity;
        cartCounter.style.display = 'inline-block'; 
    } else {
        cartCounter.textContent = '';
        cartCounter.style.display = 'none';
    }
}

// AGREGAR ITEMS AL CARRITO
export async function addToCart(productId, quantity, isUpdate = false, adicionales = null) {
  const cart = getCart();

  // console.log("Carrito actual:", cart);

  const existingItem = cart.items.find(item => item.id == productId);

  // console.log("Item a agregar: ", existingItem);
  const currentQty = existingItem ? existingItem.quantity : 0;
  // console.log("Cantidad actual del item: ", currentQty);
  const newQty = currentQty + quantity;
  // console.log("Nueva cantidad: ", newQty);

  try {
    const stockResponse = await checkProductStock(productId);
    // console.log("Respuesta del backend: ", stockResponse);
    
    // Si el stock es ilimitado, saltar validacion
    if (stockResponse.unlimited) {
    // console.log("El stock del item es ilimitado");
        if (existingItem) {
            existingItem.quantity = newQty;
        } else {
            cart.items.push({id: productId, quantity: quantity, adicionales: adicionales});
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCounterEX()
        if (!isUpdate) {
            // console.log("No es update")
                showAddedToast();
        }
        return {success: true, newQuantity: newQty, cart};
    }
    
    // Comparar con el stock disponible
    const availableStock = stockResponse.stock;
    if (newQty > availableStock) {
        console.error(`El stock del producto es ${availableStock}, la cantidad que se solicita es de ${newQty}`);
        showLimitToast();
        return {
            success: false, 
            message: `Solo hay ${availableStock}u disponibles`,
            cart
        };
    }
    
    if (existingItem) {
        existingItem.quantity = newQty;
    } else {
        cart.items.push({id: productId, quantity: quantity, adicionales: adicionales});
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCounterEX()
    if (!isUpdate) {
            // console.log("No es update")
            showAddedToast();
    }
    return {success: true, newQuantity: newQty, cart};
    } catch (error) {
        console.error('Error checking stock:', error);
        return {
            success: false, 
            message: 'Error al verificar stock del producto',
            cart
        };
    }
}

export const substractFromCart = (productId, quantity) => {
    if (quantity <= 0) {
        return {success:false,message:"El valor no puede ser menor que 1"}
    }
    const cart = getCart();
    // console.log("Carrito actual:", cart);
    // console.log("Id item a buscar", productId);
    const existingItem = cart.items.find(item => item.id == productId); // Use the same cart reference

    // console.log("Item encontrado:", existingItem);
    // console.log("Item a reducir: ", existingItem);
    const currentQty = existingItem.quantity;
    if (quantity >= currentQty) {
        return {success:false,message:"El valor no puede ser menor que 1"}
    }
    const newQty = currentQty - quantity;

    existingItem.quantity = newQty;
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCounterEX();
    return {success: true,message:"Substraccion exitosa",newQuantity: newQty}
}

export const updateProductToMax = async (productId) => {
    const cart = getCart();
    const itemToMax = cart.items.find(item => item.id == productId); 
    try {
        const itemStockResponse = await checkProductStock(productId);
        const availableStock = Number(itemStockResponse.stock);
        itemToMax.quantity = availableStock;
        localStorage.setItem('cart', JSON.stringify(cart));
        return {success: true, quantity: itemToMax.quantity}
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