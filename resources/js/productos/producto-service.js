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

export const getProduct = async (productId) => {
    try {
        const response = await axios.get(`/productos/${productId}`);
        return response.data;
    } catch (error) {
        console.error(`Error al solicitar el producto de ID: ${productId}`, error);
        throw error;
    }
}

export default {
    getProductosCategoria,
    checkProductStock
}

window.ProductoService = {
    getProductosCategoria,
    checkProductStock,
    getProduct
};