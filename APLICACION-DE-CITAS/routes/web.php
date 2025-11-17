<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\PacienteController; 
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; 


/*
|--------------------------------------------------------------------------
| Rutas Públicas y de Bienvenida
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| GRUPO DE RUTAS PROTEGIDAS (Solo necesitan autenticación)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Rutas Base de Perfil de Laravel
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    
    // -----------------------------------------------------------------
    // 1. DASHBOARD PRINCIPAL (TODO REDIRIGE A ADMIN)
    // -----------------------------------------------------------------
    
    // Ruta directa del Dashboard de Admin (se mantiene)
    Route::get('/admin/dashboard', function () {
        // Aunque se accede directamente, la lógica de acceso debería estar en el middleware
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // Redirección principal: Si estás autenticado, siempre vas al dashboard del Admin.
    // Esta es la ruta a la que redirige Laravel Breeze después de login/register.
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');
    
    // Eliminamos las rutas específicas para otros roles (recepción, medico, paciente)


    // -----------------------------------------------------------------
    // 2. MÓDULOS ADMINISTRATIVOS (Acceso total para el usuario autenticado)
    // -----------------------------------------------------------------

    Route::resource('medicos', MedicoController::class);
    Route::resource('pacientes', PacienteController::class); 
    Route::resource('citas', CitaController::class);
    
    // Ruta específica para cambiar el estado de una cita
    Route::put('citas/{cita}/estado', [CitaController::class, 'cambiarEstado'])->name('citas.cambiar_estado');

});


// Mantiene las rutas de autenticación de Breeze (login, register, etc.)
require __DIR__.'/auth.php';