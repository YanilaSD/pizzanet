<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

use App\Http\Controllers\VentaController;
use App\Http\Controllers\PrivilegioController;
use App\Http\Controllers\RolController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');

    //Privilegio
    Route::get('/privilegios', [PrivilegioController::class, 'index'])->name('privilegios.index');
    Route::get('/privilegios/create', [PrivilegioController::class, 'create'])->name('privilegios.create');
    Route::post('/privilegios', [PrivilegioController::class, 'store'])->name('privilegios.store');
    Route::get('/privilegios/{privilegio}/edit', [PrivilegioController::class, 'edit'])->name('privilegios.edit');
    Route::put('/privilegios/{privilegio}', [PrivilegioController::class, 'update'])->name('privilegios.update');
    Route::get('/privilegios/{id}/toggle', [PrivilegioController::class, 'toggle'])->name('privilegios.toggle');

    Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RolController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RolController::class, 'store'])->name('roles.store');
    Route::get('/roles/{rol}/edit', [RolController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{rol}', [RolController::class, 'update'])->name('roles.update');
    Route::get('/roles/{rol}', [RolController::class, 'show'])->name('roles.show');
    Route::get('/roles/{id}/toggle', [RolController::class, 'toggle'])->name('roles.toggle');
    Route::get('roles/{rol}/toggle', [RolController::class, 'toggle'])->name('roles.toggle');


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
