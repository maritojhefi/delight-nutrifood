// Cart Storage
// const cartData = localStorage.getItem('cart');
// const cart = cartData ? JSON.parse(cartData) : { items: [] };

// OBTENER CARRITO
export const getCart = () => {
  const data = localStorage.getItem('cart');
  return data ? JSON.parse(data) : {items:[]};
}

// VACIAR CARRITO
export const emptyCart = () => {
    console.log("emptyCart fue llamado")
    localStorage.removeItem('cart');
}

export const showAddedToast = () => {
    console.log("Showing Added to cart toast");
    var toaster = document.getElementById('toast-cart-added');
    const cart = new bootstrap.Toast(toaster);
    cart.show()
    setTimeout(() => {
        cart.hide();
    }, 1000); // Ocultar tras 1 segundo
}

export const showLimitToast = () => {
    console.log("Showing cart item limit reached toast");
    var toaster = document.getElementById('toast-cart-item-limit');
    const cart = new bootstrap.Toast(toaster);
    cart.show()
    setTimeout(() => {
        cart.hide();
    }, 1000); // Ocultar tras 1 segundo
}

// AGREGAR ITEMS AL CARRITO
export async function addToCart(productId, quantity, isPlan = false) {
//   const cartData = localStorage.getItem('cart');
//   const cart = cartData ? JSON.parse(cartData) : { items: [] };
  const cart = getCart();

  console.log("Carrito catual:", cart);

  const existingItem = cart.items.find(item => item.id === productId);

  console.log("Item a agregar: ", existingItem);
  const currentQty = existingItem ? existingItem.quantity : 0;
  console.log("Cantidad actual del item: ", currentQty);
  const newQty = currentQty + quantity;
  console.log("Nueva cantidad: ", newQty);

  try {
      const stockResponse = await ProductoService.checkProductStock(productId);
      console.log("Respuesta del backend: ", stockResponse);
      
      // Si el stock es ilimitado, saltar validacion
      if (stockResponse.unlimited) {
        console.log("El stock del item es ilimitado");
          if (existingItem) {
              existingItem.quantity = newQty;
          } else {
              cart.items.push({id: productId, quantity, isPlan: isPlan});
          }
          
          
          localStorage.setItem('cart', JSON.stringify(cart));
          showAddedToast();
          return {success: true, cart};
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
          cart.items.push({id: productId, quantity, isPlan: isPlan});
      }
      
      localStorage.setItem('cart', JSON.stringify(cart));
      showAddedToast();
      return {success: true, cart};
      
  } catch (error) {
      console.error('Error checking stock:', error);
      return {
          success: false, 
          message: 'Error al verificar stock del producto',
          cart
      };
  }
}


