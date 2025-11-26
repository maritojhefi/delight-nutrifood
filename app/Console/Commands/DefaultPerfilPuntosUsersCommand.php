<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\PerfilPunto;
use Illuminate\Console\Command;

class DefaultPerfilPuntosUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asignar:perfil-puntos-default';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Asigna el perfil de puntos default a los usuarios';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $perfilDefault = PerfilPunto::where('default', true)->first();
        if (!$perfilDefault) {
            $this->error('No se encontró el perfil de puntos default');
            return Command::FAILURE;
        }
        $usuarios = User::all();
        foreach ($usuarios as $usuario) {
            if ($usuario->perfilesPuntos->count() == 0) {
                //asignar el perfil de puntos default al usuario solo si no tiene ninguno asignado
                $usuario->perfilesPuntos()->attach($perfilDefault->id);
            }
        }
        $this->info('Se asignaron ' . $usuarios->count() . ' perfiles de puntos default a los usuarios');
        return Command::SUCCESS;
    }
}
