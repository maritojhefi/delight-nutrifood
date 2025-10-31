@push('header')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/locales-all.min.js"></script>
    <script src="{{ asset('js/calendario.js') }}"></script>
@endpush
@push('scripts')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', async function() {

            // await PlanesService.obtenerCalendarioPlan( {{ $plan->id }},{{ $usuario->id }} ).then(
            //     (respuestaCalendario) => {
            //         console.log("Respuesta obtenida sobre la informacion del plan: ", respuestaCalendario.data);
            //         console.log("Respuesta servida desde la cache: ", respuestaCalendario.cached);
            //     }
            // );

            // await PlanesService.obtenerCalendarioPlan( {{ $plan->id }},{{ $usuario->id }} ).then(
            //     (respuestaCalendario) => {
            //         console.log("Respuesta obtenida sobre la informacion del plan: ", respuestaCalendario.data);
            //         console.log("Respuesta servida desde la cache: ", respuestaCalendario.cached);
            //     }
            // );

            let formulario = document.getElementById("form-cal");
            let formBasic = document.getElementById("formBasic");
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                height: 'auto',
                initialView: 'dayGridMonth',
                locale: "es",
                hiddenDays: [0],
                selectable: true,
                //eventContent: { html: '<i>some html</i>' },
                // Cada evento es una construccion de url para mostrar plan
                events: "{{ $path }}/miperfil/mostrar/" + {{ $plan->id }} + "/" +
                    {{ $usuario->id }},

                // events: "{{ $path }}/miperfil/calendario-plan/" + {{ $plan->id }} + "/" +
                //     {{ $usuario->id }},
                // events:undefined,


                eventClick: function(info) {
                    var evento = info.event;
                    // Al hacer click en un evento, se realiza la solicitud a la url
                    axios.get('{{ $path }}/miperfil/editar/' + info.event.id).
                    then(
                        (respuesta) => {
                            if (respuesta.data.estado == "finalizado") {
                                console.log('asdsa');
                                var toastID = document.getElementById('toast-finalizado');
                                toastID = new bootstrap.Toast(toastID);
                                toastID.show();
                            }else if(respuesta.data.estado == "desarrollo")
                            {
                                var toastID = document.getElementById('toast-whatsapp');
                                toastID = new bootstrap.Toast(toastID);
                                toastID.show();
                            }
                            else if (respuesta.data.estado == "pendiente" && respuesta.data
                                .title !=
                                "feriado") {

                                formBasic.id.value = respuesta.data.id;
                                $("#basicModal").modal('show');
                            } else if (respuesta.data.estado == "permiso") {
                                var toastID = document.getElementById('toast-permiso');
                                toastID = new bootstrap.Toast(toastID);
                                toastID.show();
                            } else if (respuesta.data.estado == "archivado") {
                                var toastID = document.getElementById('toast-archivado');
                                toastID = new bootstrap.Toast(toastID);
                                toastID.show();
                            }

                        }
                    ).
                    catch(error => {
                        if (error.response) {
                            console.log(error.response.data);
                        }
                    })
                }


            });
            console.log("Info calendario a renderizar: ", calendar);
            calendar.render();
            window.onload = function() {
                const myTimeout = setTimeout(resetear, 500);


            };

            $(".fc-icon").click(function() {
                const asd = setTimeout(resetear, 100);
                const dsad = setTimeout(resetear, 300);
                const asfdfd = setTimeout(resetear, 500);
            });

            function resetear() {
                console.log('listo');
                //$(".fc-daygrid-day").children().attr("disabled","disabled");
                $("#calendar a").attr("href", "#");
                $(".fc-daygrid-event").attr("href", "#");
                console.log('listo2');

            }
            document.getElementById("btnPermiso").addEventListener("click", function() {

                console.log('escuchando');
                axios.get('{{ $path }}/miperfil/permiso/' + formBasic.id.value + '/0').
                then(
                    (respuesta) => {
                        if (respuesta.data == "varios") {
                            $("#basicModal").modal('hide');
                            $("#modalPermiso").modal('show');
                        } else {
                            calendar.refetchEvents();
                            const asd = setTimeout(resetear, 100);
                            const dsad = setTimeout(resetear, 300);
                            const asfdfd = setTimeout(resetear, 500);
                            const asfdffd = setTimeout(resetear, 1000);
                            $("#basicModal").modal('hide');
                            var toastID = document.getElementById('permiso-aceptado');
                                toastID = new bootstrap.Toast(toastID);
                                toastID.show();
                        }


                    }
                ).
                catch(error => {
                    if (error.response) {
                        console.log(error.response.data);
                        const asd = setTimeout(resetear, 100);
                        const dsad = setTimeout(resetear, 300);
                        const asfdfd = setTimeout(resetear, 500);
                        const asfdfdd = setTimeout(resetear, 1000);
                    }
                })
            });

            document.getElementById("btnPermisoUno").addEventListener("click", function() {

                console.log('escuchando');
                axios.get('{{ $path }}/miperfil/permiso/' + formBasic.id.value + '/2').
                then(
                    (respuesta) => {
                        if (respuesta.data == "varios") {
                            $("#basicModal").modal('hide');
                            $("#modalPermiso").modal('show');
                        } else {
                            calendar.refetchEvents();
                            const asd = setTimeout(resetear, 100);
                            const dsad = setTimeout(resetear, 300);
                            const asfdfd = setTimeout(resetear, 500);
                            const asfdffd = setTimeout(resetear, 1000);
                            $("#basicModal").modal('hide');
                            $("#modalPermiso").modal('hide');
                            var toastID = document.getElementById('permiso-aceptado');
                                toastID = new bootstrap.Toast(toastID);
                                toastID.show();
                        }


                    }
                ).
                catch(error => {
                    if (error.response) {
                        console.log(error.response.data);
                        const asd = setTimeout(resetear, 100);
                        const dsad = setTimeout(resetear, 300);
                        const asfdfd = setTimeout(resetear, 500);
                        const asfdfdd = setTimeout(resetear, 1000);
                    }
                })
            });

            document.getElementById("btnPermisoTodos").addEventListener("click", function() {

                console.log('escuchando');
                axios.get('{{ $path }}/miperfil/permiso/' + formBasic.id.value + '/1').
                then(
                    (respuesta) => {
                        if (respuesta.data == "varios") {
                            $("#basicModal").modal('hide');
                            $("#modalPermiso").modal('show');
                        } else {
                            calendar.refetchEvents();
                            const asd = setTimeout(resetear, 100);
                            const dsad = setTimeout(resetear, 300);
                            const asfdfd = setTimeout(resetear, 500);
                            const asfdffd = setTimeout(resetear, 1000);
                            $("#basicModal").modal('hide');
                            $("#modalPermiso").modal('hide');
                            var toastID = document.getElementById('permiso-aceptado');
                                toastID = new bootstrap.Toast(toastID);
                                toastID.show();
                        }


                    }
                ).
                catch(error => {
                    if (error.response) {
                        console.log(error.response.data);
                        const asd = setTimeout(resetear, 100);
                        const dsad = setTimeout(resetear, 300);
                        const asfdfd = setTimeout(resetear, 500);
                        const asfdfdd = setTimeout(resetear, 1000);
                    }
                })
            });
        });
    </script>
@endpush