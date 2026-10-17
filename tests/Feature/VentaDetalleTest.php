<?php

use App\Livewire\VentaDetalle;
use App\Models\CanjePunto;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Festividad;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Promocion;
use App\Models\TipoPago;
use App\Models\User;
use App\Models\Venta;
use Livewire\Livewire;

function crearProductoConStock(int $stock, float $precio = 40): Producto
{
    $categoria = Categoria::create([
        'nombre' => 'Pizzas '.uniqid(),
        'descripcion' => 'Categoria de prueba',
        'estado' => 1,
    ]);

    $producto = Producto::create([
        'categoria_id' => $categoria->id,
        'nombre' => 'Pizza '.uniqid(),
        'descripcion' => 'Pizza de prueba',
        'precio' => $precio,
        'estado' => 1,
    ]);

    Inventario::create([
        'producto_id' => $producto->id,
        'cantidad' => $stock,
        'estado' => 1,
    ]);

    return $producto->fresh('inventario');
}

function payloadProductos(Producto $producto): array
{
    return [[
        'id' => $producto->id,
        'nombre' => $producto->nombre,
        'categoria' => 'Pizzas',
        'precio' => $producto->precio,
        'stock' => (int) $producto->inventario->cantidad,
        'imagen' => null,
    ]];
}

function crearCliente(array $overrides = []): Cliente
{
    return Cliente::create(array_merge([
        'nombre' => 'Cliente Test',
        'ci' => (string) fake()->unique()->numerify('#######'),
        'celular' => (string) fake()->unique()->numerify('7#######'),
        'correo' => fake()->unique()->safeEmail(),
        'estado' => 1,
    ], $overrides));
}

function crearTipoPago(): TipoPago
{
    return TipoPago::create([
        'nombre' => 'Efectivo '.uniqid(),
        'estado' => 1,
        'activo' => 1,
    ]);
}

function crearPromocion(float $porcentaje, float $compraMinima = 0): Promocion
{
    $festividad = Festividad::create([
        'nombre' => 'Festividad '.uniqid(),
        'descripcion' => 'Prueba',
        'estado' => 1,
    ]);

    return Promocion::create([
        'festividad_id' => $festividad->id,
        'nombre' => 'Promo '.uniqid(),
        'descuento' => $porcentaje,
        'fecha_inicio' => now()->subDay()->toDateString(),
        'fecha_fin' => now()->addDay()->toDateString(),
        'compra_minima' => $compraMinima,
        'limite_uso' => 10,
        'estado' => 1,
    ]);
}

test('no agrega un producto si la cantidad supera el stock y muestra alerta', function () {
    $producto = crearProductoConStock(2);
    $cliente = crearCliente();

    session(['cliente' => $cliente->toArray()]);

    Livewire::test(VentaDetalle::class, ['productos' => payloadProductos($producto)])
        ->set('producto_id', $producto->id)
        ->set('cantidad', 5)
        ->call('agregarProducto')
        ->assertHasErrors(['cantidad']);

    expect(session('productos_venta', []))->toBeEmpty();
});

test('deshabilita agregar cuando el producto no tiene stock', function () {
    $producto = crearProductoConStock(0);
    $cliente = crearCliente();

    session(['cliente' => $cliente->toArray()]);

    $component = Livewire::test(VentaDetalle::class, ['productos' => payloadProductos($producto)])
        ->set('producto_id', $producto->id)
        ->set('cantidad', 1);

    expect($component->instance()->puedeAgregarProducto())->toBeFalse();

    $component->call('agregarProducto')->assertHasErrors(['cantidad']);
});

