<?php

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermisosRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ruta null = acceso al módulo completo
        Schema::create('permisos_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->string('modulo', 50);
            $table->string('ruta', 100)->nullable();
            $table->timestamps();
            $table->index(['role_id', 'modulo']);
        });

        // Permisos iniciales = lo que cada rol ve hoy en el menú (admin no necesita registros)
        $iniciales = [
            Role::CAJERO => ['productos', 'usuarios', 'produccion', 'ventas', 'convenios', 'perifericos', 'otros', 'configuraciones', 'horarios', 'caja_diaria'],
            Role::COCINA => ['cocina', 'productos'],
            Role::INFLUENCER => ['productos', 'usuarios', 'produccion', 'ventas', 'convenios', 'perifericos', 'otros', 'configuraciones', 'horarios'],
        ];

        foreach ($iniciales as $roleId => $modulos) {
            if (!DB::table('roles')->where('id', $roleId)->exists()) {
                continue;
            }
            foreach ($modulos as $modulo) {
                DB::table('permisos_roles')->insert([
                    'role_id' => $roleId,
                    'modulo' => $modulo,
                    'ruta' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permisos_roles');
    }
}
