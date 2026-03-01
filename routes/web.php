<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

use App\Http\Controllers\ReporteController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\PrivilegioController;
use App\Http\Controllers\TipoPagoController;
use App\Http\Controllers\FestividadController;
use App\Http\Controllers\PromocionController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/', [DashboardController::class, 'predashboard'])->name('pre-dashboard');
Route::post('/cliente', [DashboardController::class, 'client'])->name('clientes.search');
// Todas las vistas se mostraran cuando se inicie sesion
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::redirect('settings', 'settings/profile');

    Route::get('/privilegios', [PrivilegioController::class, 'index'])->middleware('privilege:listar-privilegio')->name('privilegios.index');
    Route::get('/privilegios/create', [PrivilegioController::class, 'create'])->middleware('privilege:crear-privilegio')->name('privilegios.create');
    Route::post('/privilegios', [PrivilegioController::class, 'store'])->middleware('privilege:guardar-privilegio')->name('privilegios.store');
    Route::get('/privilegios/{privilegio}/edit', [PrivilegioController::class, 'edit'])->middleware('privilege:editar-privilegio')->name('privilegios.edit');
    Route::put('/privilegios/{privilegio}', [PrivilegioController::class, 'update'])->middleware('privilege:actualizar-privilegio')->name('privilegios.update');
    Route::get('/privilegios/{id}/toggle', [PrivilegioController::class, 'toggle'])->name('privilegios.toggle');

    Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RolController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RolController::class, 'store'])->name('roles.store');
    Route::get('/roles/{rol}/edit', [RolController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{rol}', [RolController::class, 'update'])->name('roles.update');
    Route::get('/roles/{rol}', [RolController::class, 'show'])->name('roles.show');
    Route::get('roles/{rol}/toggle', [RolController::class, 'toggle'])->name('roles.toggle');

    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::get('/usuarios/{usuario}', [UsuarioController::class, 'show'])->name('usuarios.show');
    Route::get('/usuarios/{id}/toggle', [UsuarioController::class, 'toggle'])->name('usuarios.toggle');
    Route::get('usuarios/{usuario}/reset', [UsuarioController::class, 'toggle'])->name('usuarios.reset');

    Route::get('/tipo_pagos', [TipoPagoController::class, 'index'])->name('tipo_pagos.index');
    Route::get('/tipo_pagos/create', [TipoPagoController::class, 'create'])->name('tipo_pagos.create');
    Route::post('/tipo_pagos', [TipoPagoController::class, 'store'])->name('tipo_pagos.store');
    Route::get('/tipo_pagos/{tipo_pago}/edit', [TipoPagoController::class, 'edit'])->name('tipo_pagos.edit');
    Route::put('/tipo_pagos/{tipo_pago}', [TipoPagoController::class, 'update'])->name('tipo_pagos.update');
    Route::get('/tipo_pagos/{tipo_pago}/toggle', [TipoPagoController::class, 'toggle'])->name('tipo_pagos.toggle');

    Route::get('/festividades', [FestividadController::class, 'index'])->name('festividades.index');
    Route::get('/festividades/create', [FestividadController::class, 'create'])->name('festividades.create');
    Route::post('/festividades', [FestividadController::class, 'store'])->name('festividades.store');
    Route::get('/festividades/{festividad}/edit', [FestividadController::class, 'edit'])->name('festividades.edit');
    Route::put('/festividades/{festividad}', [FestividadController::class, 'update'])->name('festividades.update');
    Route::get('/festividades/{festividad}/destroy', [FestividadController::class, 'destroy'])->name('festividades.destroy');

    Route::get('/promociones', [PromocionController::class, 'index'])->name('promociones.index');
    Route::get('/promociones/create', [PromocionController::class, 'create'])->name('promociones.create');
    Route::post('/promociones', [PromocionController::class, 'store'])->name('promociones.store');
    Route::get('/promociones/{promocion}/show', [PromocionController::class, 'show'])->name('promociones.show');
    Route::get('/promociones/{promocion}/edit', [PromocionController::class, 'edit'])->name('promociones.edit');
    Route::put('/promociones/{promocion}', [PromocionController::class, 'update'])->name('promociones.update');
    Route::get('/promociones/{promocion}/destroy', [PromocionController::class, 'destroy'])->name('promociones.destroy');

    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    Route::get('/clientes/{cliente}/show', [ClienteController::class, 'show'])->name('clientes.show');
    Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
    Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::get('/clientes/{cliente}/destroy', [ClienteController::class, 'destroy'])->name('clientes.destroy');
    Route::post('/clientes/{cliente}/canjear', [ClienteController::class, 'canjear'])->name('clientes.canjear');

    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::get('/categorias/create', [CategoriaController::class, 'create'])->name('categorias.create');
    Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::get('/categorias/{categoria}/show', [CategoriaController::class, 'show'])->name('categorias.show');
    Route::get('/categorias/{categoria}/edit', [CategoriaController::class, 'edit'])->name('categorias.edit');
    Route::put('/categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::get('/categorias/{categoria}/destroy', [CategoriaController::class, 'destroy'])->name('categorias.destroy');

    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
    Route::get('/productos/create', [ProductoController::class, 'create'])->name('productos.create');
    Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::get('/productos/{producto}/show', [ProductoController::class, 'show'])->name('productos.show');
    Route::get('/productos/{producto}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
    Route::put('/productos/{producto}', [ProductoController::class, 'update'])->name('productos.update');
    Route::get('/productos/{producto}/toggle', [ProductoController::class, 'toggle'])->name('productos.toggle');

    Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
    Route::get('/ventas/create', [VentaController::class, 'create'])->name('ventas.create');
    Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');
    Route::get('/ventas/{venta}/show', [VentaController::class, 'show'])->name('ventas.show');
    Route::put('/ventas/{venta}', [VentaController::class, 'update'])->name('ventas.update');
    Route::get('/ventas/{venta}/destroy', [VentaController::class, 'destroy'])->name('ventas.destroy');
    Route::post('/ventas/cliente', [VentaController::class, 'searchClient'])->name('ventas.searchClient');
    Route::post('/ventas/add-producto', [VentaController::class, 'addProducto'])->name('ventas.addProducto');
    Route::delete('/ventas/remove-producto', [VentaController::class, 'removeProducto'])->name('ventas.removeProducto');
    Route::get('/ventas/getTotalCompra', [VentaController::class, 'getTotalCompra'])->name('ventas.getTotalCompra');
    Route::post('/ventas/setPromocion', [VentaController::class, 'setPromocion'])->name('ventas.setPromocion');
    Route::post('/ventas/getPuntosCliente', [VentaController::class, 'getPuntosCliente'])->name('ventas.getPuntosCliente');
    Route::post('/ventas/setUsoPuntos', [VentaController::class, 'setUsoPuntos'])->name('ventas.setUsoPuntos');

   Route::get('/reportes', [ReporteController::class, 'index'])
    ->middleware('privilege:ver-reportes')
    ->name('reportes.index');

Route::get('/reportes/ventas', [ReporteController::class, 'ventas'])
    ->middleware('privilege:reporte-ventas')
    ->name('reportes.ventas');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

require __DIR__.'/auth.php';
