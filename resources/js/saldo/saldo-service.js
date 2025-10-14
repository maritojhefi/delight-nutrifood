import axios from 'axios';

export const obtenerHistorialSaldo = async (pagina, limite) => {
    const response = await axios.get(`/miperfil/saldo/historial`, {
        params: {
            pagina: pagina,
            limite: limite,
        }
    });
    return response.data
}

window.SaldoService = {
    obtenerHistorialSaldo,
};