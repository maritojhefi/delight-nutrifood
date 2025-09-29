import axios from 'axios';

export const generarVentaQR = async () => {
    try {
        const response = await axios.post('/ventas/ventaQR');
        return response.data;
    } catch (error) {
        console.error("Error al generar la nueva venta por QR:", error);
        throw error;
    }
}

export const generarProductosVenta_Carrito = async (carrito) => {
    try {
        const response = await axios.post('/ventas/sincronizar', {
            carrito: carrito,
        });
        return response.data;
    } catch (error) {
        console.error("Error al sincronizar informacion del carrito con la venta activa:", error);
        throw error;
    }
}

export const agregarProductoVenta = async (productoID, cantidad, IDsAdicionales = []) => {
    try {
        const response = await axios.post('/ventas/producto', {
            producto_id: productoID,
            adicionales_ids: IDsAdicionales,
            cantidad: cantidad
        });
        return response.data;
    } catch (error) {
        console.error("Error al agregar el producto a la venta:", error);
        throw error;
    }
}

export const productosVenta = async() => {
    const response = await axios.get('/ventas/productos');
    return response.data;
}

export const productoVenta = async(producto_venta_ID) => {
    const response = await axios.get(`/ventas/productos/${producto_venta_ID}`);
    return response.data;
}

export const actualizarObservacionPorID = async (pivotID,texto) => {
    const response = await axios.post(`ventas/producto/observacion`, {
        texto: texto,
        pivot_id: pivotID,
    })
    return response;
}

export const eliminarOrdenIndex = async (pivotID, index) => {
    const response = await axios.patch(`ventas/producto/eliminar-orden`, {
        // data: {
        pivot_id: pivotID,
        target_index: index
        // }
    });
    return response;
}

window.VentaService = {
    generarVentaQR,
    generarProductosVenta_Carrito,
    agregarProductoVenta,
    productosVenta,
    productoVenta,
    actualizarObservacionPorID,
    eliminarOrdenIndex
}