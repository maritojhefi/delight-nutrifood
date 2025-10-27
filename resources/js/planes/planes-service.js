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
    // console.log("Respuesta obtenida sobre la informacion del plan: ", response.data);
    // console.log("Respuesta servida desde la cache: ", response.cached);
    return response; 
}

export const asignarPermisosVarios = async(fecha, cantidad, planId) => {
    const response = await axios.get(`/miperfil/permisos/${fecha}/${cantidad}/${planId}`);
    return response;
}


// Limpiar la cache de las solicitudes al plan del calendario
// Util en el caso de que el usuario actualice sus planes y sea necesaria la actualizacion de la cache

// import { clearCache } from 'axios-cache-interceptor';

// clearCache(`/miperfil/calendario-plan/${idPlan}/${idUser}`);

window.PlanesService = {
    permisoPedido,
    obtenerCalendarioPlan,
    asignarPermisosVarios
};
