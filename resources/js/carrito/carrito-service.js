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

window.getCartProductsInfo = getCartProductsInfo;
