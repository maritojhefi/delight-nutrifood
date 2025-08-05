import axios from 'axios';

export const getProductosCategoria = async (categoriaId) => {
    try {
        const response = await axios.get(`productos/categorizados/${categoriaId}`);
        return response.data;
    } catch (error) {
        console.error(`Error al solicitar productos con subcategoria_id ${categoriaId}`, error);
        return [];
    }
};

window.ProductoService = {
    getProductosCategoria
};