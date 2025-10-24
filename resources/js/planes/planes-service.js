import axios from "axios";
import cachedAxios from "../config/axios/axios-instance";

export const permisoPedido = async(idPlaneUser) => {
    const response = await axios.get(`/miperfil/permiso/${idPlaneUser}/2`);
    return response;
}

export const obtenerCalendarioPlan = async(idPlan, idUser) => {
    const response = await cachedAxios.get(`/miperfil/calendario-plan/${idPlan}/${idUser}`, {
        cache: {
            ttl: 1000 * 60 * 5,
            interpretHeader: false,
        }
    });
    // console.log("Respuesta obtenida sobre la informacion del plan: ", response.data);
    // console.log("Respuesta servida desde la cache: ", response.cached);
    return response; 
}


// Limpiar la cache de las solicitudes al plan del calendario
// Util en el caso de que el usuario actualice sus planes y sea necesaria la actualizacion de la cache

// import { clearCache } from 'axios-cache-interceptor';

// clearCache(`/miperfil/calendario-plan/${idPlan}/${idUser}`);

window.PlanesService = {
    permisoPedido,
    obtenerCalendarioPlan
};
