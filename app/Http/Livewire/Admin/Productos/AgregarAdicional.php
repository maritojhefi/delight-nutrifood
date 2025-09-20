<?php

namespace App\Http\Livewire\Admin\Productos;

use App\Models\Grupo;
use Livewire\Component;
use App\Models\Adicionale;
use App\Models\Subcategoria;
use App\Models\GrupoAdicionales;
use Illuminate\Support\Facades\DB;

class AgregarAdicional extends Component
{
    public $subcategoria;
    public $search;
    public $searchSub;
    public $nombreGrupo, $maximoGrupo;

    public $mostrarSubcategoria = true;

    // Propiedades para grupos_adicionales
    public $nombreGrupoAdicional,
        $esObligatorio = false,
        $maximoSeleccionable = 1;

    // Propiedades para editar grupos
    public $grupoEditando = null;
    public $nombreGrupoEditando = '';
    public $esObligatorioEditando = false;
    public $maximoSeleccionableEditando = 1;

    public function seleccionado(Subcategoria $sub)
    {
        $this->mostrarSubcategoria = false;
        $this->subcategoria = $sub;
        $this->emit('change-focus-other-field');
    }

    public function mostrarSubcategoria()
    {
        $this->mostrarSubcategoria = true;
        $this->subcategoria = null;
        $this->emit('change-focus-other-field');
    }

