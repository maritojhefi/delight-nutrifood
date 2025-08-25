<div class="col-12">
    <div data-card-height="130" class="card card-style mb-4 mx-0 hover-grow-s" style="overflow: hidden">
        <div class="d-flex flex-row align-items-center gap-3"> 
            <a href="{{route('detalleproducto',$producto->id)}}" class="product-card-image">
                <img src="{{asset('imagenes/productos/'.$producto->imagen)}}" 
                    onerror="this.src='/imagenes/delight/default-bg-1.png';" 
                    style="background-color: white;" />
            </a>
            <div class="d-flex flex-column w-100 gap-1 me-2" style="max-width: 260px">
                <h4 class="me-1">{{ucfirst(strtolower($producto->nombre))}}</h4>
                <div class="tags-container d-flex flex-row align-items-center justify-content-start gap-2">
                    @if($producto->tag->isNotEmpty())
                        @foreach ($producto->tag as $tag_individual)
                            <button
                                popovertarget="poppytag-{{$producto->id}}-{{$tag_individual->id}}"
                                popoveraction="toggle"
                                style="anchor-name: --tag-btn-{{$producto->i}}-{{$tag_individual->id}};"
                            >
                                <i data-lucide="{{$tag_individual->icono}}" class="lucide-icon" style="width:1.5rem;height:1.5rem;">
                                </i>
                            <div popover 
                                id="poppytag-{{$producto->id}}-{{$tag_individual->id}}" 
                                class="tag-info-popover bg-white bg-dtheme-blue p-2 rounded-2 shadow-lg border"
                                >
                                <p class="color-theme">{{$tag_individual->nombre}}</p>     
                            </div>
                        @endforeach
                    @endif
                        {{-- <i  data-lucide="wheat-off" 
                            class="lucide-icon "
                            style="width: 1rem; height: 1rem;"></i>
                        <i  data-lucide="milk-off" 
                            class="lucide-icon "
                            style="width: 1rem; height: 1rem;"></i> --}}
                </div>
                <div class="d-flex flex-row align-items-center justify-content-between gap-4">
                    @if (($producto->descuento < $producto->precio) && ($producto->descuento > 0))
                        <div class="d-flex flex-column">
                            <p class="font-10 mb-0 mt-n2"><del>Bs. {{$producto->precio}}</del></p>
                            <p class="font-24 mt-n2 font-weight-bolder color-highlight mb-0">Bs. {{$producto->descuento}}</p>
                        </div>
                    @else
                        <p class="font-18 font-weight-bolder color-highlight mb-0">Bs. {{$producto->precio}}</p>
                    @endif
                    <div class="d-flex flex-row gap-2">
                        <button ruta="{{route('detalleproducto',$producto->id)}}" class="btn btn-xs copiarLink rounded-s btn-full shadow-l bg-red-light font-900">
                            <i class="fa fa-link"></i>
                        </button>
                        @if ($producto->tiene_stock == false)
                                <button class="btn btn-xs  rounded-s btn-full shadow-l bg-gray-dark font-900 text-uppercase" disabled>
                                    <i class="fa fa-ban"></i>
                                    Sin Stock
                                </button>
                        @else
                            <button
                                class="add-to-cart btn btn-xs  rounded-s btn-full shadow-l bg-highlight font-900 text-uppercase"
                                data-producto-id="{{$producto->id}}"
                                data-producto-nombre="{{$producto->nombre}}"
                            >
                                <i class="fa fa-shopping-cart"></i>
                                <span class="font-11">Añadir</span>
                            </button>
                        @endif
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>