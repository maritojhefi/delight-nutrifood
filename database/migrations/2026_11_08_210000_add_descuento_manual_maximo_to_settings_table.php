<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

class AddDescuentoManualMaximoToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 25% por defecto: el vendedor no puede descontar más de un cuarto del total de la venta
        Setting::firstOrCreate(
            ['atributo' => Setting::DESCUENTO_MANUAL_MAXIMO],
            ['valor' => '25', 'es_imagen' => false]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::where('atributo', Setting::DESCUENTO_MANUAL_MAXIMO)->delete();
    }
}
