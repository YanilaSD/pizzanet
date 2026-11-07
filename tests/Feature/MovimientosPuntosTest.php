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
        ->assertSee('Cada Bs 10 = 1 pt')
        ->assertSee('Ganado')
        ->assertSee('+6');
});

test('al dar de baja una venta revierte el canje y los puntos ganados', function () {
    $cliente = Cliente::create([
        'nombre' => 'Baja Puntos',
        'ci' => '1112223',
        'celular' => '70000005',
        'correo' => 'baja@example.com',
        'estado' => 1,
    ]);

    $ventaPrevia = crearVentaDePuntos($cliente, 150);
    $venta = crearVentaDePuntos($cliente, 8);
    $regla = CanjePunto::create([
        'nombre' => 'Canje fidelidad',
        'descripcion' => '100 puntos por Bs 10',
        'puntos' => 100,
        'descuento' => 10,
        'estado' => 1,
    ]);

    $canje = HistorialCanje::create([
        'cliente_id' => $cliente->id,
        'canje_punto_id' => $regla->id,
        'venta_id' => $venta->id,
        'puntos' => 100,
        'fecha' => now()->toDateString(),
        'estado' => 1,
    ]);

    expect($cliente->fresh()->saldo_puntos)->toBe(150 + 8 - 100);

    $this->withoutMiddleware(\App\Http\Middleware\CheckPrivilege::class);

    $this->actingAs(User::factory()->create())
        ->post(route('ventas.destroy', $venta))
        ->assertRedirect(route('ventas.index'))
        ->assertSessionHas('ventas', "Se dio de baja la venta #{$venta->id}. Se revirtieron los puntos.");

    expect((int) $venta->fresh()->estado)->toBe(0)
        ->and((int) $canje->fresh()->estado)->toBe(1)
        ->and((int) $canje->fresh()->puntos)->toBe(100)
        ->and($cliente->fresh()->saldo_puntos)->toBe(150)
        ->and((int) $ventaPrevia->fresh()->estado)->toBe(1);

    $reversionCanje = HistorialCanje::query()
        ->where('venta_id', $venta->id)
        ->where('puntos', '<', 0)
        ->first();

    expect($reversionCanje)->not->toBeNull()
        ->and((int) $reversionCanje->puntos)->toBe(-100)
        ->and((int) $reversionCanje->canje_punto_id)->toBe((int) $canje->canje_punto_id)
        ->and($reversionCanje->id)->not->toBe($canje->id);

    $venta->fresh()->anular();

    expect(HistorialCanje::query()->where('venta_id', $venta->id)->count())->toBe(2);

    $movimientos = $cliente->fresh()->movimientosPuntos();
    $revertidos = $movimientos->where('tipo', 'revertido');

    expect($movimientos->total())->toBe(5)
        ->and($movimientos->pluck('tipo')->all())->toContain('canjeado', 'ganado', 'revertido')
        ->and($revertidos)->toHaveCount(2)
        ->and($revertidos->pluck('origen')->all())->toEqualCanonicalizing(['canje', 'venta'])
        ->and($revertidos->pluck('puntos')->map(fn ($puntos) => (int) $puntos)->all())->toEqualCanonicalizing([100, 8])
        ->and($revertidos->every(fn ($movimiento) => (int) $movimiento->venta_id === $venta->id))->toBeTrue();
});

test('la vista del historial muestra la reversión por baja de venta', function () {
    $this->withoutMiddleware(\App\Http\Middleware\CheckPrivilege::class);

    $cliente = Cliente::create([
        'nombre' => 'Vista Baja',
        'ci' => '4445556',
        'celular' => '70000006',
        'correo' => 'vistabaja@example.com',
        'estado' => 1,
    ]);

    $venta = crearVentaDePuntos($cliente, 12);
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

    $venta->anular();

    $this->actingAs(User::factory()->create())
        ->get(route('clientes.canjes', $cliente))
        ->assertOk()
        ->assertSee('Revertido')
        ->assertSee('Canjeado')
        ->assertSee('Ganado')
        ->assertSee('Baja de venta')
        ->assertSee('#'.$venta->id)
        ->assertSee('puntos ganados')
        ->assertSee('Pizza por puntos')
        ->assertSee('+50')
        ->assertSee('-50')
        ->assertSee('+12')
        ->assertSee('-12');
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
