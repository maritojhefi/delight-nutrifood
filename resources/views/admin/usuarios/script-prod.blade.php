<script>
    document.addEventListener('DOMContentLoaded', function() {

let formulario = document.getElementById("form-cal");
let formBasic = document.getElementById("formBasic");
var calendarEl = document.getElementById('calendar');
var urlBase = "{{ GlobalHelper::getValorAtributoSetting('url_web')}}"
var calendar = new FullCalendar.Calendar(calendarEl, {
  initialView: 'dayGridMonth',
  locale:"es",
  hiddenDays: [ 0 ],
  selectable:true,
    events: urlBase+"/admin/usuarios/mostrar/"+{{$plan->id}}+"/"+{{$usuario->id}},
  dateClick:function(info){
      formulario.reset();
      formulario.start.value=info.dateStr;
      formulario.end.value=info.dateStr;
      //formulario.total.value=1;
      $("#modalcalendar").modal('show');
  },
  eventClick:function(info){
      var evento=info.event;
      console.log(evento);
      axios.get(urlBase+'/admin/usuarios/editar/'+info.event.id).
    then(
        (respuesta)=>{
            formBasic.title.value=respuesta.data.title;
            formBasic.detalle.value=respuesta.data.detalle;
            formBasic.id.value=respuesta.data.id;
            $("#basicModal").modal('show');
        }
        ).
    catch(error=>{
        if(error.response){
            console.log(error.response.data);
        }
    })
  },
  select: function(info) {
    var fecha1 = moment(info.startStr);
    var fecha2 = moment(info.endStr);
    var fecharestada = moment(info.endStr).subtract(1, "days").format("YYYY-MM-DD");
    
    formulario.reset();
    formulario.start.value=info.startStr;
    formulario.end.value=fecharestada;
    //formulario.total.value=fecha2.diff(fecha1, 'days');
    $("#modalcalendar").modal('show');
  }
});
calendar.render();

document.getElementById("btnGuardar").addEventListener("click",function(){
    const datos=new FormData(formulario);
    console.log(datos);
    console.log(formulario.nombre.value);
   
    axios.post(urlBase+'/admin/usuarios/agregarplan',datos).
    then(
        (respuesta)=>{
            calendar.refetchEvents();
            $("#modalcalendar").modal('hide');
        }
        ).
    catch(error=>{
        if(error.response){
            console.log(error.response.data);
        }
    })
});

document.getElementById("btnEliminar").addEventListener("click",function(){
    const datos=new FormData(formulario);
    
    
    axios.get(urlBase+'/admin/usuarios/borrar/'+formBasic.id.value).
    then(
        (respuesta)=>{
            calendar.refetchEvents();
            $("#basicModal").modal('hide');
            
        }
        ).
    catch(error=>{
        if(error.response){
            console.log(error.response.data);
        }
    })
});

document.getElementById("btnFeriado").addEventListener("click",function(){
    const datos=new FormData(formulario);
    
    axios.post(urlBase+'/admin/usuarios/feriado',datos).
    then(
        (respuesta)=>{
            calendar.refetchEvents();
            $("#modalcalendar").modal('hide');
        }
        ).
    catch(error=>{
        if(error.response){
            console.log(error.response.data);
        }
    })
});
});

</script>