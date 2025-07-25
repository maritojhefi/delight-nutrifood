<?php

namespace Database\Factories;

use App\Models\Producto;
use App\Helpers\GlobalHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Producto::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre' => $this->faker->unique()->word,
            'precio' => $this->faker->numberBetween($min = 10, $max = 1000),
            'detalle' =>  $this->faker->text,
            'subcategoria_id' => $this->faker->numberBetween($min = 1, $max = 5),
            'estado' => $this->faker->randomElement($array = array('inactivo', 'activo')),
            'imagen' => GlobalHelper::getValorAtributoSetting(atributo: 'nombre_foto_logo'),
            'codigoBarra' => strtolower(GlobalHelper::getValorAtributoSetting('nombre_sistema')) . $this->faker->numberBetween($min = 1, $max = 1000),
            'descuento' => $this->faker->numberBetween($min = 10, $max = 1000),
            'puntos' => $this->faker->numberBetween($min = 3, $max = 20),
        ];
    }
}
