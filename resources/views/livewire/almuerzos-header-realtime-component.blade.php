<div class="row letra14 bordeado bordeado-pulse d-none d-lg-flex">

    @isset($menuDia)
        <div class="col mx-auto text-center m-0 p-0">
            <span
                class="badge  badge-{{ App\Helpers\GlobalHelper::cantidadColor($menuDia->ejecutivo_cant) }} py-1 m-1"><strong>{{ $menuDia->ejecutivo_cant }}</strong></span><br>
            <strong class="letra10">{{ Str::limit($menuDia->ejecutivo, 15) }}</strong>
            <h5 class="" style="font-size: 8px !important">Ejecutivo</h5>
        </div>

        <div class="col mx-auto text-center m-0 p-0">
            <span
                class="badge badge-{{ App\Helpers\GlobalHelper::cantidadColor($menuDia->dieta_cant) }} py-1 m-1"><strong>{{ $menuDia->dieta_cant }}</strong></span><br>
            <strong class="letra10">{{ Str::limit($menuDia->dieta, 15) }}</strong>
            <h5 class="" style="font-size: 8px !important">Dieta</h5>
        </div>

        <div class="col mx-auto text-center m-0 p-0">
            <span
                class="badge badge-{{ App\Helpers\GlobalHelper::cantidadColor($menuDia->vegetariano_cant) }} py-1 m-1"><strong>{{ $menuDia->vegetariano_cant }}</strong></span><br>
            <strong class="letra10">{{ Str::limit($menuDia->vegetariano, 15) }}</strong>
            <h5 class="" style="font-size: 8px !important">Vegetariano</h5>
        </div>

        <div class="col mx-auto text-center m-0 p-0">
            <span
                class="badge badge-{{ App\Helpers\GlobalHelper::cantidadColor($menuDia->carbohidrato_1_cant) }} py-1 m-1"><strong>{{ $menuDia->carbohidrato_1_cant }}</strong></span><br>
            <strong class="letra10">{{ Str::limit($menuDia->carbohidrato_1, 15) }}</strong>
            <h5 class="" style="font-size: 8px !important">Carbo 1</h5>
        </div>

        <div class="col mx-auto text-center m-0 p-0">
            <span
                class="badge badge-{{ App\Helpers\GlobalHelper::cantidadColor($menuDia->carbohidrato_2_cant) }} py-1 m-1"><strong>{{ $menuDia->carbohidrato_2_cant }}</strong></span><br>
            <strong class="letra10">{{ Str::limit($menuDia->carbohidrato_2, 15) }}</strong>
            <h5 class="" style="font-size: 8px !important">Carbo 2</h5>
        </div>

        <div class="col mx-auto text-center m-0 p-0">
            <span
                class="badge badge-{{ App\Helpers\GlobalHelper::cantidadColor($menuDia->carbohidrato_3_cant) }} py-1 m-1"><strong>{{ $menuDia->carbohidrato_3_cant }}</strong></span><br>
            <strong class="letra10">{{ Str::limit($menuDia->carbohidrato_3, 15) }}</strong>
            <h5 class="" style="font-size: 8px !important">Carbo 3</h5>
        </div>
        <div class="col mx-auto text-center m-0 p-0">
            <span
                class="badge badge-{{ App\Helpers\GlobalHelper::cantidadColor($menuDia->sopa_cant) }} py-1 m-1"><strong>{{ $menuDia->sopa_cant }}</strong></span><br>
            <strong class="letra10">{{ Str::limit($menuDia->sopa, 15) }}</strong>
            <h5 class="" style="font-size: 8px !important">SOPA</h5>
        </div>
    @else
       <h1 class="text-center m-3">Sin informacion del menú para el dia de hoy</h1> 
    @endisset
</div>
