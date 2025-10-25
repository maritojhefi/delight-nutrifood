import axios from "axios";

export const permisoPedido = async(idPlaneUser) => {
    const response = await axios.get(`/miperfil/permiso/${idPlaneUser}/2`);
    return response; 
}

// axios.get('{{ $path }}/miperfil/permiso/' + formBasic.id.value + '/2').
// then(
//     (respuesta) => {
//         if (respuesta.data == "varios") {
//             $("#basicModal").modal('hide');
//             $("#modalPermiso").modal('show');
//         } else {
//             calendar.refetchEvents();
//             const asd = setTimeout(resetear, 100);
//             const dsad = setTimeout(resetear, 300);
//             const asfdfd = setTimeout(resetear, 500);
//             const asfdffd = setTimeout(resetear, 1000);
//             $("#basicModal").modal('hide');
//             $("#modalPermiso").modal('hide');
//             var toastID = document.getElementById('permiso-aceptado');
//                 toastID = new bootstrap.Toast(toastID);
//                 toastID.show();
//         }


//     }
// ).

window.PlanesService = {
    permisoPedido,
};
