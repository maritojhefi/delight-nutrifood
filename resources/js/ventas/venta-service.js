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

window.VentaService = {
    generarVentaQR,
    generarProductosVenta_Carrito
}