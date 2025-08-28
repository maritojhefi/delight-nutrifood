
<div id="products-search-box" class="bg-theme rounded-xl p-2 mx-3">
    <div class="d-flex flex-row gap-2 justify-content-start">
        {{-- Icono lupa --}}
        <i data-lucide="search" class="lucide-icon color-theme"></i>
        {{-- Input Buscador --}}
        <input 
            type="text" class="border-0 text-black color-black w-100"
            id="products-search-input" data-search=""
            data-search-type = "{{$tipo ?? ''}}"
            placeholder="Buscar productos..." />
    </div>
</div>
{{-- Contenedor resultados de busqueda --}}
<div 
    class="card card-style bg-theme p-2" 
    style="display: none;">
        {{-- Listado de resultados --}}
        <ol id="products-search-results" class="d-flex flex-column gap-2 list-group list-group-flush">
            {{-- Resultados de busqueda se cargan aqui --}}
            {{-- <li id="example-search-result1">
                <a class="d-flex flex-row gap-1 justify-content-between align-items-center ">
                    <img 
                    class="rounded-sm col-2" style="width: 4rem; height: 4rem; object-fit: cover;"
                    src="{{GlobalHelper::getValorAtributoSetting('bg_default')}}" alt="Producto">
                    <div class="col-6">
                        <h6>{{Str::limit("Producto ofertado",35)}}</h6>
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <small class="text-secondary" style="line-height: 1">{{Str::limit("Panes Integrales",24)}}</small>
                            <div class="d-flex flex-row align-items-center gap-1">
                            </div>
                        </div>
                    </div>
                    <div class="col-3 d-flex flex-column align-items-end">
                        <p class="mb-0 color-highlight font-900">Bs. 21.00</p>
                        <del class="text-secondary" style="font-size: 80%">Bs. 60.00</del>
                    </div>
                </a>
            </li>
            <li id="example-search-result2">
                <a class="d-flex flex-row gap-1 justify-content-between align-items-center ">
                    <img 
                    class="rounded-sm col-2" style="width: 4rem; height: 4rem; object-fit: cover;"
                    src="{{GlobalHelper::getValorAtributoSetting('bg_default')}}" alt="Producto">
                    <div class="col-6">
                        <h6>{{Str::limit("Nombre bastante largo de un producto, describiendo mas de lo necesario",35)}}</h6>
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <small class="text-secondary" style="line-height: 1">{{Str::limit("Panes Integrales",24)}}</small>
                            <div class="d-flex flex-row align-items-center gap-1">
                            </div>
                        </div>
                    </div>
                    <div class="col-3 d-flex flex-column align-items-end">
                        <p class="mb-0 color-highlight font-900">Bs. 75.00</p>
                    </div>
                </a>
            </li>
            <li id="example-search-result3">
                <a class="d-flex flex-row gap-1 justify-content-between align-items-center ">
                    <img 
                    class="rounded-sm col-2" style="width: 4rem; height: 4rem; object-fit: cover;"
                    src="{{GlobalHelper::getValorAtributoSetting('bg_default')}}" alt="Producto">
                    <div class="col-6">
                        <h6>{{Str::limit("Producto sin tags",35)}}</h6>
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <small class="text-secondary" style="line-height: 1">{{Str::limit("Helados saludables, veganos y libres de azúcares",24)}}</small>
                            <div class="d-flex flex-row align-items-center gap-1">
                            </div>
                        </div>
                    </div>
                    <div class="col-3 d-flex flex-column align-items-end">
                        <p class="mb-0 color-highlight font-900">Bs. 120.00</p>
                    </div>
                </a>
            </li>
            <li id="example-search-result4">
                <a class="d-flex flex-row gap-1 justify-content-between align-items-center ">
                    <img 
                    class="rounded-sm col-2" style="width: 4rem; height: 4rem; object-fit: cover;"
                    src="{{GlobalHelper::getValorAtributoSetting('bg_default')}}" alt="Producto">
                    <div class="col-6">
                        <h6>{{Str::limit("Producto con tags",35)}}</h6>
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <small class="text-secondary" style="line-height: 1">{{Str::limit("Helados saludables, veganos y libres de azúcares",24)}}</small>
                            <div class="d-flex flex-row align-items-center gap-1">
                                <i data-lucide="milk-off" class="lucide-icon color-highlight" style="width: 1rem; height: 1rem;"></i>
                                <i data-lucide="wheat-off" class="lucide-icon color-highlight" style="width: 1rem; height: 1rem;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 d-flex flex-column align-items-end">
                        <p class="mb-0 color-highlight font-900">Bs. 1000.00</p>
                    </div>
                </a>
            </li>
            <li id="example-search-result5">
                <a class="d-flex flex-row gap-1 justify-content-between align-items-center ">
                    <img 
                    class="rounded-sm col-2" style="width: 4rem; height: 4rem; object-fit: cover;"
                    src="{{GlobalHelper::getValorAtributoSetting('bg_default')}}" alt="Producto">
                    <div class="col-6">
                        <h6>{{Str::limit("Producto tags y subcategoria larga",35)}}</h6>
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <small class="text-secondary" style="line-height: 1">{{Str::limit("Helados saludables, veganos y libres de azúcares",24)}}</small>
                            <div class="d-flex flex-row align-items-center gap-1">
                                <i data-lucide="vegan" class="lucide-icon color-highlight" style="width: 1rem; height: 1rem;"></i>
                                <i data-lucide="milk-off" class="lucide-icon color-highlight" style="width: 1rem; height: 1rem;"></i>
                                <i data-lucide="wheat-off" class="lucide-icon color-highlight" style="width: 1rem; height: 1rem;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 d-flex flex-column align-items-end">
                        <p class="mb-0 color-highlight font-900">Bs. 1000.00</p>
                        <del class="text-secondary" style="font-size: 80%">Bs. 1200.00</del>
                    </div>
                </a>
            </li> --}}
        </ol>
