<div class="col-12">
    <div data-card-height="140" class="card card-style mb-4 mx-0 hover-grow-s" style="overflow: hidden">
        <div class="d-flex flex-row align-items-center gap-2"> 
            <a href="{{route('detalleproducto',$producto->id)}}" class="product-card-image">
                <img 
                    src="{{$producto->pathAttachment()}}"
                    onerror='this.src="{{ GlobalHelper::getValorAtributoSetting("bg_default") }}"'
                    style="background-color: white;min-width: 130px" />
            </a>
            <div class="d-flex flex-column w-100 gap-1 me-2">
                <h4 class="me-1 font-20" style="max-height: 8rem;overflow: hidden">{{Str::limit($producto->nombre(),35)}}</h4>
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
                </div>
                <div class="d-flex flex-row align-items-center justify-content-between">
                    @if (($producto->descuento < $producto->precio) && ($producto->descuento > 0))
                        <div class="d-flex flex-column m-0 justify-content-center w-100">
                            <p class="font-10 m-0"><del>Bs. {{$producto->precio}}</del></p>
                            <p class="font-18 font-weight-bolder color-highlight mb-0">Bs. {{$producto->descuento}}</p>
                        </div>
                    @else
                        <p class="font-18 font-weight-bolder color-highlight mb-0">Bs. {{$producto->precio}}</p>
                    @endif
                    <div class="d-flex flex-row gap-1">
                        <button ruta="{{route('detalleproducto',$producto->id)}}" class="btn px-1 copiarLink rounded-s bg-red-light font-900">
                            <i class="fa fa-link mx-1"></i>
                        </button>
                        @if ($producto->tiene_stock == false)
                                <button class="btn btn-xs  rounded-s btn-full shadow-l bg-gray-dark font-900 text-uppercase" disabled>
                                    <div class="d-flex flex-row align-items-center gap-1">
                                        <i class="fa fa-ban"></i>
                                        <span class="font-10">Sin Stock</span>
                                    </div>
                                </button>
                        @else
                            <button
                                class="{{ $producto->tiene_adicionales ? "menu-adicionales-btn":"agregar-unidad" }} btn rounded-s px-1 shadow-l bg-highlight font-900 text-uppercase"
                                data-producto-id="{{$producto->id}}"
                                data-producto-nombre="{{$producto->nombre}}"
                            >
                                <div class="d-flex flex-row align-items-center gap-1">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="font-10">Añadir</span>
                                </div>
                            </button>
                        @endif
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>