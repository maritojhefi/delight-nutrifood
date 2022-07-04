@push('scripts')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            let formulario = document.getElementById("form-cal");
            let formBasic = document.getElementById("formBasic");
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: "es",
                hiddenDays: [0],
                selectable: true,
                //eventContent: { html: '<i>some html</i>' },
                events: "{{ $path }}/admin/usuarios/mostrar/" + {{ $plan->id }} + "/" +
                    {{ $usuario->id }},

                dayRender: function(date, cell) {

                    $(cell).addClass('disabled');

                },
                eventClick: function(info) {
                    var evento = info.event;

                    axios.get('{{ $path }}/admin/usuarios/editar/' + info.event.id).
                    then(
                        (respuesta) => {
                            if (respuesta.data.estado == "finalizado") {

                            } else if (respuesta.data.estado == "pendiente" && respuesta.data.title !=
                                "feriado") {

                                formBasic.id.value = respuesta.data.id;
                                $("#basicModal").modal('show');
                            } else if (respuesta.data.estado == "permiso" ) {
                                    
                            

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
            calendar.render();
            window.onload = function() {
                const myTimeout = setTimeout(resetear, 200);
            };

            $(".fc-icon").click(function() {
                const asd = setTimeout(resetear, 400);
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
                axios.get('{{ $path }}/admin/usuarios/permiso/' + formBasic.id.value).
                then(
                    (respuesta) => {
                        calendar.refetchEvents();
                        const asd = setTimeout(resetear, 400);
                        $("#basicModal").modal('hide');

                    }
                ).
                catch(error => {
                    if (error.response) {
                        console.log(error.response.data);
                        const asd = setTimeout(resetear, 400);
                    }
                })
            });
            document.getElementById("btnQuitar").addEventListener("click", function() {

                axios.get('{{ $path }}/admin/usuarios/quitarpermiso/' +
                    formulario.id.value).
                then(
                    (respuesta) => {
                        calendar.refetchEvents();
                        const asd = setTimeout(resetear, 400);
                        $("#permisoModal").modal('hide');
                    }
                ).
                catch(error => {
                    if (error.response) {
                        console.log(error.response.data);
                    }
                })
            });

        });
    </script>
@endpush
