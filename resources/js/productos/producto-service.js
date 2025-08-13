import axios from 'axios';

// FUNCIONES CON LLAMADO A ENDPOINTS DEL BACKEND - PRODUCTO
export const getProductosCategoria = async (categoriaId) => {
    try {
        const response = await axios.get(`productos/categorizados/${categoriaId}`);
        return response.data;
    } catch (error) {
        console.error(`Error al solicitar productos con subcategoria_id ${categoriaId}`, error);
        return [];
    }
};

export const checkProductStock = async (productId) => {
    try {
        const response = await axios.get(`/productos/${productId}/stock`);
        return response.data;
    } catch (error) {
        console.error(`Error al solicitar stock del producto ${productId}`, error);
        throw error;
    }
}

window.ProductoService = {
    getProductosCategoria,
    checkProductStock
};