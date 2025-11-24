import axios from 'axios';
import { loaderAxios } from '../config/axios/axios-instance.js';

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
        const response = await loaderAxios.post('/ventas/sincronizar', {
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
        const response = await loaderAxios.post('/ventas/producto', {
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


export const disminuirProductoVenta = async(producto_venta_ID) => {
    const response = await loaderAxios.patch(`/ventas/producto/disminuir-orden`, {
        producto_venta_id: producto_venta_ID,
    });
    return response.data;
}

export const productosVenta = async() => {
    const response = await loaderAxios.get('/ventas/productos');
    return response.data;
}

export const productoVenta = async(producto_venta_ID) => {
    const response = await loaderAxios.get(`/ventas/productos/${producto_venta_ID}`);
    return response.data;
}

export const actualizarObservacionPorID = async (pivotID,texto) => {
    const response = await loaderAxios.post(`/ventas/producto/observacion`, {
        texto: texto,
        pivot_id: pivotID,
    })
    return response;
}

export const eliminarOrdenIndex = async (pivotID, index) => {
    const response = await loaderAxios.patch(`/ventas/producto/eliminar-orden`, {
        // data: {
        pivot_id: pivotID,
        target_index: index
        // }
    });
    return response;
}

export const ordenVentaIndex = async(producto_venta_id, indice) => {
    const response = await axios.get(`/ventas/producto/${producto_venta_id}/orden/${indice}`);
    return response.data;
}

export const actualizarOrdenVentaIndex = async(producto_id, indice, adicionalesIds, sucursale_id) => {
    const response = await loaderAxios.patch(`/ventas/productos/actualizar-orden`, {
        producto_id: producto_id,
        // producto_venta_id: productoVentaId,
        indice: indice,
        adicionalesIds: adicionalesIds,
        sucursale_id: sucursale_id,
    });
    return response.data;
}

export const eliminarPedidoCompleto = async (pivotID) => {
    const response = await loaderAxios.delete(`/ventas/producto/${pivotID}`);
    return response;
}

window.VentaService = {
    generarVentaQR,
    generarProductosVenta_Carrito,
    agregarProductoVenta,
    productosVenta,
    productoVenta,
    actualizarObservacionPorID,
    eliminarOrdenIndex,
    eliminarPedidoCompleto,
    disminuirProductoVenta,
    ordenVentaIndex,
    actualizarOrdenVentaIndex
}