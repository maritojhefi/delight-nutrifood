<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\Sucursale;
// Laravel Sanctum removido - usando auth estándar
use Illuminate\Foundation\Testing\RefreshDatabase;

class VentaApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear usuario autenticado
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        
        // Crear datos de prueba
        $this->sucursal = Sucursale::factory()->create();
        $this->cliente = User::factory()->create(['role_id' => 4]); // Cliente
        $this->producto = Producto::factory()->create();
    }

    /** @test */
    public function puede_listar_ventas()
    {
        // Crear ventas de prueba
        Venta::factory()->count(3)->create([
            'usuario_id' => $this->user->id,
            'sucursale_id' => $this->sucursal->id
        ]);

        $response = $this->getJson('/api/ventas');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'current_page',
                        'data' => [
                            '*' => ['id', 'usuario_id', 'sucursale_id', 'total', 'pagado']
                        ],
                        'per_page',
                        'total'
                    ],
                    'message'
                ]);
    }

    /** @test */
    public function puede_crear_nueva_venta()
    {
        $data = [
            'sucursale_id' => $this->sucursal->id,
            'cliente_id' => $this->cliente->id
        ];

        $response = $this->postJson('/api/ventas', $data);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'data' => ['id', 'usuario_id', 'sucursale_id', 'cliente_id'],
                    'message'
                ]);

        $this->assertDatabaseHas('ventas', [
            'usuario_id' => $this->user->id,
            'sucursale_id' => $this->sucursal->id,
            'cliente_id' => $this->cliente->id
        ]);
    }

    /** @test */
    public function puede_obtener_venta_especifica()
    {
        $venta = Venta::factory()->create([
            'usuario_id' => $this->user->id,
            'sucursale_id' => $this->sucursal->id
        ]);

        $response = $this->getJson("/api/ventas/{$venta->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'venta' => ['id', 'usuario_id', 'sucursale_id'],
                        'calculos' => ['subtotal', 'itemsCuenta', 'puntos']
                    ],
                    'message'
                ]);
    }

    /** @test */
    public function puede_agregar_producto_a_venta()
    {
        $venta = Venta::factory()->create([
            'usuario_id' => $this->user->id,
            'sucursale_id' => $this->sucursal->id
        ]);

        $data = [
            'producto_id' => $this->producto->id,
            'cantidad' => 2
        ];

        $response = $this->postJson("/api/ventas/{$venta->id}/productos", $data);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'venta',
                        'calculos'
                    ],
                    'message'
                ]);
    }

    /** @test */
    public function puede_actualizar_descuento()
    {
        $venta = Venta::factory()->create([
            'usuario_id' => $this->user->id,
            'sucursale_id' => $this->sucursal->id
        ]);

        $data = ['descuento' => 10.50];

        $response = $this->patchJson("/api/ventas/{$venta->id}/descuento", $data);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('ventas', [
            'id' => $venta->id,
            'descuento' => 10.50
        ]);
    }

    /** @test */
    public function puede_cambiar_cliente()
    {
        $venta = Venta::factory()->create([
            'usuario_id' => $this->user->id,
            'sucursale_id' => $this->sucursal->id
        ]);

        $nuevoCliente = User::factory()->create(['role_id' => 4]);
        $data = ['cliente_id' => $nuevoCliente->id];

        $response = $this->patchJson("/api/ventas/{$venta->id}/cliente", $data);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('ventas', [
            'id' => $venta->id,
            'cliente_id' => $nuevoCliente->id
        ]);
    }

    /** @test */
    public function puede_enviar_a_cocina()
    {
        $venta = Venta::factory()->create([
            'usuario_id' => $this->user->id,
            'sucursale_id' => $this->sucursal->id
        ]);

        $response = $this->postJson("/api/ventas/{$venta->id}/enviar-cocina");

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('ventas', [
            'id' => $venta->id,
            'cocina' => true
        ]);
    }

    /** @test */
    public function puede_eliminar_venta()
    {
        $venta = Venta::factory()->create([
            'usuario_id' => $this->user->id,
            'sucursale_id' => $this->sucursal->id
        ]);

        $response = $this->deleteJson("/api/ventas/{$venta->id}");

        $response->assertStatus(200);
        
        $this->assertDatabaseMissing('ventas', [
            'id' => $venta->id
        ]);
    }

    /** @test */
    public function no_puede_crear_venta_sin_sucursal()
    {
        $data = [
            'cliente_id' => $this->cliente->id
            // sucursale_id faltante
        ];

        $response = $this->postJson('/api/ventas', $data);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['sucursale_id']);
    }

    /** @test */
    public function no_puede_acceder_sin_autenticacion()
    {
        // Crear una nueva instancia de test sin autenticación
        $response = $this->withoutMiddleware([])->getJson('/api/ventas');

        $response->assertStatus(302); // Redirect al login
    }
}
