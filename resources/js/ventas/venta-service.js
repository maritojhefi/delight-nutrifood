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
        console.log("Llamado a agregarProductoVenta")
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

window.VentaService = {
    generarVentaQR,
    generarProductosVenta_Carrito,
    agregarProductoVenta
}