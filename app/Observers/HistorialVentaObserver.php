<?php

namespace App\Observers;


use App\Models\User;
use App\Models\RegistroPunto;
use App\Models\Historial_venta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HistorialVentaObserver
{
    /**
     * Handle the Historial_venta "created" event.
     *
     * @param  \App\Models\Historial_venta  $historial_venta
     * @return void
     */
    public function created(Historial_venta $historial_venta)
    {
        if ($historial_venta->cliente_id != null && $historial_venta->puntos > 0) {
            // Cargar la relación del cliente
            $historial_venta->load('cliente');

            // Log para depuración
            // Log::info('Observer ejecutado', [
            //     'historial_venta_id' => $historial_venta->id,
            //     'cliente_id' => $historial_venta->cliente_id,
            //     'puntos' => $historial_venta->puntos,
            //     'partner_id' => $historial_venta->cliente ? $historial_venta->cliente->partner_id : 'cliente no cargado'
            // ]);

            if ($historial_venta->cliente && $historial_venta->cliente->partner_id) {
                // CASO 1: El cliente tiene un partner
                // Log::info('Procesando con partner', ['partner_id' => $historial_venta->cliente->partner_id]);
                $this->procesarPuntosConPartner($historial_venta);
            } else {
                // CASO 2: El cliente no tiene partner
                // Log::info('Procesando sin partner');
                $this->procesarPuntosSinPartner($historial_venta);
            }
        }
    }

    /**
     * Procesa los puntos cuando el cliente tiene un partner
     */
    private function procesarPuntosConPartner(Historial_venta $historial_venta)
    {
        $partnerId = $historial_venta->cliente->partner_id;
        $puntosOriginales = $historial_venta->puntos;

        // Log::info('Iniciando procesamiento con partner', [
        //     'partner_id' => $partnerId,
        //     'puntos_originales' => $puntosOriginales
        // ]);

        // Buscar el perfil de puntos del partner en la tabla intermedia
        $perfilPuntoUser = DB::table('perfiles_puntos_users')
            ->where('user_id', $partnerId)
            ->first();

        // Log::info('Perfil punto user encontrado', ['perfil' => $perfilPuntoUser]);

        if ($perfilPuntoUser) {
            // Buscar el perfil de puntos para obtener el porcentaje
            $perfilPunto = DB::table('perfiles_puntos')
                ->where('id', $perfilPuntoUser->perfil_punto_id)
                ->first();

            // Log::info('Perfil punto encontrado', ['perfil' => $perfilPunto]);

            if ($perfilPunto && $perfilPunto->porcentaje > 0) {
                // Calcular puntos del partner (porcentaje del total)
                $puntosPartner = ($puntosOriginales * $perfilPunto->porcentaje) / 100;

                // Verificar si es decimal y aplicar redondeo estándar
                if (fmod($puntosPartner, 1) != 0) {
                    // Es decimal, aplicar redondeo estándar (0.5 hacia arriba)
                    $puntosPartner = round($puntosPartner);
                }

                // Calcular puntos del cliente (resto)
                $puntosCliente = $puntosOriginales - $puntosPartner;

                // Log::info('Cálculos realizados', [
                //     'puntos_partner' => $puntosPartner,
                //     'puntos_cliente' => $puntosCliente
                // ]);

                // Actualizar puntos del partner
                $partner = User::find($partnerId);
                if ($partner) {
                    $puntosAnterioresPartner = $partner->puntos;
                    $partner->puntos += $puntosPartner;
                    $partner->save();
                    // Log::info('Partner actualizado', [
                    //     'partner_id' => $partnerId,
                    //     'puntos_anteriores' => $puntosAnterioresPartner,
                    //     'puntos_agregados' => $puntosPartner,
                    //     'puntos_nuevos' => $partner->puntos
                    // ]);
                } else {
                    Log::error('Partner no encontrado', ['partner_id' => $partnerId]);
                }

                // Actualizar puntos del cliente
                $cliente = User::find($historial_venta->cliente_id);
                if ($cliente) {
                    $puntosAnterioresCliente = $cliente->puntos;
                    $cliente->puntos += $puntosCliente;
                    $cliente->save();
                    // Log::info('Cliente actualizado', [
                    //     'cliente_id' => $historial_venta->cliente_id,
                    //     'puntos_anteriores' => $puntosAnterioresCliente,
                    //     'puntos_agregados' => $puntosCliente,
                    //     'puntos_nuevos' => $cliente->puntos
                    // ]);
                } else {
                    Log::error('Cliente no encontrado', ['cliente_id' => $historial_venta->cliente_id]);
                }

                // Crear registro de puntos
                try {
                    $registro = RegistroPunto::create([
                        'historial_venta_id' => $historial_venta->id,
                        'partner_id' => $partnerId,
                        'cliente_id' => $historial_venta->cliente_id,
                        'puntos_partner' => $puntosPartner,
                        'puntos_cliente' => $puntosCliente,
                        'total_puntos' => $puntosOriginales,
                        'tipo' => RegistroPunto::TIPO_VENTA,
                    ]);
                    // Log::info('RegistroPunto creado exitosamente', ['registro_id' => $registro->id]);
                } catch (\Exception $e) {
                    Log::error('Error al crear RegistroPunto', ['error' => $e->getMessage()]);
                }
            } else {
                // Si no hay porcentaje configurado, todos los puntos van al cliente
                // Log::info('No hay porcentaje configurado, procesando sin partner');
                $this->procesarPuntosSinPartner($historial_venta);
            }
        } else {
            // Si el partner no tiene perfil de puntos, todos los puntos van al cliente
            // Log::info('Partner no tiene perfil de puntos, procesando sin partner');
            $this->procesarPuntosSinPartner($historial_venta);
        }
    }

    /**
     * Procesa los puntos cuando el cliente no tiene partner
     */
    private function procesarPuntosSinPartner(Historial_venta $historial_venta)
    {
        $puntosOriginales = $historial_venta->puntos;

        // Log::info('Procesando sin partner', [
        //     'cliente_id' => $historial_venta->cliente_id,
        //     'puntos_originales' => $puntosOriginales
        // ]);

        // Actualizar puntos del cliente
        $cliente = User::find($historial_venta->cliente_id);
        if ($cliente) {
            $puntosAnteriores = $cliente->puntos;
            $cliente->puntos += $puntosOriginales;
            $cliente->save();
            // Log::info('Cliente actualizado (sin partner)', [
            //     'cliente_id' => $historial_venta->cliente_id,
            //     'puntos_anteriores' => $puntosAnteriores,
            //     'puntos_agregados' => $puntosOriginales,
            //     'puntos_nuevos' => $cliente->puntos
            // ]);
        } else {
            Log::error('Cliente no encontrado (sin partner)', ['cliente_id' => $historial_venta->cliente_id]);
        }

        // Crear registro de puntos (sin partner)
        try {
            $registro = RegistroPunto::create([
                'historial_venta_id' => $historial_venta->id,
                'partner_id' => null,
                'cliente_id' => $historial_venta->cliente_id,
                'puntos_partner' => 0,
                'puntos_cliente' => $puntosOriginales,
                'total_puntos' => $puntosOriginales,
                'tipo' => RegistroPunto::TIPO_VENTA,
            ]);
            // Log::info('RegistroPunto creado exitosamente (sin partner)', ['registro_id' => $registro->id]);
        } catch (\Exception $e) {
            Log::error('Error al crear RegistroPunto (sin partner)', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Handle the Historial_venta "updated" event.
     *
     * @param  \App\Models\Historial_venta  $historial_venta
     * @return void
     */
    public function updated(Historial_venta $historial_venta)
    {
        //
    }

    /**
     * Handle the Historial_venta "deleted" event.
     *
     * @param  \App\Models\Historial_venta  $historial_venta
     * @return void
     */
    public function deleted(Historial_venta $historial_venta)
    {
        //
    }

    /**
     * Handle the Historial_venta "restored" event.
     *
     * @param  \App\Models\Historial_venta  $historial_venta
     * @return void
     */
    public function restored(Historial_venta $historial_venta)
    {
        //
    }

    /**
     * Handle the Historial_venta "force deleted" event.
     *
     * @param  \App\Models\Historial_venta  $historial_venta
     * @return void
     */
    public function forceDeleted(Historial_venta $historial_venta)
    {
        //
    }
}
