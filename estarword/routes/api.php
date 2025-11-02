<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NaveEstelarController;
use App\Http\Controllers\PilotoController;
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\PlanetaController;
use App\Http\Controllers\UserController; 

// AUTHCONTROLLER -> LOGIN, REGISTER, LOGOUT
// EL RESTO DE CONTROLLERS -> INDEX, STORE, SHOW, UPDATE, DESTROY

// --- RUTAS PÚBLICAS ---
Route::post('/registro', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- RUTAS PROTEGIDAS (Requieren un token válido) ---
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // --- Gestión de Usuarios (SOLO ADMIN) ---
    // (index, show, update, destroy)
    Route::apiResource('usuarios', UserController::class)->middleware('solo:administrador');

    // --- CRUD de Naves Estelares ---
    // (index, show) -> Accesible para todos los roles
    Route::apiResource('naves-estelares', NaveEstelarController::class)->only(['index', 'show'])->parameters(['naves-estelares' => 'nave_estelar']);

    // (store, update, destroy) -> SOLO ADMIN
    Route::apiResource('naves-estelares', NaveEstelarController::class)->only(['store', 'update', 'destroy'])->middleware('solo:administrador')->parameters(['naves-estelares' => 'nave_estelar']);

    // --- CRUD de Planetas ---
    // (index, show) -> Accesible para todos los roles
    Route::apiResource('planetas', PlanetaController::class)->only(['index', 'show']);

    // (store, update, destroy) -> SOLO ADMIN
    Route::apiResource('planetas', PlanetaController::class)->only(['store', 'update', 'destroy'])->middleware('solo:administrador');

    // --- CRUD de Pilotos ---
    // (index, show) -> Accesible para todos los roles
    Route::apiResource('pilotos', PilotoController::class)->only(['index', 'show']);

    // (store, update, destroy) -> SOLO ADMIN (Asumiendo que crear perfiles de piloto es de admin)
    Route::apiResource('pilotos', PilotoController::class)->only(['store', 'update', 'destroy'])->middleware('solo:administrador');

    // --- CRUD de Mantenimientos ---
    // (index, show) -> Accesible para todos los roles
    Route::apiResource('mantenimientos', MantenimientoController::class)->only(['index', 'show']);

    // (store, update, destroy) -> ADMIN y GESTOR
    Route::apiResource('mantenimientos', MantenimientoController::class)->only(['store', 'update', 'destroy'])->middleware('varios:administrador,gestor');

    // --- RUTAS PERSONALIZADAS Y REPORTES (Listados) ---
    
    // --- Acciones de Asignación (ADMIN y GESTOR) ---
    Route::post('/naves-estelares/{id_nave_estelar}/asignarPiloto', [PilotoController::class, 'asignarPiloto'])->middleware('varios:administrador,gestor');
    Route::post('/naves-estelares/{id_nave_estelar}/quitarPiloto', [PilotoController::class, 'quitarPiloto'])->middleware('varios:administrador,gestor');

    // --- Listados y Reportes (ACCESIBLES POR AMBOS ROLES) ---
    Route::get('/mantenimientos/buscar/fecha', [MantenimientoController::class, 'listarPorFecha']);
    Route::get('/naves-sin-piloto', [PilotoController::class, 'navesSinPiloto']);
    Route::get('/pilotos/historialAsignaciones', [PilotoController::class, 'historialAsignacionesPiloto']);
    Route::get('/pilotos/asignacionesActuales', [PilotoController::class, 'asignacionesActualesPiloto']);

});