import axios from "axios";
import cachedAxios from "../config/axios/axios-instance";

export const permisoPedido = async(idPlaneUser) => {
    const response = await axios.get(`/miperfil/permiso/${idPlaneUser}/2`);
    return response;
}

// export const obtenerCalendarioPlan = async(idPlan, idUser) => {
//     const response = await cachedAxios.get(`/miperfil/calendario-plan/${idPlan}/${idUser}`, {
//         cache: {
//             ttl: 1000 * 60 * 5,
//             interpretHeader: false,
//         }
//     });
//     return response; 
// }

export const obtenerCalendarioPlan = async(idPlan, idUser) => {
    const response = await axios.get(`/miperfil/calendario-plan/${idPlan}/${idUser}`);
    return response; 
}

// export const asignarPermisosVarios = async(fecha, cantidad, planId) => {
//     const response = await axios.get(`/miperfil/permisos/${fecha}/${cantidad}/${planId}`);
//     return response;
// }

export const asignarPermisosVarios = async (fecha, cantidad, planId, usuarioId) => {
    const response = await axios.post(`/miperfil/permisos/asignar-permisos`, {
        fecha,
        cantidad,
        planId,
        usuarioId
    });
    return response;
};


export const deshacerPermisosVarios = async(fecha, cantidad, planId, usuarioId) => {
    const response = await axios.post(`/miperfil/permisos/deshacer-permisos`, {
        fecha: fecha,
        cantidad: cantidad,
        planId: planId,
        usuarioId: usuarioId
    });
    return response;
}


// Limpiar la cache de las solicitudes al plan del calendario
// Util en el caso de que el usuario actualice sus planes y sea necesaria la actualizacion de la cache

// import { clearCache } from 'axios-cache-interceptor';

// clearCache(`/miperfil/calendario-plan/${idPlan}/${idUser}`);

window.PlanesService = {
    permisoPedido,
    obtenerCalendarioPlan,
    asignarPermisosVarios,
    deshacerPermisosVarios
};
