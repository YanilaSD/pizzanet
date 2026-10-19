<?php

use App\Models\CanjePunto;
use App\Models\Cliente;
use App\Models\HistorialCanje;
use App\Models\TipoPago;
use App\Models\User;
use App\Models\Venta;

test('el historial mezcla puntos ganados y canjeados del mas reciente al mas antiguo', function () {
    $cliente = Cliente::create([
        'nombre' => 'Ana Puntos',
        'ci' => '1234567',
        'celular' => '70000001',
        'correo' => 'ana@example.com',
        'estado' => 1,
    ]);

    $otro = Cliente::create([
        'nombre' => 'Otro Cliente',
        'ci' => '7654321',
        'celular' => '70000002',
        'correo' => 'otro@example.com',
        'estado' => 1,
    ]);

    $ventaAntigua = crearVentaDePuntos($cliente, 4);
    $ventaNueva = crearVentaDePuntos($cliente, 9);
    crearVentaDePuntos($cliente, 0);
    $cancelada = crearVentaDePuntos($cliente, 20);
    $cancelada->update(['estado' => 0]);
    crearVentaDePuntos($otro, 15);

    Venta::whereKey($ventaAntigua->id)->update(['created_at' => now()->subDays(3)]);
    Venta::whereKey($ventaNueva->id)->update(['created_at' => now()->subDay()]);

    $regla = CanjePunto::create([
        'nombre' => '100 puntos por Bs 10',
        'descripcion' => 'Canje de prueba',
        'puntos' => 100,
        'descuento' => 10,
        'estado' => 1,
    ]);

    $canje = HistorialCanje::create([
        'cliente_id' => $cliente->id,
        'canje_punto_id' => $regla->id,
        'venta_id' => $ventaNueva->id,
        'puntos' => 100,
        'fecha' => now()->toDateString(),
        'estado' => 1,
    ]);

    $movimientos = $cliente->movimientosPuntos();

    expect($movimientos->total())->toBe(3)
        ->and($movimientos->pluck('tipo')->all())->toBe(['canjeado', 'ganado', 'ganado'])
        ->and($movimientos->pluck('puntos')->map(fn ($puntos) => (int) $puntos)->all())->toBe([100, 9, 4])
        ->and($movimientos->first()->detalle)->toBe('100 puntos por Bs 10')
        ->and((int) $movimientos->first()->venta_id)->toBe($ventaNueva->id)
        ->and((int) $movimientos->last()->venta_id)->toBe($ventaAntigua->id);

    request()->merge(['page' => 2]);

    $segunda = $cliente->movimientosPuntos(2);

    expect($segunda->total())->toBe(3)
        ->and($segunda)->toHaveCount(1)
        ->and($segunda->first()->tipo)->toBe('ganado')
        ->and((int) $segunda->first()->puntos)->toBe(4);

    expect($canje->exists)->toBeTrue();
});

test('la vista del cliente muestra el historial de movimientos paginado', function () {
    $this->withoutMiddleware(\App\Http\Middleware\CheckPrivilege::class);

    $cliente = Cliente::create([
        'nombre' => 'Luis Movimientos',
        'ci' => '8888888',
        'celular' => '70000003',
        'correo' => 'luis@example.com',
        'estado' => 1,
    ]);

    $venta = crearVentaDePuntos($cliente, 9);
    $regla = CanjePunto::create([
        'nombre' => 'Pizza por puntos',
        'descripcion' => 'Canje de prueba',
        'puntos' => 50,
        'descuento' => 10,
        'estado' => 1,
    ]);

    HistorialCanje::create([
        'cliente_id' => $cliente->id,
        'canje_punto_id' => $regla->id,
        'venta_id' => $venta->id,
        'puntos' => 50,
        'fecha' => now()->toDateString(),
        'estado' => 1,
    ]);

    $this->actingAs(User::factory()->create())
        ->get(route('clientes.canjes', $cliente))
        ->assertOk()
        ->assertSee('Historial de movimientos')
        ->assertSee('Luis Movimientos')
        ->assertSee('Ganado')
        ->assertSee('Canjeado')
        ->assertSee('Pizza por puntos')
        ->assertSee('+9')
        ->assertSee('-50')
        ->assertSee('Venta #'.$venta->id);
});

test('el portal del cliente lista los mismos movimientos', function () {
    $cliente = Cliente::create([
        'nombre' => 'Portal Cliente',
        'ci' => '9999999',
        'celular' => '70000004',
        'correo' => 'portal@example.com',
        'estado' => 1,
    ]);

    crearVentaDePuntos($cliente, 6);

    $this->withSession(['portal_cliente_id' => $cliente->id])
        ->get(route('cliente.portal'))
        ->assertOk()
        ->assertSee('Puntos disponibles')
        ->assertSee('Este mes')
        ->assertSee('Historial de movimientos')
        ->assertSee('Ganado')
        ->assertSee('+6');
});

function crearVentaDePuntos(Cliente $cliente, int $puntos): Venta
{
    $usuario = User::factory()->create();
    $tipoPago = TipoPago::create([
        'nombre' => 'Efectivo '.uniqid(),
        'estado' => 1,
        'activo' => 1,
    ]);

    return Venta::create([
        'usuario_id' => $usuario->id,
        'cliente_id' => $cliente->id,
        'tipo_pago_id' => $tipoPago->id,
        'puntos' => $puntos,
        'subtotal' => 90,
        'descuento' => 0,
        'total' => 90,
        'estado' => 1,
    ]);
}
