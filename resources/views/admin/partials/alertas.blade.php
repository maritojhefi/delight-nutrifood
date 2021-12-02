@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="contenido">
        <strong><span class="material-icons">
            done
            </span> 
            Notificacion:</strong> {{session('success')}} 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span class="material-icons">
            close
            </span>
        </button>
    </div>
    @endif
    @if (session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert" id="contenido">
        <strong><span class="material-icons">
            info
            </span> 
            Notificacion:</strong> {{session('info')}} 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span class="material-icons">
            close
            </span>
        </button>
    </div>
@endif
@if (session('danger'))
<div class="alert alert-danger alert-dismissible alert-alt solid fade show">
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