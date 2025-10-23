<?php

namespace App\Models;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Adicionale;
use App\Models\GrupoAdicionales;
use App\Helpers\GlobalHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Subcategoria extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'categoria_id', 'foto', 'interacciones'];
    
    // Constante para la ruta de fotos de subcategorías en S3
    const RUTA_FOTO = '/imagenes/subcategorias/';
    public function getNombreAttribute($value)
    {
        return ucfirst(strtolower($value));
    }
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
    public function horarios()
    {
        return $this->belongsToMany(Horario::class)->withTimestamps();
    }
    public function adicionales()
    {
        return $this->belongsToMany(Adicionale::class)->withTimestamps();
        // ->withPivot('grupo_adicionales_id')
        // ->withPivot('id_grupo');
    }
    public function adicionalesGrupo()
    {
        // Obtenemos la informacion de los adicionales y se les asigna tambien la informacion del grupo al que pertenecen
        return $this->adicionales()
            ->join('adicionale_subcategoria as pivot', function ($join) {
                $join->on('adicionales.id', '=', 'pivot.adicionale_id')->where('pivot.subcategoria_id', $this->id);
            })
            // Tomando tambien adicionales que no disponen de un grupo
            ->leftJoin('grupos_adicionales', 'pivot.grupo_adicionales_id', '=', 'grupos_adicionales.id')
            ->select(['adicionales.*', 'grupos_adicionales.id as grupo_id', 'grupos_adicionales.nombre_grupo', 'grupos_adicionales.es_obligatorio', 'grupos_adicionales.maximo_seleccionable'])
            ->get();
    }
    protected $appends = ['pathFoto'];

    public function rutaFoto()
    {
        if ($this->foto == null) {
            return GlobalHelper::getValorAtributoSetting('subcategoria_default');
        } else {
            // Usar el disco configurado para generar la URL correcta
            $disk = GlobalHelper::discoArchivos();
            if ($disk === 's3') {
                // Para S3, usar la URL completa
                $config = config('filesystems.disks.s3');
                $bucket = $config['bucket'] ?? '';
                $region = $config['region'] ?? 'us-east-1';
                $baseUrl = "https://{$bucket}.s3.{$region}.amazonaws.com";
                return $baseUrl . self::RUTA_FOTO . $this->foto;
            } else {
                // Para local, usar la ruta relativa
                return 'imagenes/subcategorias/' . $this->foto;
            }
        }
    }

    /**
     * Obtener la URL completa de la foto de la subcategoría
     */
    public function getPathFotoAttribute()
    {
        if ($this->foto == null) {
            return GlobalHelper::getValorAtributoSetting('subcategoria_default');
        } else {
            // Usar el disco configurado para generar la URL correcta
            $disk = GlobalHelper::discoArchivos();
            if ($disk === 's3') {
                // Para S3, usar la URL completa
                $config = config('filesystems.disks.s3');
                $bucket = $config['bucket'] ?? '';
                $region = $config['region'] ?? 'us-east-1';
                $baseUrl = "https://{$bucket}.s3.{$region}.amazonaws.com";
                return $baseUrl . self::RUTA_FOTO . $this->foto;
            } else {
                // Para local, usar asset()
                return asset('imagenes/subcategorias/' . $this->foto);
            }
        }
    }
    public function scopeTieneProductosDisponibles($query)
    {
        // Obtener solo tags con productos disponibles y visibles al cliente
        return $query->whereHas('productos', function ($query) {
            $query->publicoTienda();
        });
    }

    public function getGruposAdicionalesConAdicionalesEnSubCategoria()
    {
        // Opción 1: Usando Eloquent con relaciones (más limpio)
        return GrupoAdicionales::whereHas('adicionalesEnSubcategoria', function ($query) {
            $query->where('adicionale_subcategoria.subcategoria_id', $this->id);
        })->get();
    }

    // Relación para obtener los grupos que tienen adicionales en esta subcategoría
    public function gruposAdicionalesConAdicionales()
    {
        return $this->belongsToMany(GrupoAdicionales::class, 'adicionale_subcategoria', 'subcategoria_id', 'grupo_adicionales_id')
            ->whereNotNull('grupo_adicionales_id')
            ->distinct();
    }

    // Relación directa para obtener grupos con adicionales en esta subcategoría
    public function gruposConAdicionalesEnSubcategoria()
    {
        return $this->belongsToMany(GrupoAdicionales::class, 'adicionale_subcategoria', 'subcategoria_id', 'grupo_adicionales_id')
            ->whereNotNull('adicionale_subcategoria.grupo_adicionales_id')
            ->distinct();
    }

    // Alternativa usando DB Query Builder (más eficiente para consultas complejas)
    public function getGruposAdicionalesConAdicionalesEnSubCategoriaDB()
    {
        return DB::table('grupos_adicionales')
            ->join('adicionale_subcategoria', 'grupos_adicionales.id', '=', 'adicionale_subcategoria.grupo_adicionales_id')
            ->where('adicionale_subcategoria.subcategoria_id', $this->id)
            ->whereNotNull('adicionale_subcategoria.grupo_adicionales_id')
            ->select('grupos_adicionales.*')
            ->distinct()
            ->get()
            ->map(function ($item) {
                return new GrupoAdicionales((array) $item);
            });
    }

    // Método más simple usando la relación directa
    public function gruposConAdicionales()
    {
        return $this->gruposAdicionalesConAdicionales()->get();
    }

    public function gruposAdicionales()
    {
        return $this->hasMany(GrupoAdicionales::class);
    }

    // Relación para obtener todos los grupos de adicionales de esta subcategoría
    public function gruposAdicionalesDeSubcategoria()
    {
        return $this->hasMany(GrupoAdicionales::class, 'subcategoria_id');
    }
}