test('descuenta stock y no suma puntos en venta sin cliente', function () {
    $user = User::factory()->withoutTwoFactor()->create();
    $producto = crearProductoConStock(5, 40);
    $tipoPago = crearTipoPago();
    $anonimo = Cliente::firstOrCreate(
        ['ci' => config('app.ci_anonimo')],
        [
            'nombre' => 'Sin nombre',
            'celular' => config('app.ci_anonimo'),
            'correo' => null,
            'estado' => 1,
        ]
    );

    session(['cliente' => $anonimo->toArray()]);

    $this->actingAs($user);

    Livewire::test(VentaDetalle::class, ['productos' => payloadProductos($producto)])
        ->set('producto_id', $producto->id)
        ->set('cantidad', 2)
        ->call('agregarProducto')
        ->assertHasNoErrors()
        ->set('tipo_pago_id', $tipoPago->id)
        ->call('confirmarVenta')
        ->assertHasNoErrors()
        ->assertRedirect();

    $venta = Venta::first();

    expect($venta)->not->toBeNull()
        ->and((float) $venta->subtotal)->toBe(80.0)
        ->and((float) $venta->descuento)->toBe(0.0)
        ->and((float) $venta->total)->toBe(80.0)
        ->and((int) $venta->puntos)->toBe(0)
        ->and((int) $producto->inventario()->first()->cantidad)->toBe(3);
});

test('al seleccionar una promocion actualiza el descuento y el total a pagar', function () {
    $producto = crearProductoConStock(5, 100);
    $cliente = crearCliente();
    $promocion = crearPromocion(10);

    session(['cliente' => $cliente->toArray()]);

    Livewire::test(VentaDetalle::class, ['productos' => payloadProductos($producto)])
        ->set('producto_id', $producto->id)
        ->set('cantidad', 1)
        ->call('agregarProducto')
        ->set('promocion_id', $promocion->id)
        ->assertSet('descuento', 10.0)
        ->assertSet('total', 90.0)
        ->assertSee('Descuento aplicado')
        ->assertSee($promocion->nombre);
});

test('aplica descuento por porcentaje de promocion y suma puntos al cliente', function () {
    $user = User::factory()->withoutTwoFactor()->create();
    $producto = crearProductoConStock(5, 100);
    $tipoPago = crearTipoPago();
    $cliente = crearCliente();
    $promocion = crearPromocion(10);

    session(['cliente' => $cliente->toArray()]);

    $this->actingAs($user);

    Livewire::test(VentaDetalle::class, ['productos' => payloadProductos($producto)])
        ->set('producto_id', $producto->id)
        ->set('cantidad', 1)
        ->call('agregarProducto')
        ->set('promocion_id', $promocion->id)
        ->set('tipo_pago_id', $tipoPago->id)
        ->call('confirmarVenta')
        ->assertHasNoErrors();

    $venta = Venta::first();

    expect((float) $venta->subtotal)->toBe(100.0)
        ->and((float) $venta->descuento)->toBe(10.0)
        ->and((float) $venta->total)->toBe(90.0)
        ->and((int) $venta->puntos)->toBe(9)
        ->and((int) $venta->promocion_id)->toBe($promocion->id)
        ->and((int) $producto->inventario()->first()->cantidad)->toBe(4);
});

test('permite promocion en venta sin cliente y no canjea puntos', function () {
    $user = User::factory()->withoutTwoFactor()->create();
    $producto = crearProductoConStock(3, 50);
    $tipoPago = crearTipoPago();
    $promocion = crearPromocion(20);
    $anonimo = Cliente::firstOrCreate(
        ['ci' => config('app.ci_anonimo')],
        [
            'nombre' => 'Sin nombre',
            'celular' => config('app.ci_anonimo'),
            'correo' => null,
            'estado' => 1,
        ]
    );

    session(['cliente' => $anonimo->toArray()]);

    $this->actingAs($user);

    Livewire::test(VentaDetalle::class, ['productos' => payloadProductos($producto)])
        ->set('producto_id', $producto->id)
        ->set('cantidad', 2)
        ->call('agregarProducto')
        ->set('promocion_id', $promocion->id)
        ->set('tipo_pago_id', $tipoPago->id)
        ->call('confirmarVenta')
        ->assertHasNoErrors();

    $venta = Venta::first();

    expect((float) $venta->subtotal)->toBe(100.0)
        ->and((float) $venta->descuento)->toBe(20.0)
        ->and((float) $venta->total)->toBe(80.0)
        ->and((int) $venta->puntos)->toBe(0)
        ->and($venta->promocion_id)->toBe($promocion->id);
});

test('aplica canje de puntos en venta con cliente y descuenta stock', function () {
    $user = User::factory()->withoutTwoFactor()->create();
    $producto = crearProductoConStock(4, 40);
    $tipoPago = crearTipoPago();
    $cliente = crearCliente();

    CanjePunto::create([
        'nombre' => 'Canje fidelidad',
        'descripcion' => '100 puntos por Bs 10',
        'puntos' => 100,
        'descuento' => 10,
        'estado' => 1,
    ]);

    Venta::create([
        'usuario_id' => $user->id,
        'cliente_id' => $cliente->id,
        'tipo_pago_id' => $tipoPago->id,
        'puntos' => 120,
        'subtotal' => 200,
        'descuento' => 0,
        'total' => 200,
        'estado' => 1,
    ]);

    session(['cliente' => $cliente->toArray()]);

    $this->actingAs($user);

    Livewire::test(VentaDetalle::class, ['productos' => payloadProductos($producto)])
        ->set('producto_id', $producto->id)
        ->set('cantidad', 1)
        ->call('agregarProducto')
        ->set('usar_puntos', true)
        ->set('tipo_pago_id', $tipoPago->id)
        ->call('confirmarVenta')
        ->assertHasNoErrors();

    $venta = Venta::orderByDesc('id')->first();

    expect((float) $venta->subtotal)->toBe(40.0)
        ->and((float) $venta->descuento)->toBe(10.0)
        ->and((float) $venta->total)->toBe(30.0)
        ->and($venta->promocion_id)->toBeNull()
        ->and((int) $producto->inventario()->first()->cantidad)->toBe(3)
        ->and($cliente->fresh()->saldo_puntos)->toBe(120 + (int) $venta->puntos - 100);
});

test('canjea bloques completos cuando el saldo supera la base de puntos', function () {
    $user = User::factory()->withoutTwoFactor()->create();
    $producto = crearProductoConStock(4, 80);
    $tipoPago = crearTipoPago();
    $cliente = crearCliente();

    CanjePunto::create([
        'nombre' => 'Canje fidelidad',
        'descripcion' => '100 puntos por Bs 10',
        'puntos' => 100,
        'descuento' => 10,
        'estado' => 1,
    ]);

    Venta::create([
        'usuario_id' => $user->id,
        'cliente_id' => $cliente->id,
        'tipo_pago_id' => $tipoPago->id,
        'puntos' => 234,
        'subtotal' => 400,
        'descuento' => 0,
        'total' => 400,
        'estado' => 1,
    ]);

    session(['cliente' => $cliente->toArray()]);

    $this->actingAs($user);

    Livewire::test(VentaDetalle::class, ['productos' => payloadProductos($producto)])
        ->set('producto_id', $producto->id)
        ->set('cantidad', 1)
        ->call('agregarProducto')
        ->assertSee('Canjear')
        ->assertSee('200')
        ->assertSee('20.00')
        ->assertSee('Quedan 34')
        ->set('usar_puntos', true)
        ->assertSet('descuento', 20.0)
        ->assertSet('total', 60.0)
        ->set('tipo_pago_id', $tipoPago->id)
        ->call('confirmarVenta')
        ->assertHasNoErrors();

    $venta = Venta::orderByDesc('id')->first();

    expect((float) $venta->subtotal)->toBe(80.0)
        ->and((float) $venta->descuento)->toBe(20.0)
        ->and((float) $venta->total)->toBe(60.0)
        ->and((int) $venta->puntos)->toBe(6)
        ->and($cliente->fresh()->saldo_puntos)->toBe(234 + 6 - 200);
});

test('no convierte el resto de puntos en un descuento parcial', function () {
    $user = User::factory()->withoutTwoFactor()->create();
    $producto = crearProductoConStock(4, 80);
    $tipoPago = crearTipoPago();
    $cliente = crearCliente();

    CanjePunto::create([
        'nombre' => 'Canje fidelidad',
        'descripcion' => '100 puntos por Bs 10',
        'puntos' => 100,
        'descuento' => 10,
        'estado' => 1,
    ]);

    Venta::create([
        'usuario_id' => $user->id,
        'cliente_id' => $cliente->id,
        'tipo_pago_id' => $tipoPago->id,
        'puntos' => 150,
        'subtotal' => 300,
        'descuento' => 0,
        'total' => 300,
        'estado' => 1,
    ]);

    session(['cliente' => $cliente->toArray()]);

    $this->actingAs($user);

    Livewire::test(VentaDetalle::class, ['productos' => payloadProductos($producto)])
        ->set('producto_id', $producto->id)
        ->set('cantidad', 1)
        ->call('agregarProducto')
        ->set('usar_puntos', true)
        ->assertSet('descuento', 10.0)
        ->assertSet('total', 70.0)
        ->set('tipo_pago_id', $tipoPago->id)
        ->call('confirmarVenta')
        ->assertHasNoErrors();

    $venta = Venta::orderByDesc('id')->first();

    expect((float) $venta->descuento)->toBe(10.0)
        ->and($cliente->fresh()->saldo_puntos)->toBe(150 + (int) $venta->puntos - 100);
});

test('no muestra promociones cuya festividad esta inactiva', function () {
    $producto = crearProductoConStock(1);
    $cliente = crearCliente();

    $promoFestividadActiva = crearPromocion(15);

    $festividadInactiva = Festividad::create([
        'nombre' => 'Festividad inactiva '.uniqid(),
        'descripcion' => 'Prueba',
        'estado' => 0,
    ]);

    $promoFestividadInactiva = Promocion::create([
        'festividad_id' => $festividadInactiva->id,
        'nombre' => 'Promo festividad off '.uniqid(),
        'descuento' => 50,
        'fecha_inicio' => now()->subDay()->toDateString(),
        'fecha_fin' => now()->addDay()->toDateString(),
        'compra_minima' => 0,
        'limite_uso' => 10,
        'estado' => 1,
    ]);

    session(['cliente' => $cliente->toArray()]);

    Livewire::test(VentaDetalle::class, ['productos' => payloadProductos($producto)])
        ->assertSee($promoFestividadActiva->nombre)
        ->assertDontSee($promoFestividadInactiva->nombre);
});

test('no muestra promociones completadas en crear venta', function () {
    $producto = crearProductoConStock(1);
    $cliente = crearCliente();

    $promoActiva = crearPromocion(15);
    $promoCompletada = crearPromocion(50);
    $promoCompletada->update(['estado' => Promocion::ESTADO_COMPLETADO]);

    session(['cliente' => $cliente->toArray()]);

    Livewire::test(VentaDetalle::class, ['productos' => payloadProductos($producto)])
        ->assertSee($promoActiva->nombre)
        ->assertDontSee($promoCompletada->nombre);
});

test('marca promocion como completada al agotar limite de uso en la venta', function () {
    $user = User::factory()->withoutTwoFactor()->create();
    $producto = crearProductoConStock(5);
    $cliente = crearCliente();
    $tipoPago = crearTipoPago();

    $promocion = crearPromocion(10);
    $promocion->update(['limite_uso' => 1]);

    session(['cliente' => $cliente->toArray()]);

    $this->actingAs($user);

    Livewire::test(VentaDetalle::class, ['productos' => payloadProductos($producto)])
        ->set('producto_id', $producto->id)
        ->set('cantidad', 1)
        ->call('agregarProducto')
        ->set('promocion_id', $promocion->id)
        ->set('tipo_pago_id', $tipoPago->id)
        ->call('confirmarVenta')
        ->assertHasNoErrors();

    expect($promocion->fresh())
        ->estado->toBe(Promocion::ESTADO_COMPLETADO)
        ->limite_uso->toBe(0);
});

test('no muestra tipos de pago ni promociones inactivas', function () {
    $producto = crearProductoConStock(1);
    $cliente = crearCliente();
    $activo = crearTipoPago();

    $tipoInactivo = TipoPago::create([
        'nombre' => 'Pago inactivo '.uniqid(),
        'estado' => 1,
        'activo' => 0,
    ]);

    $promoActiva = crearPromocion(15);
    $promoInactiva = crearPromocion(50);
    $promoInactiva->update(['estado' => Promocion::ESTADO_BAJA]);

    session(['cliente' => $cliente->toArray()]);

    Livewire::test(VentaDetalle::class, ['productos' => payloadProductos($producto)])
        ->assertSee($activo->nombre)
        ->assertDontSee($tipoInactivo->nombre)
        ->assertSee($promoActiva->nombre)
        ->assertDontSee($promoInactiva->nombre);
});
