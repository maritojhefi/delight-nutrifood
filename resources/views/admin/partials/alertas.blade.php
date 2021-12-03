@if (session('success'))
<div class="alert alert-success alert-dismissible alert-alt fade show" id="contenido">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
    </button>
    <strong>Bien!</strong> {{session('success')}} 
</div>
    @endif
   
@if (session('danger'))
<div class="alert alert-danger alert-dismissible alert-alt solid fade show" id="contenido">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
    </button>
    <strong>Error!</strong> {{session('danger')}} 
</div>


@endif

<script>
    $(document).ready(function() {
        //Ejecutamos método que oculta las cajas
        OcultarContenedores1();
      });
      
      //Método que oculta el primer contenedor para mostrar el otro
      function OcultarContenedores1() {
        setTimeout(function() {
          $("#contenido").hide(1000);
        }, 5000);
      }
     

</script>