    public function agregar(Adicionale $ad)
    {
        $sub = Subcategoria::find($this->subcategoria->id);
        $registro = DB::table('adicionale_subcategoria')->where('subcategoria_id', $sub->id)->where('adicionale_id', $ad->id)->get();
        //dd($registro);
        if ($registro->count() != 0) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'Ya se encuentra agregado!',
            ]);
        } else {
            $sub->adicionales()->attach($ad->id);
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'Se agrego satisfactoriamente!',
            ]);
            $this->subcategoria = $sub;
        }
    }

    public function eliminar(Adicionale $ad)
    {
        $sub = Subcategoria::find($this->subcategoria->id);
        $sub->adicionales()->detach($ad->id);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'warning',
            'message' => 'Se elimino el adicional en esta categoria!',
        ]);
        $this->subcategoria = $sub;
    }
    public function submit()
    {
        $this->validate([
            'nombreGrupo' => 'required|min:4',
            'maximoGrupo' => 'required|integer',
        ]);
        // dd($this->nombreGrupo, $this->maximoGrupo);

        Grupo::create([
            'nombre_grupo' => $this->nombreGrupo,
            'max' => $this->maximoGrupo,
        ]);
        $this->reset('nombreGrupo', 'maximoGrupo');
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Se creo un nuevo grupo!',
        ]);
    }
    public function anadirGrupo(Adicionale $ad, $grupo)
    {
        $sub = Subcategoria::find($this->subcategoria->id);
        $sub->adicionales()->sync([$ad->id => ['id_grupo' => $grupo]]);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Se agrego correctamente al grupo!',
        ]);
        $this->subcategoria = $sub;
    }

    // Funciones para manejar grupos_adicionales
    public function crearGrupoAdicional()
    {
        $this->validate([
            'nombreGrupoAdicional' => 'required|min:3',
            'maximoSeleccionable' => 'required|integer|min:1',
            'esObligatorio' => 'boolean',
        ]);

        if ($this->grupoEditando) {
            // Modo edición
            $grupo = GrupoAdicionales::find($this->grupoEditando);
            if ($grupo) {
                $grupo->update([
                    'nombre_grupo' => $this->nombreGrupoAdicional,
                    'es_obligatorio' => $this->esObligatorio,
                    'maximo_seleccionable' => $this->maximoSeleccionable,
                ]);

                $this->dispatchBrowserEvent('alert', [
                    'type' => 'success',
                    'message' => 'Grupo actualizado exitosamente!',
                ]);

                // Refrescar la subcategoría para mostrar los cambios
                $this->subcategoria = Subcategoria::find($this->subcategoria->id);
            }
        } else {
            // Modo creación - Vincular automáticamente a la subcategoría actual
            GrupoAdicionales::create([
                'nombre_grupo' => $this->nombreGrupoAdicional,
                'es_obligatorio' => $this->esObligatorio,
                'maximo_seleccionable' => $this->maximoSeleccionable,
                'subcategoria_id' => $this->subcategoria->id,
            ]);

            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'Grupo de adicionales creado exitosamente!',
            ]);
        }

        $this->reset(['nombreGrupoAdicional', 'esObligatorio', 'maximoSeleccionable', 'grupoEditando']);
        $this->esObligatorio = false;
        $this->maximoSeleccionable = 1;

        // Refrescar la subcategoría para que aparezcan los nuevos grupos
        $this->subcategoria = Subcategoria::find($this->subcategoria->id);

        $this->emit('cerrarModalCrearEditarGrupo');
        // Emitir evento para reinicializar SortableJS
        $this->emit('reinicializar-sortable');
    }

    public function actualizarGrupoAdicional($adicionaleId, $grupoId)
    {
        DB::table('adicionale_subcategoria')
            ->where('subcategoria_id', $this->subcategoria->id)
            ->where('adicionale_id', $adicionaleId)
            ->update(['grupo_adicionales_id' => $grupoId]);

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Adicional asignado al grupo correctamente!',
        ]);

        // Refrescar la subcategoría para mostrar los cambios
        $this->subcategoria = Subcategoria::find($this->subcategoria->id);
    }

    public function quitarDeGrupo($adicionaleId)
    {
        DB::table('adicionale_subcategoria')
            ->where('subcategoria_id', $this->subcategoria->id)
            ->where('adicionale_id', $adicionaleId)
            ->update(['grupo_adicionales_id' => null]);

        $this->dispatchBrowserEvent('alert', [
            'type' => 'warning',
            'message' => 'Adicional removido del grupo!',
        ]);

        // Refrescar la subcategoría para mostrar los cambios
        $this->subcategoria = Subcategoria::find($this->subcategoria->id);
    }

    // Funciones para editar grupos en el modal
    public function iniciarEdicionGrupo($grupoId)
    {
        // Verificar que el grupo pertenezca a la subcategoría actual
        $grupo = GrupoAdicionales::where('id', $grupoId)
            ->where('subcategoria_id', $this->subcategoria->id)
            ->first();

        if ($grupo) {
            $this->grupoEditando = $grupoId;
            $this->nombreGrupoAdicional = $grupo->nombre_grupo;
            $this->esObligatorio = $grupo->es_obligatorio;
            $this->maximoSeleccionable = $grupo->maximo_seleccionable;
        } else {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'El grupo no pertenece a esta subcategoría.',
            ]);
        }
    }

    public function cancelarEdicionGrupo()
    {
        $this->grupoEditando = null;
        $this->reset(['nombreGrupoAdicional', 'esObligatorio', 'maximoSeleccionable']);
        $this->esObligatorio = false;
        $this->maximoSeleccionable = 1;
    }

    public function actualizarGrupo()
    {
        $this->validate([
            'nombreGrupoAdicional' => 'required|min:3',
            'maximoSeleccionable' => 'required|integer|min:1',
            'esObligatorio' => 'boolean',
        ]);

        $grupo = GrupoAdicionales::find($this->grupoEditando);
        if ($grupo) {
            $grupo->update([
                'nombre_grupo' => $this->nombreGrupoAdicional,
                'es_obligatorio' => $this->esObligatorio,
                'maximo_seleccionable' => $this->maximoSeleccionable,
            ]);

            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'Grupo actualizado exitosamente!',
            ]);

            $this->cancelarEdicionGrupo();
            $this->emit('reinicializar-sortable');
        }
    }

    // Función para eliminar grupo con validación
    public function eliminarGrupo($grupoId)
    {
        // Verificar que el grupo pertenezca a la subcategoría actual
        $grupo = GrupoAdicionales::where('id', $grupoId)
            ->where('subcategoria_id', $this->subcategoria->id)
            ->first();

        if (!$grupo) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'El grupo no pertenece a esta subcategoría.',
            ]);
            return;
        }

        // Verificar si el grupo tiene adicionales asignados
        $tieneAdicionales = DB::table('adicionale_subcategoria')
            ->where('grupo_adicionales_id', $grupoId)
            ->where('subcategoria_id', $this->subcategoria->id)
            ->exists();

        if ($tieneAdicionales) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'No se puede eliminar el grupo porque tiene adicionales asignados. Primero remueve todos los adicionales del grupo.',
            ]);
            return;
        }

        // Si no tiene adicionales, proceder con la eliminación
        $grupo->delete();

        // Refrescar la subcategoría para mostrar los cambios
        $this->subcategoria = Subcategoria::find($this->subcategoria->id);

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Grupo eliminado exitosamente!',
        ]);
        $this->emit('reinicializar-sortable');
    }

    // Función para confirmar eliminación
    public function confirmarEliminacionGrupo($grupoId)
    {
        // dd($grupoId);
        $this->emit('confirmar-eliminacion', [
            'grupoId' => $grupoId,
        ]);
    }

    public function render()
    {
        $adicionales = Adicionale::where('nombre', 'LIKE', '%' . $this->search . '%')
            ->take(5)
            ->get();
        $grupos = Grupo::all();

        // Solo obtener grupos de adicionales de la subcategoría actual
        if ($this->subcategoria) {
            $gruposAdicionales = $this->subcategoria->gruposAdicionalesDeSubcategoria;
        } else {
            $gruposAdicionales = collect();
        }

        $subcategorias = Subcategoria::where('nombre', 'LIKE', '%' . $this->searchSub . '%')->get();

        // Si hay una subcategoría seleccionada, obtener adicionales con información de grupos
        if ($this->subcategoria) {
            $adicionalesConGrupos = $this->subcategoria->adicionalesGrupo();
            // Obtener grupos que tienen al menos un adicional en esta subcategoría usando la relación directa
            $gruposConAdicionales = $this->subcategoria->gruposConAdicionalesEnSubcategoria;
        } else {
            $adicionalesConGrupos = collect();
            $gruposConAdicionales = collect();
        }
        return view('livewire.admin.productos.agregar-adicional', compact('subcategorias', 'adicionales', 'grupos', 'gruposAdicionales', 'adicionalesConGrupos', 'gruposConAdicionales'))
            ->extends('admin.master')
            ->section('content');
    }
}
