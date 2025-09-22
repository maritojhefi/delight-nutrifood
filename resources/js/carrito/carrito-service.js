// var urlBase = "{{ GlobalHelper::getValorAtributoSetting('url_web')}}"
import axios from 'axios';

export const getCartProductsInfo = async ({ sucursaleId, items }) => {
  try {
    const response = await axios.post(`/carrito/mi-carrito`, {
      sucursale_id: sucursaleId, // Enviar un valor numerico
      items: Array.isArray(items) ? items : [] // Asegura que items sea un array
    }
    // , {
    //   headers: {
    //     'Content-Type': 'application/json',
    //     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    //   }
    // }
  );
    
    return response.data;
  } catch (error) {
    console.error("Error al obtener información sobre productos del carrito:", error);
    throw error; // re lanza el error para que pueda ser manejado por el llamador
  }
}

// export const handleAgregarUnidad = async () => {
//   const ProductoID = $(this).data('producto-id');
//   try {
//       const agregarVentaProducto = await VentaService.agregarProductoVenta(ProductoID, 1);
//   } catch (error) {
//       if (error.response && error.response.status === 409) {
//           console.log("Pasando a agregar al carrito")
//           // Si el usuario no dispone de una venta activa (o no ha iniciado sesion) se agrega el producto al carrito
//           const AddAttempt = await carritoStorage.agregarAlCarrito(ProductoID, 1);
//           estaVerificando(false);
//           closeDetallesMenu();
//       } else if (error.response && error.response.status === 422) {
//           const { stockProducto, cantidadSolicitada } = error.response.data;
//           if (stockProducto <= 0)
//           {
//               // Re-renderizar listado de productos
//               // estaVerificando(false);
//               mostrarAvisoAgotado();
//           } else if (stockProducto < cantidadSolicitada) {
//               console.log("No hay suficiente stock disponible para completar la solicitud")
//           }
//       } else {
//           console.error('Error interno del servidor:', error);
//       }
//   }
// }

// export const sincronizarCarrito_ProductoVenta = (carrito) => {
//   const response = await axios.post(`/carrito/sincronizar`)
// };


window.getCartProductsInfo = getCartProductsInfo;