</div>
@push('scripts')
{{-- SCRIPT PARA LA BUSQUEDA DE PRODUCTOS  --}}
<script>
    document.addEventListener('DOMContentLoaded', async function () {
        let debounceTimer;
        let lastQuery = '';

        const ignoredKeys = new Set([
            'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown',
            'Shift', 'Control', 'Alt', 'Escape',
            'Meta', 'CapsLock', 'Tab'
        ]);

        const searchInput = document.getElementById('products-search-input');
        const searchType = searchInput.dataset.searchType;
        const resultsContainer = document.getElementById('products-search-results');

        searchInput.addEventListener('keyup', async function(e) {
            const key = e.key;

            if (ignoredKeys.has(key)) {
                return; // Ignorar teclas no relevantes
            }

            clearTimeout(debounceTimer);

            const query = searchInput.value.replace(/\s+/g, ' ').trim();

            if (query === lastQuery) {
                return; // No hacer nada si la consulta no ha cambiado
            }

            if (query.length <= 2) {
                if (query.length === 0) {
                    cleanSearchResults();
                    hideSearchResults();
                    lastQuery = '';
                }
                return; // No buscar si la consulta es demasiado corta
            }

            debounceTimer = setTimeout( async () => {
                lastQuery = query;
                try {
                    const response = await ProductoService.getSearchedProducts(searchType,query);
                    console.log("query value when sent:", query);
                    renderSearchResults(response);

                } catch (error) {
                    console.error('Error fetching search results:', error);
                    resultsContainer.innerHTML += `<li class="text-danger">Error al realizar la busqueda</li>`;
                    throw error;
                } finally {
                    console.log('Search attempt finished.');
                }
            }, 500);
        });

        // Limpiar los resultados de busqueda
        const cleanSearchResults = () => {
            console.log("cleaning search results");
            resultsContainer.innerHTML = '';
        };

        // Ocultar el contenedor de resultados
        const hideSearchResults = () => {
            resultsContainer.parentElement.style.display = 'none';
        };

        // Renderizar los resultados obtenidos
        const renderSearchResults = (results) => {
            resultsContainer.parentElement.style.display = 'block';

            // En caso de cero coincidencias
            if (results.length === 0) {
                resultsContainer.innerHTML = '<li>No se encontraron productos que coincidan con la busqueda</li>';
                return;
            }

            // Construir el HTML de todos los resultados y luego insertarlo de una vez
            const htmlParts = results.map(producto => {
                return `
                    <li id="example-search-result-${producto.id}">
                        <a href="${producto.url}" class="d-flex flex-row gap-1 justify-content-between align-items-center">
                            <img 
                                class="rounded-sm col-2" 
                                style="width: 4rem; height: 4rem; object-fit: cover;"
                                src="${producto.url_imagen}"
                                alt="${producto.nombre || 'Producto'}"
                                loading="lazy">
                            <div class="col-6">
                                <h6>
                                    ${limitString(producto.nombre, 35)}
                                </h6>
                                <div class="d-flex flex-row align-items-center justify-content-between">
                                    <small class="text-secondary" style="line-height: 1">
                                        ${limitString(producto.subcategoria, 24)}
                                    </small>
                                    <div class="d-flex flex-row align-items-center gap-1">
                                        ${renderTags(producto.tags)}
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 d-flex flex-column align-items-end">
                                ${renderPrice(producto)}
                            </div>
                        </a>
                    </li>
                `;
            });
            
            // Actualizar el contenido del contenedor
            resultsContainer.innerHTML = htmlParts.join('');
            //Reinicializar los iconos de lucide
            reinitializeLucideIcons();
        };

        // Helper para cortar strings
        const limitString = (text, limit) => {
            if (!text) return "";
            return text.length > limit ? text.substring(0, limit) + "..." : text;
        };

        // Renderizado de Tags pertenecientes al producto
        const renderTags = (tags) => {
            if (!tags || !Array.isArray(tags)) return "";
            
            return tags.map(tag => {
                return `<i data-lucide="${tag.icono}" class="lucide-icon color-highlight" style="width: 1rem; height: 1rem;"></i>`;
            }).join('');
        };

        // Renderizado del precio de los productos
        const renderPrice = (producto) => {
            // Condicionante de descuentos
            if (producto.tiene_descuento) {
                return `
                    <p class="mb-0 color-highlight font-900">Bs. ${producto.precioFinal}</p>
                    <del class="text-secondary" style="font-size: 80%">Bs. ${producto.precioOriginal}</del>
                `;
            } else {
                return `<p class="mb-0 color-highlight font-900">Bs. ${producto.precioFinal}</p>`;
            }
        };
    });
</script>
@endpush