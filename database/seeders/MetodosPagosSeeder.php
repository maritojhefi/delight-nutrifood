<?php

namespace Database\Seeders;

use App\Models\MetodoPago;
use App\Models\Sucursale;
use Illuminate\Database\Seeder;

class MetodosPagosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            'Efectivo' => ['Efectivo', 'Pago en efectivo', 'efectivo.jpg'],
            'Tarjeta' => ['Tarjeta', 'Con tarjetero', 'tarjeta.avif'],
            'Banco Sol' => ['Sol', 'Transferencia QR a Banco Sol', 'sol.png'],
            'Banco BNB' => ['BNB', 'Transferencia QR a Banco BNB', 'bnb.png'],
        ];

        foreach ($array as $nombre => $metodo) {
            MetodoPago::updateOrCreate([
                'nombre_metodo_pago' => $nombre
            ], [
                'codigo' => $metodo[0],
                'descripcion' => $metodo[1],
                'imagen' => $metodo[2],
                'sucursal_id' => Sucursale::first()->id,
            ]);
        }
    }
}
