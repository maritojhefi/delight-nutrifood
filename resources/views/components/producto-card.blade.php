@props(['producto', 'version' => 1])

@if($version == 1)
    {{-- Versión 1: diseño original (compatibilidad con usos existentes) --}}
    <div {{ $attributes->merge(['class' => 'col-12']) }}>
        <div data-card-height="140" class="card card-style mb-4 mx-0 hover-grow-s" style="overflow: hidden">
            <div class="d-flex flex-row align-items-center gap-2"> 
                <a href="{{ route('detalleproducto', $producto->id) }}" class="product-card-image">
                    <img 
                        src="{{ $producto->pathAttachment() }}"
                        onerror='this.src="{{ GlobalHelper::getValorAtributoSetting("bg_default") }}"'
                        style="background-color: white;min-width: 130px" />
                </a>
                <div class="d-flex flex-column w-100 gap-1 me-2">
                    <h4 class="me-1 font-20" style="max-height: 8rem;overflow: hidden">
                        {{ Str::limit($producto->nombre(), 35) }}
                    </h4>
                    <div class="tags-container d-flex flex-row align-items-center justify-content-start gap-2">
                        @if($producto->tag->isNotEmpty())
                            @foreach ($producto->tag as $tag_individual)
                                <button
                                    popovertarget="poppytag-{{ $producto->id }}-{{ $tag_individual->id }}"
                                    popoveraction="toggle"
                                    style="anchor-name: --tag-btn-{{ $producto->i }}-{{ $tag_individual->id }};"
                                >
                                    <i data-lucide="{{ $tag_individual->icono }}" class="lucide-icon" style="width:1.5rem;height:1.5rem;">
                                    </i>
                                <div popover 
                                    id="poppytag-{{ $producto->id }}-{{ $tag_individual->id }}" 
                                    class="tag-info-popover bg-white bg-dtheme-blue p-2 rounded-2 shadow-lg border"
                                    >
                                    <p class="color-theme">{{ $tag_individual->nombre }}</p>     
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="d-flex flex-row align-items-center justify-content-between">
                        @if (($producto->descuento < $producto->precio) && ($producto->descuento > 0))
                            <div class="d-flex flex-column m-0 justify-content-center w-100">
                                <p class="font-10 m-0"><del>Bs. {{ $producto->precio }}</del></p>
                                <p class="font-18 font-weight-bolder color-highlight mb-0">Bs. {{ $producto->descuento }}</p>
                            </div>
                        @else
                            <p class="font-18 font-weight-bolder color-highlight mb-0">Bs. {{ $producto->precio }}</p>
                        @endif
                        <div class="d-flex flex-row gap-1">
                            <button ruta="{{ route('detalleproducto', $producto->id) }}" class="btn px-1 copiarLink rounded-s bg-red-light font-900">
                                <i class="fa fa-link mx-1"></i>
                            </button>
                            @if ($producto->tiene_stock == false)
                                    <button class="btn btn-xs rounded-s btn-full shadow-l bg-gray-dark font-900 text-uppercase" disabled>
                                        <div class="d-flex flex-row align-items-center gap-1">
                                            <i class="fa fa-ban"></i>
                                            <span class="font-10">Sin Stock</span>
                                        </div>
                                    </button>
                            @else
                                <button
                                    class="{{ $producto->tiene_adicionales ? 'menu-adicionales-btn' : 'agregar-unidad' }} btn rounded-s px-1 shadow-l bg-highlight font-900 text-uppercase"
                                    data-producto-id="{{ $producto->id }}"
                                    data-producto-nombre="{{ $producto->nombre }}"
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
@else
    {{-- Versión 2: nuevo diseño tipo listado (alineado al modal-listado-productos) --}}
    <div 
        {{ $attributes->merge(['class' => 'col-12']) }}
        onclick="navegarAProducto(event, '{{ route('detalleproducto', $producto->id) }}')"
        style="cursor: pointer;"
    >
        <div 
            class="producto-item d-flex align-items-center mb-1 position-relative" 
            style="padding: 10px; border-radius: 12px; transition: background 0.2s ease;"
        >
            {{-- Imagen circular del producto --}}
            <div class="flex-shrink-0 me-3">
                <img 
                    src="{{ $producto->pathAttachment() }}" 
                    alt="{{ $producto->nombre() }}"
                    onerror='this.src="{{ GlobalHelper::getValorAtributoSetting("bg_default") }}"'
                    class="rounded-circle shadow-sm"
                    style="width: 55px; height: 55px; object-fit: cover; border: 2px solid rgba(255,255,255,0.2);">
            </div>

            {{-- Información del producto --}}
            <div class="flex-grow-1" style="min-width: 0;">
                <h3 class="color-theme mb-0 font-16 font-700" style="line-height: 1.2;">
                    {{ Str::limit($producto->nombre(), 60) }}
                </h3>

                @php
                    $detalleProducto = $producto->descripcion ?? $producto->detalle ?? null;
                @endphp

                @if(!empty($detalleProducto))
                    <p class="opacity-50 line-height-s font-11 mb-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $detalleProducto }}
                    </p>
                @endif

                <div class="d-flex flex-wrap gap-1">
                    @if($producto->tag->isNotEmpty())
                        @foreach ($producto->tag as $tag_individual)
                            <span class="badge font-9 px-2 py-1" style="background: rgba(0,0,0,0.06); border-radius: 20px;">
                                {{ $tag_individual->nombre }}
                            </span>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Precio y acciones --}}
            <div class="d-flex flex-column align-items-end ms-2 flex-shrink-0">
                @php
                    $tieneDescuento = ($producto->descuento < $producto->precio) && ($producto->descuento > 0);
                @endphp

                @if($tieneDescuento)
                    <div class="text-end">
                        <p class="font-11 mb-0 opacity-50">
                            <del>Bs. {{ $producto->precio }}</del>
                        </p>
                        <h4 class="color-theme font-700 mb-0 font-16">
                            Bs. {{ $producto->descuento }}
                        </h4>
                    </div>
                @else
                    <h4 class="color-theme font-700 mb-0 font-16">
                        Bs. {{ $producto->precio }}
                    </h4>
                @endif

                <div class="d-flex mt-1">
                    {{-- Botón copiar link --}}
                    <button ruta="{{ route('detalleproducto', $producto->id) }}" 
                            class="btn-accion-circular icon icon-xs rounded-circle shadow-l me-1 bg-blue-dark copiarLink"
                            title="Copiar link">
                        <i class="fa fa-link"></i>
                    </button>

                    {{-- Acción de carrito / agotado --}}
                    @if ($producto->tiene_stock == false)
                        <button class="btn-accion-circular icon icon-xs rounded-circle shadow-l bg-gray-dark btn-accion-disabled" disabled title="Agotado">
                            <i class="fa fa-ban"></i>
                        </button>
                    @else
                        <button
                            class="{{ $producto->tiene_adicionales ? 'menu-adicionales-btn' : 'agregar-unidad' }} add-disabler btn-accion-circular icon icon-xs rounded-circle shadow-l bg-green-dark btn-accion-add"
                            data-producto-id="{{ $producto->id }}"
                            data-producto-nombre="{{ $producto->nombre }}"
                            title="Añadir al carrito"
                        >
                            <i class="fa fa-cart-plus"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif