// Cart Storage
const cartData = localStorage.getItem('cart');
const cart = cartData ? JSON.parse(cartData) : { items: [] };

//

export const getCart = () => {
  const data = localStorage.getItem('cart');
  return data ? JSON.parse(data) : {items:[]};
}

export function addToCart(productId, quantity, isPlan = false) {
    // 
  const cartData = localStorage.getItem('cart');
  const cart = cartData ? JSON.parse(cartData) : { items: [] };
  
  const existingItem = cart.items.find(item => item.id === productId);
  const currentQty = existingItem ? existingItem.quantity : 0;
  const newQty = currentQty + quantity;

  // Validar en comparacion al stock disponibla
  
  // Realizar consulta al backend para verificar que el stock sea el suficiente

  let availableStock = 3;

  if (newQty > availableStock) {
    console.log("El monto de items que desea agregarse es mayor al stock existente");
    return { success: false, message: `Solo hay ${availableStock}u de ${existingItem.nombre} disponibles`}
  }


  if (existingItem) {
    existingItem.quantity = newQty
  } else {
    cart.items.push( {id: productId, quantity, isPlan: isPlan} );
  }

  // Save to localStorage
  localStorage.setItem('cart', JSON.stringify(cart));

  return {success: true, cart};
}

