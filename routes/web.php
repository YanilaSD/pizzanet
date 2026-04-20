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
use App\Http\Controllers\DescuentoController;
use App\Http\Controllers\HistorialController;

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/', [DashboardController::class, 'predashboard'])->name('pre-dashboard');
Route::post('/cliente', [DashboardController::class, 'client'])->name('clientes.search');
// Todas las vistas se mostraran cuando se inicie sesion
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::redirect('settings', 'settings/profile');

    Route::prefix('privilegios')->group(function () {
        Route::get('/', [PrivilegioController::class, 'index'])->middleware('privilege:privilegios.index')->name('privilegios.index');
        Route::get('/create', [PrivilegioController::class, 'create'])->middleware('privilege:privilegios.create')->name('privilegios.create');
        Route::post('/', [PrivilegioController::class, 'store'])->middleware('privilege:privilegios.store')->name('privilegios.store');
        Route::get('/{privilegio}/edit', [PrivilegioController::class, 'edit'])->middleware('privilege:privilegios.edit')->name('privilegios.edit');
        Route::put('/{privilegio}', [PrivilegioController::class, 'update'])->middleware('privilege:privilegios.update')->name('privilegios.update');
        Route::get('/{id}/toggle', [PrivilegioController::class, 'toggle'])->middleware('privilege:privilegios.toggle')->name('privilegios.toggle');
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RolController::class, 'index'])->middleware('privilege:roles.index')->name('roles.index');
        Route::get('/create', [RolController::class, 'create'])->middleware('privilege:roles.create')->name('roles.create');
        Route::post('/', [RolController::class, 'store'])->middleware('privilege:roles.store')->name('roles.store');
        Route::get('/{rol}', [RolController::class, 'show'])->middleware('privilege:roles.show')->name('roles.show');
        Route::get('/{rol}/edit', [RolController::class, 'edit'])->middleware('privilege:roles.edit')->name('roles.edit');
        Route::put('/{rol}', [RolController::class, 'update'])->middleware('privilege:roles.update')->name('roles.update');
        Route::get('/{rol}/toggle', [RolController::class, 'toggle'])->middleware('privilege:roles.toggle')->name('roles.toggle');
    });

    Route::prefix('usuarios')->group(function () {
        Route::get('/', [UsuarioController::class, 'index'])->middleware('privilege:usuarios.index')->name('usuarios.index');
        Route::get('/create', [UsuarioController::class, 'create'])->middleware('privilege:usuarios.create')->name('usuarios.create');
        Route::post('/', [UsuarioController::class, 'store'])->middleware('privilege:usuarios.store')->name('usuarios.store');
        Route::get('/{usuario}', [UsuarioController::class, 'show'])->middleware('privilege:usuarios.show')->name('usuarios.show');
        Route::get('/{usuario}/edit', [UsuarioController::class, 'edit'])->middleware('privilege:usuarios.edit')->name('usuarios.edit');
        Route::put('/{usuario}', [UsuarioController::class, 'update'])->middleware('privilege:usuarios.update')->name('usuarios.update');
        Route::get('/{id}/toggle', [UsuarioController::class, 'toggle'])->middleware('privilege:usuarios.toggle')->name('usuarios.toggle');
        Route::get('/{usuario}/reset', [UsuarioController::class, 'reset_pwd'])->middleware('privilege:usuarios.reset')->name('usuarios.reset');
        Route::post('/{usuario}/reset-f2a', [UsuarioController::class, 'reset_f2a'])->middleware('privilege:usuarios.reset_f2a')->name('usuarios.reset_f2a');
    });

    Route::prefix('tipo_pagos')->group(function () {
        Route::get('/', [TipoPagoController::class, 'index'])->middleware('privilege:tipo_pagos.index')->name('tipo_pagos.index');
        Route::get('/create', [TipoPagoController::class, 'create'])->middleware('privilege:tipo_pagos.create')->name('tipo_pagos.create');
        Route::post('/', [TipoPagoController::class, 'store'])->middleware('privilege:tipo_pagos.store')->name('tipo_pagos.store');
        Route::get('/{tipo_pago}/edit', [TipoPagoController::class, 'edit'])->middleware('privilege:tipo_pagos.edit')->name('tipo_pagos.edit');
        Route::put('/{tipo_pago}', [TipoPagoController::class, 'update'])->middleware('privilege:tipo_pagos.update')->name('tipo_pagos.update');
        Route::get('/{tipo_pago}/toggle', [TipoPagoController::class, 'toggle'])->middleware('privilege:tipo_pagos.toggle')->name('tipo_pagos.toggle');
    });

    Route::prefix('festividades')->group(function () {
        Route::get('/', [FestividadController::class, 'index'])->middleware('privilege:festividades.index')->name('festividades.index');
        Route::get('/create', [FestividadController::class, 'create'])->middleware('privilege:festividades.create')->name('festividades.create');
        Route::post('/', [FestividadController::class, 'store'])->middleware('privilege:festividades.store')->name('festividades.store');
        Route::get('/{festividad}/edit', [FestividadController::class, 'edit'])->middleware('privilege:festividades.edit')->name('festividades.edit');
        Route::put('/{festividad}', [FestividadController::class, 'update'])->middleware('privilege:festividades.update')->name('festividades.update');
        Route::get('/{festividad}/destroy', [FestividadController::class, 'destroy'])->middleware('privilege:festividades.destroy')->name('festividades.destroy');
    });

    Route::prefix('promociones')->group(function () {
        Route::get('/', [PromocionController::class, 'index'])->middleware('privilege:promociones.index')->name('promociones.index');
        Route::get('/create', [PromocionController::class, 'create'])->middleware('privilege:promociones.create')->name('promociones.create');
        Route::post('/', [PromocionController::class, 'store'])->middleware('privilege:promociones.store')->name('promociones.store');
        Route::get('/{promocion}/show', [PromocionController::class, 'show'])->middleware('privilege:promociones.show')->name('promociones.show');
        Route::get('/{promocion}/edit', [PromocionController::class, 'edit'])->middleware('privilege:promociones.edit')->name('promociones.edit');
        Route::put('/{promocion}', [PromocionController::class, 'update'])->middleware('privilege:promociones.update')->name('promociones.update');
        Route::get('/{promocion}/destroy', [PromocionController::class, 'destroy'])->middleware('privilege:promociones.destroy')->name('promociones.destroy');
    });

    Route::prefix('clientes')->group(function () {
        Route::get('/', [ClienteController::class, 'index'])->middleware('privilege:clientes.index')->name('clientes.index');
        Route::get('/create', [ClienteController::class, 'create'])->middleware('privilege:clientes.create')->name('clientes.create');
        Route::post('/', [ClienteController::class, 'store'])->middleware('privilege:clientes.store')->name('clientes.store');
        Route::get('/{cliente}/show', [ClienteController::class, 'show'])->middleware('privilege:clientes.show')->name('clientes.show');
        Route::get('/{cliente}/edit', [ClienteController::class, 'edit'])->middleware('privilege:clientes.edit')->name('clientes.edit');
        Route::get('/{cliente}/canjes', [HistorialController::class, 'index'])->middleware('privilege:clientes.index')->name('clientes.canjes');
        Route::put('/{cliente}', [ClienteController::class, 'update'])->middleware('privilege:clientes.update')->name('clientes.update');
        Route::get('/{cliente}/destroy', [ClienteController::class, 'destroy'])->middleware('privilege:clientes.destroy')->name('clientes.destroy');
    });

    Route::prefix('descuentos')->group(function () {
        Route::get('/', [DescuentoController::class, 'index'])->name('descuentos.index');
        Route::get('/create', [DescuentoController::class, 'create'])->name('descuentos.create');
        Route::post('/', [DescuentoController::class, 'store'])->name('descuentos.store');
        Route::get('/{descuento}/edit', [DescuentoController::class, 'edit'])->name('descuentos.edit');
        Route::put('/{descuento}', [DescuentoController::class, 'update'])->name('descuentos.update');
        Route::get('/{descuento}/destroy', [DescuentoController::class, 'destroy'])->name('descuentos.destroy');
    });

    Route::prefix('categorias')->group(function () {
        Route::get('/', [CategoriaController::class, 'index'])->middleware('privilege:categorias.index')->name('categorias.index');
        Route::get('/create', [CategoriaController::class, 'create'])->middleware('privilege:categorias.create')->name('categorias.create');
        Route::post('/', [CategoriaController::class, 'store'])->middleware('privilege:categorias.store')->name('categorias.store');
        Route::get('/{categoria}/show', [CategoriaController::class, 'show'])->middleware('privilege:categorias.show')->name('categorias.show');
        Route::get('/{categoria}/edit', [CategoriaController::class, 'edit'])->middleware('privilege:categorias.edit')->name('categorias.edit');
        Route::put('/{categoria}', [CategoriaController::class, 'update'])->middleware('privilege:categorias.update')->name('categorias.update');
        Route::get('/{categoria}/destroy', [CategoriaController::class, 'destroy'])->middleware('privilege:categorias.destroy')->name('categorias.destroy');
    });

    Route::prefix('productos')->group(function () {
        Route::get('/', [ProductoController::class, 'index'])->middleware('privilege:productos.index')->name('productos.index');
        Route::get('/create', [ProductoController::class, 'create'])->middleware('privilege:productos.create')->name('productos.create');
        Route::post('/', [ProductoController::class, 'store'])->middleware('privilege:productos.store')->name('productos.store');
        Route::get('/{producto}/show', [ProductoController::class, 'show'])->middleware('privilege:productos.show')->name('productos.show');
        Route::get('/{producto}/edit', [ProductoController::class, 'edit'])->middleware('privilege:productos.edit')->name('productos.edit');
        Route::put('/{producto}', [ProductoController::class, 'update'])->middleware('privilege:productos.update')->name('productos.update');
        Route::get('/{producto}/toggle', [ProductoController::class, 'toggle'])->middleware('privilege:productos.toggle')->name('productos.toggle');
    });

    Route::prefix('ventas')->group(function () {
        Route::get('/', [VentaController::class, 'index'])->middleware('privilege:ventas.index')->name('ventas.index');
        Route::get('/create', [VentaController::class, 'create'])->middleware('privilege:ventas.create')->name('ventas.create');
        Route::post('/', [VentaController::class, 'store'])->middleware('privilege:ventas.store')->name('ventas.store');
        Route::get('/{venta}/show', [VentaController::class, 'show'])->middleware('privilege:ventas.show')->name('ventas.show');
        Route::put('/{venta}', [VentaController::class, 'update'])->middleware('privilege:ventas.update')->name('ventas.update');
        Route::get('/{venta}/destroy', [VentaController::class, 'destroy'])->middleware('privilege:ventas.destroy')->name('ventas.destroy');

        Route::post('/cliente', [VentaController::class, 'searchClient'])->middleware('privilege:ventas.searchClient')->name('ventas.searchClient');
        Route::post('/add-producto', [VentaController::class, 'addProducto'])->middleware('privilege:ventas.addProducto')->name('ventas.addProducto');
        Route::delete('/remove-producto', [VentaController::class, 'removeProducto'])->middleware('privilege:ventas.removeProducto')->name('ventas.removeProducto');
        Route::get('/getTotalCompra', [VentaController::class, 'getTotalCompra'])->middleware('privilege:ventas.getTotalCompra')->name('ventas.getTotalCompra');
        Route::post('/setPromocion', [VentaController::class, 'setPromocion'])->middleware('privilege:ventas.setPromocion')->name('ventas.setPromocion');
        Route::post('/getPuntosCliente', [VentaController::class, 'getPuntosCliente'])->middleware('privilege:ventas.getPuntosCliente')->name('ventas.getPuntosCliente');
        Route::post('/setUsoPuntos', [VentaController::class, 'setUsoPuntos'])->middleware('privilege:ventas.setUsoPuntos')->name('ventas.setUsoPuntos');
    });

    Route::prefix('reportes')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->middleware('privilege:reportes.index')->name('reportes.index');
        Route::get('/ventas', [ReporteController::class, 'ventas'])->middleware('privilege:reportes.ventas')->name('reportes.ventas');
        Route::get('/ventas/pdf', [ReporteController::class, 'ventasPDF'])->middleware('privilege:reportes.ventas.pdf')->name('reportes.ventas.pdf');
        Route::get('/usuarios', [ReporteController::class, 'usuarios'])->middleware('privilege:reportes.usuarios')->name('reportes.usuarios');
        Route::get('/usuarios/pdf', [ReporteController::class, 'usuariosPDF'])->middleware('privilege:reportes.usuarios.pdf')->name('reportes.usuarios.pdf');
        Route::get('/producto', [ReporteController::class, 'productos'])->middleware('privilege:reportes.productos')->name('reportes.productos');
        Route::get('/productos/pdf', [ReporteController::class, 'productosPDF'])->middleware('privilege:reportes.productos.pdf')->name('reportes.productos.pdf');
    });


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
