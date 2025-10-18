<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

use App\Http\Controllers\VentaController;
use App\Http\Controllers\PrivilegioController;

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
