<div class="perm">
    <style>
        /* Tokens tomados del tema admin (style.css): tinta #194039, primario #0E8A74, fondo #fbfbfb */
        .perm {
            --p-ink: #194039;
            --p-muted: #56706b;
            --p-surface: #ffffff;
            --p-border: #e3e9e7;
            --p-rule: #eef2f1;
            --p-track: #eef3f1;
            --p-active: #e6f1ee;
            --p-accent: #0E8A74;
            --p-accent-ink: #0a6b5a;
            --p-warn-bg: #fffbeb;
            --p-warn-ink: #92400e;
            --p-gap: 16px;
            color: var(--p-ink);
        }
        .perm-head { margin-bottom: 20px; }
        .perm-head h1 { font-size: 24px; font-weight: 600; margin: 0 0 4px; color: var(--p-ink); }
        .perm-head p { font-size: 14px; margin: 0; color: var(--p-muted); max-width: 70ch; }

        .perm-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 300px;
            gap: var(--p-gap);
            align-items: start;
        }
        .perm-roles { grid-column: 1 / -1; }
        .perm-panel {
            background: var(--p-surface);
            border: 1px solid var(--p-border);
            border-radius: 12px;
        }
        .perm-panel-head { padding: 16px 20px 12px; }
        .perm-panel-head h2 { font-size: 16px; font-weight: 600; margin: 0; color: var(--p-ink); }
        .perm-panel-head p { font-size: 13px; margin: 4px 0 0; color: var(--p-muted); }

        /* Roles */
        .perm-roles ul { list-style: none; margin: 0; padding: 8px; display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 8px; }
        .perm-roles li { display: flex; }
        .perm-roles li + li { border-left: 1px solid var(--p-rule); padding-left: 8px; }
        .perm-role {
            display: block; width: 100%; text-align: left;
            background: transparent; border: 0; border-radius: 8px;
            padding: 10px 12px; color: var(--p-ink); cursor: pointer;
        }
        .perm-role:hover { background: var(--p-rule); }
        .perm-role:focus-visible { outline: 2px solid var(--p-accent); outline-offset: 1px; }
        .perm-role[aria-current="true"] { background: var(--p-active); }
        .perm-role[aria-disabled="true"] { cursor: default; }
        .perm-role[aria-disabled="true"]:hover { background: transparent; }
        .perm-role-name { display: flex; align-items: center; gap: 6px; font-size: 15px; font-weight: 500; text-transform: capitalize; }
        .perm-role-name .fa-lock { font-size: 12px; color: var(--p-muted); }
        .perm-role-desc { display: block; font-size: 13px; color: var(--p-muted); margin-top: 1px; }
        .perm-role-meta { display: block; font-size: 12px; color: var(--p-muted); margin-top: 4px; font-variant-numeric: tabular-nums; }
        .perm-role[aria-current="true"] .perm-role-meta { color: var(--p-accent-ink); }

        /* Editor */
        .perm-editor-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; padding: 16px 20px; border-bottom: 1px solid var(--p-border); }
        .perm-editor-head h2 { font-size: 18px; font-weight: 600; margin: 0; color: var(--p-ink); }
        .perm-editor-head h2 span { text-transform: capitalize; }
        .perm-editor-head p { font-size: 14px; margin: 4px 0 0; color: var(--p-muted); }
        .perm-stamp { flex: none; font-size: 12px; color: var(--p-muted); white-space: nowrap; padding-top: 4px; }
        .perm-stamp.is-dirty { color: var(--p-warn-ink); }

        .perm-row { padding: 14px 20px; border-bottom: 1px solid var(--p-rule); }
        .perm-row:last-child { border-bottom: 0; }
        .perm-row-main { display: grid; grid-template-columns: 24px minmax(0, 1fr) auto; gap: 12px; align-items: center; }
        .perm-ico { font-size: 18px; color: var(--p-muted); text-align: center; }
        .perm-row.is-todo .perm-ico, .perm-row.is-algunos .perm-ico { color: var(--p-accent); }
        .perm-row-text h3 { font-size: 15px; font-weight: 500; margin: 0; color: var(--p-ink); }
        .perm-row-text p { font-size: 13px; margin: 2px 0 0; color: var(--p-muted); }

        /* Selector de 3 estados */
        .perm-seg { display: inline-flex; background: var(--p-track); border-radius: 8px; padding: 3px; gap: 2px; }
        .perm-seg input { position: absolute; opacity: 0; pointer-events: none; }
        .perm-seg label {
            margin: 0; padding: 6px 12px; border-radius: 6px; font-size: 13px; line-height: 20px;
            color: var(--p-muted); cursor: pointer; white-space: nowrap; border: 1px solid transparent;
        }
        .perm-seg label:hover { color: var(--p-ink); }
        .perm-seg input:checked + label { background: var(--p-surface); border-color: var(--p-border); color: var(--p-ink); font-weight: 500; }
        .perm-seg input[value="ninguno"]:checked + label { color: var(--p-muted); }
        .perm-seg input[value="todo"]:checked + label { background: var(--p-accent-ink); border-color: var(--p-accent-ink); color: #fff; }
        .perm-seg input[value="algunos"]:checked + label { border-color: var(--p-accent); color: var(--p-accent-ink); }
        .perm-seg input:focus-visible + label { outline: 2px solid var(--p-accent); outline-offset: 1px; }

        .perm-detail { margin: 10px 0 0 36px; font-size: 13px; color: var(--p-muted); }
        .perm-items { margin: 12px 0 0 36px; padding: 12px 14px; background: #f8faf9; border-radius: 8px; }
        .perm-items-head { display: flex; justify-content: space-between; align-items: center; gap: 8px; margin-bottom: 8px; font-size: 13px; color: var(--p-muted); font-variant-numeric: tabular-nums; }
        .perm-items-head button { background: none; border: 0; padding: 0 0 0 12px; font-size: 13px; color: var(--p-accent-ink); cursor: pointer; }
        .perm-items-head button:hover { text-decoration: underline; }
        .perm-items-list { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 6px 16px; }
        .perm-check { display: flex; align-items: center; gap: 8px; font-size: 14px; color: var(--p-ink); margin: 0; cursor: pointer; min-height: 28px; }
        .perm-check input { width: 16px; height: 16px; accent-color: var(--p-accent); flex: none; margin: 0; }
        .perm-warn { margin-top: 10px; padding: 8px 12px; font-size: 13px; background: var(--p-warn-bg); border: 1px solid #fde68a; color: var(--p-warn-ink); border-radius: 6px; }

        /* Vista previa del menú */
        .perm-preview { position: sticky; top: 110px; }
        .perm-menu { list-style: none; margin: 0; padding: 0 20px 16px; }
        .perm-menu > li { padding: 8px 0; border-top: 1px solid var(--p-rule); }
        .perm-menu > li:first-child { border-top: 0; }
        .perm-menu-title { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 500; color: var(--p-ink); }
        .perm-menu-title i { width: 18px; text-align: center; color: var(--p-accent); font-size: 15px; }
        .perm-menu ul { list-style: none; margin: 4px 0 0 28px; padding: 0; }
        .perm-menu ul li { font-size: 13px; color: var(--p-muted); line-height: 22px; }
        .perm-menu-empty { margin: 0 20px 16px; padding: 12px; border: 1px dashed var(--p-border); border-radius: 8px; font-size: 13px; color: var(--p-muted); }
        .perm-extra { margin: 0 20px 16px; padding-top: 12px; border-top: 1px solid var(--p-border); font-size: 13px; color: var(--p-muted); }
        .perm-extra strong { color: var(--p-ink); font-weight: 500; }

        /* Barra de guardado */
        .perm-savebar {
            position: sticky; bottom: 16px; z-index: 5; margin-top: var(--p-gap);
            display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;
            background: var(--p-ink); color: #fff; border-radius: 12px; padding: 12px 16px 12px 20px;
        }
        .perm-savebar p { margin: 0; font-size: 14px; }
        .perm-savebar p span { text-transform: capitalize; }
        .perm-savebar .btn-link { color: #fff; text-decoration: none; font-size: 14px; }
        .perm-savebar .btn-link:hover { text-decoration: underline; }
        .perm-save { background: #fff; color: var(--p-ink); border: 0; border-radius: 8px; padding: 8px 16px; font-size: 14px; font-weight: 500; }
        .perm-save:hover { background: var(--p-active); }
        .perm-save:focus-visible { outline: 2px solid #fff; outline-offset: 2px; }
        .perm-save[disabled] { opacity: .7; }

        @media (max-width: 1199px) {
            .perm-grid { grid-template-columns: minmax(0, 1fr); }
            .perm-preview { position: static; }
            .perm-roles ul { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .perm-roles li + li { border-left: 0; padding-left: 0; }
        }
        @media (max-width: 767px) {
            .perm-roles ul { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 640px) {
            .perm-row-main { grid-template-columns: 24px minmax(0, 1fr); }
            .perm-seg { grid-column: 1 / -1; display: flex; }
            .perm-seg label { flex: 1; text-align: center; padding: 10px 6px; }
            .perm-detail, .perm-items { margin-left: 0; }
            .perm-items-list { grid-template-columns: minmax(0, 1fr); }
            .perm-check { min-height: 36px; }
            .perm-editor-head { flex-direction: column; }
        }
    </style>

    @php
        $totalModulos = count($modulos);
        $conAcceso = collect($modulos)->keys()->filter(fn($c) => ($modo[$c] ?? 'ninguno') === 'todo' || (($modo[$c] ?? '') === 'algunos' && count($seleccion[$c] ?? []) > 0))->count();
        $opciones = ['ninguno' => 'Sin acceso', 'algunos' => 'Solo algunas', 'todo' => 'Todo'];
    @endphp

    <header class="perm-head">
        <h1>Permisos</h1>
        <p>Qué secciones del panel de administración puede abrir cada rol. Lo que no esté permitido no aparece en su menú y tampoco se abre escribiendo la dirección. «Todo» incluye también las opciones que se agreguen a esa sección en el futuro.</p>
    </header>

    <div class="perm-grid">
        {{-- Roles --}}
        <nav class="perm-panel perm-roles" aria-label="Roles">
            <h2 class="visually-hidden">Roles</h2>
            <ul>
                @foreach ($roles as $rol)
                    <li>
                        <button type="button" class="perm-role"
                            @if ($rol->bloqueado) aria-disabled="true" @else wire:click="seleccionarRol({{ $rol->id }})" @endif
                            @if ($hayCambios && !$rol->bloqueado && $rol->id != $roleId) onclick="if(!confirm('Tienes cambios sin guardar en este rol. ¿Descartarlos?')){event.stopImmediatePropagation();}" @endif
                            aria-current="{{ $rol->id == $roleId ? 'true' : 'false' }}">
                            <span class="perm-role-name">
                                {{ $rol->nombre }}
                                @if ($rol->bloqueado) <i class="fa fa-lock" aria-hidden="true"></i> @endif
                            </span>
                            @if ($rol->descripcion)
                                <span class="perm-role-desc">{{ $rol->descripcion }}</span>
                            @endif
                            <span class="perm-role-meta">
                                {{ $rol->personas }} {{ $rol->personas == 1 ? 'persona' : 'personas' }} ·
                                @if ($rol->id == \App\Models\Role::ADMIN)
                                    ve todo, no se limita
                                @elseif ($rol->id == \App\Models\Role::CLIENTE)
                                    no entra al panel
                                @else
                                    {{ $rol->modulosConAcceso }} de {{ $totalModulos }} secciones
                                @endif
                            </span>
                        </button>
                    </li>
                @endforeach
            </ul>
        </nav>

        {{-- Editor --}}
        <section class="perm-panel perm-editor" aria-labelledby="perm-editor-title">
            @if ($rolActual)
                <div class="perm-editor-head">
                    <div>
                        <h2 id="perm-editor-title">Qué puede abrir <span>{{ $rolActual->nombre }}</span></h2>
                        <p>{{ $conAcceso }} de {{ $totalModulos }} secciones con acceso · {{ $rolActual->personas }} {{ $rolActual->personas == 1 ? 'persona tiene' : 'personas tienen' }} este rol</p>
                    </div>
                    <span class="perm-stamp {{ $hayCambios ? 'is-dirty' : '' }}">
                        {{ $hayCambios ? 'Cambios sin guardar' : 'Todo guardado' }}
                    </span>
                </div>

                @foreach ($modulos as $clave => $modulo)
                    @php
                        $estado = $modo[$clave] ?? 'ninguno';
                        $items = array_unique($modulo['items']);
                        $marcadas = count(array_intersect($items, $seleccion[$clave] ?? []));
                    @endphp
                    <div class="perm-row is-{{ $estado }}" wire:key="row-{{ $roleId }}-{{ $clave }}">
                        <div class="perm-row-main">
                            <i class="{{ $modulo['icono'] ?? 'fa fa-circle-o' }} perm-ico" aria-hidden="true"></i>
                            <div class="perm-row-text">
                                <h3>{{ $modulo['titulo'] }}</h3>
                                @if (!empty($modulo['descripcion']))
                                    <p>{{ $modulo['descripcion'] }}</p>
                                @endif
                            </div>
                            <div class="perm-seg" role="radiogroup" aria-label="Acceso a {{ $modulo['titulo'] }}">
                                @foreach ($opciones as $valor => $texto)
                                    <input type="radio" id="modo-{{ $clave }}-{{ $valor }}" name="modo-{{ $clave }}"
                                        value="{{ $valor }}" wire:model="modo.{{ $clave }}" @if ($estado === $valor) checked @endif>
                                    <label for="modo-{{ $clave }}-{{ $valor }}">{{ $texto }}</label>
                                @endforeach
                            </div>
                        </div>

                        @if ($estado === 'todo' && count($items) > 1)
                            @php $nombres = array_keys($items); @endphp
                            <p class="perm-detail">
                                Incluye {{ count($nombres) > 4 ? implode(', ', array_slice($nombres, 0, 3)) . ' y ' . (count($nombres) - 3) . ' más' : implode(', ', array_slice($nombres, 0, -1)) . ' y ' . end($nombres) }}.
                            </p>
                        @elseif ($estado === 'algunos')
                            <div class="perm-items">
                                <div class="perm-items-head">
                                    <span>{{ $marcadas }} de {{ count($items) }} opciones</span>
                                    <span>
                                        <button type="button" wire:click="marcarTodas('{{ $clave }}', true)">Marcar todas</button>
                                        <button type="button" wire:click="marcarTodas('{{ $clave }}', false)">Quitar todas</button>
                                    </span>
                                </div>
                                <div class="perm-items-list">
                                    @foreach ($items as $subtitulo => $ruta)
                                        <label class="perm-check" for="item-{{ $clave }}-{{ $loop->index }}">
                                            <input type="checkbox" id="item-{{ $clave }}-{{ $loop->index }}"
                                                value="{{ $ruta }}" wire:model="seleccion.{{ $clave }}" @if (in_array($ruta, $seleccion[$clave] ?? [])) checked @endif>
                                            {{ $subtitulo }}
                                        </label>
                                    @endforeach
                                </div>
                                @if ($marcadas === 0)
                                    <div class="perm-warn">Marca al menos una opción. Si guardas así, esta sección quedará sin acceso.</div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="perm-editor-head">
                    <div>
                        <h2 id="perm-editor-title">No hay roles para configurar</h2>
                        <p>Crea un rol en Usuarios › Roles para asignarle permisos.</p>
                    </div>
                </div>
            @endif
        </section>

        {{-- Vista previa --}}
        @if ($rolActual)
            <aside class="perm-panel perm-preview" aria-labelledby="perm-preview-title">
                <div class="perm-panel-head">
                    <h2 id="perm-preview-title">Así verá el menú</h2>
                    <p>Menú lateral de una persona con el rol <span style="text-transform: capitalize">{{ $rolActual->nombre }}</span>.</p>
                </div>
                @php
                    $menu = [];
                    foreach ($modulos as $clave => $modulo) {
                        if ($clave === 'caja_diaria') continue;
                        $estado = $modo[$clave] ?? 'ninguno';
                        $visibles = $estado === 'todo'
                            ? array_keys($modulo['items'])
                            : ($estado === 'algunos' ? array_keys(array_intersect($modulo['items'], $seleccion[$clave] ?? [])) : []);
                        if (count($visibles)) $menu[] = ['modulo' => $modulo, 'items' => $visibles];
                    }
                    $cajaHeader = ($modo['caja_diaria'] ?? 'ninguno') === 'todo'
                        || (($modo['caja_diaria'] ?? '') === 'algunos' && count($seleccion['caja_diaria'] ?? []));
                @endphp
                @if (count($menu))
                    <ul class="perm-menu">
                        @foreach ($menu as $entrada)
                            <li>
                                <span class="perm-menu-title">
                                    <i class="{{ $entrada['modulo']['icono'] ?? 'fa fa-circle-o' }}" aria-hidden="true"></i>
                                    {{ $entrada['modulo']['titulo'] }}
                                </span>
                                @if (count($entrada['items']) > 1 || $entrada['items'][0] !== $entrada['modulo']['titulo'])
                                    <ul>
                                        @foreach ($entrada['items'] as $subtitulo)
                                            <li>{{ $subtitulo }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="perm-menu-empty">Su menú quedará vacío. Al entrar al panel volverá a su perfil de la tienda.</p>
                @endif
                <p class="perm-extra">
                    Caja diaria en el menú de perfil:
                    <strong>{{ $cajaHeader ? 'sí' : 'no' }}</strong>
                </p>
            </aside>
        @endif
    </div>

    @if ($hayCambios && $rolActual)
        <div class="perm-savebar" role="region" aria-label="Cambios sin guardar">
            <p>Tienes cambios sin guardar en <span>{{ $rolActual->nombre }}</span>.</p>
            <div>
                <button type="button" class="btn btn-link" wire:click="descartar">Descartar</button>
                <button type="button" class="perm-save" wire:click="guardar" wire:loading.attr="disabled" wire:target="guardar">
                    <span wire:loading.remove wire:target="guardar">Guardar cambios</span>
                    <span wire:loading wire:target="guardar">Guardando…</span>
                </button>
            </div>
        </div>
    @endif
</div>
