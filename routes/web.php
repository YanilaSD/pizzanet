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
use App\Http\Controllers\CanjePuntoController;
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
        Route::get('/', [PrivilegioController::class, 'index'])->middleware('privilege:ver-privilegios')->name('privilegios.index');
        Route::get('/create', [PrivilegioController::class, 'create'])->middleware('privilege:crear-privilegio')->name('privilegios.create');
        Route::post('/', [PrivilegioController::class, 'store'])->middleware('privilege:guardar-privilegio')->name('privilegios.store');
        Route::get('/{privilegio}/edit', [PrivilegioController::class, 'edit'])->middleware('privilege:editar-privilegio')->name('privilegios.edit');
        Route::put('/{privilegio}', [PrivilegioController::class, 'update'])->middleware('privilege:actualizar-privilegio')->name('privilegios.update');
        Route::get('/{id}/toggle', [PrivilegioController::class, 'toggle'])->middleware('privilege:activar-desactivar-privilegio')->name('privilegios.toggle');
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RolController::class, 'index'])->middleware('privilege:ver-roles')->name('roles.index');
        Route::get('/create', [RolController::class, 'create'])->middleware('privilege:crear-rol')->name('roles.create');
        Route::post('/', [RolController::class, 'store'])->middleware('privilege:guardar-rol')->name('roles.store');
        Route::get('/{rol}', [RolController::class, 'show'])->middleware('privilege:ver-detalle-rol')->name('roles.show');
        Route::get('/{rol}/edit', [RolController::class, 'edit'])->middleware('privilege:editar-rol')->name('roles.edit');
        Route::put('/{rol}', [RolController::class, 'update'])->middleware('privilege:actualizar-rol')->name('roles.update');
        Route::get('/{rol}/toggle', [RolController::class, 'toggle'])->middleware('privilege:activar-desactivar-rol')->name('roles.toggle');
    });

    Route::prefix('usuarios')->group(function () {
        Route::get('/', [UsuarioController::class, 'index'])->middleware('privilege:ver-usuarios')->name('usuarios.index');
        Route::get('/create', [UsuarioController::class, 'create'])->middleware('privilege:crear-usuario')->name('usuarios.create');
        Route::post('/', [UsuarioController::class, 'store'])->middleware('privilege:guardar-usuario')->name('usuarios.store');
        Route::get('/{usuario}', [UsuarioController::class, 'show'])->middleware('privilege:ver-detalle-usuario')->name('usuarios.show');
        Route::get('/{usuario}/edit', [UsuarioController::class, 'edit'])->middleware('privilege:editar-usuario')->name('usuarios.edit');
        Route::put('/{usuario}', [UsuarioController::class, 'update'])->middleware('privilege:actualizar-usuario')->name('usuarios.update');
        Route::get('/{id}/toggle', [UsuarioController::class, 'toggle'])->middleware('privilege:activar-desactivar-usuario')->name('usuarios.toggle');
        Route::get('/{usuario}/reset', [UsuarioController::class, 'reset_pwd'])->middleware('privilege:resetear-contrasena')->name('usuarios.reset');
        Route::post('/{usuario}/reset-f2a', [UsuarioController::class, 'reset_f2a'])->middleware('privilege:resetear-2fa')->name('usuarios.reset_f2a');
    });

    Route::prefix('tipo_pagos')->group(function () {
        Route::get('/', [TipoPagoController::class, 'index'])->middleware('privilege:ver-tipos-de-pago')->name('tipo_pagos.index');
        Route::get('/create', [TipoPagoController::class, 'create'])->middleware('privilege:crear-tipo-de-pago')->name('tipo_pagos.create');
        Route::post('/', [TipoPagoController::class, 'store'])->middleware('privilege:guardar-tipo-de-pago')->name('tipo_pagos.store');
        Route::get('/{tipo_pago}/edit', [TipoPagoController::class, 'edit'])->middleware('privilege:editar-tipo-de-pago')->name('tipo_pagos.edit');
        Route::put('/{tipo_pago}', [TipoPagoController::class, 'update'])->middleware('privilege:actualizar-tipo-de-pago')->name('tipo_pagos.update');
        Route::get('/{tipo_pago}/toggle', [TipoPagoController::class, 'toggle'])->middleware('privilege:activar-desactivar-tipo-de-pago')->name('tipo_pagos.toggle');
        Route::get('/{tipo_pago}/destroy', [TipoPagoController::class, 'destroy'])->middleware('privilege:eliminar-tipo-de-pago')->name('tipo_pagos.destroy');
    });

    Route::prefix('festividades')->group(function () {
        Route::get('/', [FestividadController::class, 'index'])->middleware('privilege:ver-festividades')->name('festividades.index');
        Route::get('/create', [FestividadController::class, 'create'])->middleware('privilege:crear-festividad')->name('festividades.create');
        Route::post('/', [FestividadController::class, 'store'])->middleware('privilege:guardar-festividad')->name('festividades.store');
        Route::get('/{festividad}/edit', [FestividadController::class, 'edit'])->middleware('privilege:editar-festividad')->name('festividades.edit');
        Route::put('/{festividad}', [FestividadController::class, 'update'])->middleware('privilege:actualizar-festividad')->name('festividades.update');
        Route::get('/{festividad}/destroy', [FestividadController::class, 'destroy'])->middleware('privilege:eliminar-festividad')->name('festividades.destroy');
    });

    Route::prefix('promociones')->group(function () {
        Route::get('/', [PromocionController::class, 'index'])->middleware('privilege:ver-promociones')->name('promociones.index');
        Route::get('/create', [PromocionController::class, 'create'])->middleware('privilege:crear-promocion')->name('promociones.create');
        Route::post('/', [PromocionController::class, 'store'])->middleware('privilege:guardar-promocion')->name('promociones.store');
        Route::get('/{promocion}/show', [PromocionController::class, 'show'])->middleware('privilege:ver-detalle-promocion')->name('promociones.show');
        Route::get('/{promocion}/edit', [PromocionController::class, 'edit'])->middleware('privilege:editar-promocion')->name('promociones.edit');
        Route::put('/{promocion}', [PromocionController::class, 'update'])->middleware('privilege:actualizar-promocion')->name('promociones.update');
        Route::get('/{promocion}/destroy', [PromocionController::class, 'destroy'])->middleware('privilege:eliminar-promocion')->name('promociones.destroy');
    });

    Route::prefix('clientes')->group(function () {
        Route::get('/', [ClienteController::class, 'index'])->middleware('privilege:ver-clientes')->name('clientes.index');
        Route::get('/create', [ClienteController::class, 'create'])->middleware('privilege:crear-cliente')->name('clientes.create');
        Route::post('/', [ClienteController::class, 'store'])->middleware('privilege:guardar-cliente')->name('clientes.store');
        Route::get('/{cliente}/show', [ClienteController::class, 'show'])->middleware('privilege:ver-detalle-cliente')->name('clientes.show');
        Route::get('/{cliente}/edit', [ClienteController::class, 'edit'])->middleware('privilege:editar-cliente')->name('clientes.edit');
        Route::get('/{cliente}/canjes', [HistorialController::class, 'index'])->middleware('privilege:canjear-puntos-cliente')->name('clientes.canjes');
        Route::put('/{cliente}', [ClienteController::class, 'update'])->middleware('privilege:actualizar-cliente')->name('clientes.update');
        Route::get('/{cliente}/destroy', [ClienteController::class, 'destroy'])->middleware('privilege:eliminar-cliente')->name('clientes.destroy');
    });

    Route::prefix('canjes')->group(function () {
        Route::get('/', [CanjePuntoController::class, 'index'])->middleware('privilege:ver-canjes')->name('canjes.index');
        Route::get('/create', [CanjePuntoController::class, 'create'])->middleware('privilege:crear-canje')->name('canjes.create');
        Route::post('/', [CanjePuntoController::class, 'store'])->middleware('privilege:guardar-canje')->name('canjes.store');
        Route::get('/{canje}/edit', [CanjePuntoController::class, 'edit'])->middleware('privilege:editar-canje')->name('canjes.edit');
        Route::put('/{canje}', [CanjePuntoController::class, 'update'])->middleware('privilege:actualizar-canje')->name('canjes.update');
        Route::get('/{canje}/toggle', [CanjePuntoController::class, 'toggle'])->middleware('privilege:activar-desactivar-canje')->name('canjes.toggle');
    });

    Route::prefix('categorias')->group(function () {
        Route::get('/', [CategoriaController::class, 'index'])->middleware('privilege:ver-categorias')->name('categorias.index');
        Route::get('/create', [CategoriaController::class, 'create'])->middleware('privilege:crear-categoria')->name('categorias.create');
        Route::post('/', [CategoriaController::class, 'store'])->middleware('privilege:guardar-categoria')->name('categorias.store');
        Route::get('/{categoria}/show', [CategoriaController::class, 'show'])->middleware('privilege:ver-detalle-categoria')->name('categorias.show');
        Route::get('/{categoria}/edit', [CategoriaController::class, 'edit'])->middleware('privilege:editar-categoria')->name('categorias.edit');
        Route::put('/{categoria}', [CategoriaController::class, 'update'])->middleware('privilege:actualizar-categoria')->name('categorias.update');
        Route::get('/{categoria}/destroy', [CategoriaController::class, 'destroy'])->middleware('privilege:eliminar-categoria')->name('categorias.destroy');
    });

    Route::prefix('productos')->group(function () {
        Route::get('/', [ProductoController::class, 'index'])->middleware('privilege:ver-productos')->name('productos.index');
        Route::get('/create', [ProductoController::class, 'create'])->middleware('privilege:crear-producto')->name('productos.create');
        Route::post('/', [ProductoController::class, 'store'])->middleware('privilege:guardar-producto')->name('productos.store');
        Route::get('/{producto}/show', [ProductoController::class, 'show'])->middleware('privilege:ver-detalle-producto')->name('productos.show');
        Route::get('/{producto}/edit', [ProductoController::class, 'edit'])->middleware('privilege:editar-producto')->name('productos.edit');
        Route::put('/{producto}', [ProductoController::class, 'update'])->middleware('privilege:actualizar-producto')->name('productos.update');
        Route::put('/{producto}/inventario', [ProductoController::class, 'updateInventario'])->middleware('privilege:actualizar-producto')->name('productos.inventario.update');
        Route::get('/{producto}/destroy', [ProductoController::class, 'destroy'])->middleware('privilege:eliminar-producto')->name('productos.destroy');
    });

    Route::prefix('ventas')->group(function () {
        Route::get('/', [VentaController::class, 'index'])->middleware('privilege:ver-ventas')->name('ventas.index');
        Route::get('/create', [VentaController::class, 'create'])->middleware('privilege:crear-venta')->name('ventas.create');
        Route::get('/{venta}/show', [VentaController::class, 'show'])->middleware('privilege:ver-detalle-venta')->name('ventas.show');
        Route::post('/{venta}/destroy', [VentaController::class, 'destroy'])->middleware('privilege:eliminar-venta')->name('ventas.destroy');
        Route::post('/cliente', [VentaController::class, 'searchClient'])->middleware('privilege:buscar-cliente-en-venta')->name('ventas.searchClient');
    });

    Route::prefix('reportes')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->middleware('privilege:ver-reportes')->name('reportes.index');
        Route::get('/ventas', [ReporteController::class, 'ventas'])->middleware('privilege:reporte-ventas')->name('reportes.ventas');
        Route::get('/ventas/pdf', [ReporteController::class, 'ventasPDF'])->middleware('privilege:exportar-ventas-pdf')->name('reportes.ventas.pdf');
        Route::get('/usuarios', [ReporteController::class, 'usuarios'])->middleware('privilege:reporte-usuarios')->name('reportes.usuarios');
        Route::get('/usuarios/pdf', [ReporteController::class, 'usuariosPDF'])->middleware('privilege:exportar-usuarios-pdf')->name('reportes.usuarios.pdf');
        Route::get('/producto', [ReporteController::class, 'productos'])->middleware('privilege:reporte-productos')->name('reportes.productos');
        Route::get('/productos/pdf', [ReporteController::class, 'productosPDF'])->middleware('privilege:exportar-productos-pdf')->name('reportes.productos.pdf');
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
