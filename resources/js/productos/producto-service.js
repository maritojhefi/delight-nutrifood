import axios from 'axios';

// FUNCIONES CON LLAMADO A ENDPOINTS DEL BACKEND - PRODUCTO
export const getProductosCategoria = async (categoriaId) => {
    try {
        const response = await axios.get(`/productos/categorizados/${categoriaId}`);
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

export const validarProductoConAdicionales = async (productoID, selectedIds) => {
    try {
        const response = await axios.post(`/productos/validar-adicionales`, {
            producto_id: productoID,
            adicionales_ids: selectedIds
        });
        return response.data;
    } catch (error) {
        console.error("Error al validar adicionales:", error);
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

// Obtener productos pertenecientes a un tag determinado
export const getProductosTag = async (tagId) => {
    try {
        const response = await axios.get(`/productos/tag/${tagId}`);
        return response.data;
    } catch (error) {
        console.log(error);
        throw error;
    }
}

export const getProductoDetalle = async (productoId) => {
    try {
        const response = await axios.get(`/productos/${productoId}/detallado`);
        return response.data;
    } catch (error) {
        console.error(error);
        throw error;
    }
}

export const getSearchedProducts = async (type,query) => {
    const response = await axios.get(`/productos/buscar/${type}/${encodeURIComponent(query)}`);
    return response.data;
}

export default {
    getProductosCategoria,
    checkProductStock
}

window.ProductoService = {
    getProductosCategoria,
    checkProductStock,
    getProduct,
    getProductoDetalle,
    getProductosTag,
    getSearchedProducts,
    validarProductoConAdicionales
};