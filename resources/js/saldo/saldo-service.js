import axios from 'axios';
import { loaderAxios } from '../config/axios/axios-instance';

export const obtenerHistorialSaldo = async (pagina, limite) => {
    const response = await loaderAxios.get(`/miperfil/saldo/historial`, {
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