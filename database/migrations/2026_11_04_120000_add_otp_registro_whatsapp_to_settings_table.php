<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

class AddOtpRegistroWhatsappToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Desactivado por defecto: el envío del OTP por WhatsApp está fallando
        Setting::firstOrCreate(
            ['atributo' => Setting::OTP_REGISTRO_WHATSAPP],
            ['valor' => '0', 'es_imagen' => false]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::where('atributo', Setting::OTP_REGISTRO_WHATSAPP)->delete();
    }
}
