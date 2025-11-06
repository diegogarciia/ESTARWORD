<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NaveEstelarController;
use App\Http\Controllers\PilotoController;
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\PlanetaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ControladorLocal;

Route::post('/registro', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('usuarios', UserController::class)->middleware('solo:administrador');

    Route::apiResource('naves-estelares', NaveEstelarController::class)->only(['index', 'show'])->middleware('varios:administrador,gestor');
    Route::apiResource('naves-estelares', NaveEstelarController::class)->only(['store', 'update', 'destroy'])->middleware('solo:administrador');

    Route::apiResource('planetas', PlanetaController::class)->only(['index', 'show'])->middleware('varios:administrador,gestor');
    Route::apiResource('planetas', PlanetaController::class)->only(['store', 'update', 'destroy'])->middleware('solo:administrador');

    Route::apiResource('pilotos', PilotoController::class)->only(['index', 'show'])->middleware('varios:administrador,gestor');
    Route::apiResource('pilotos', PilotoController::class)->only(['store', 'update', 'destroy'])->middleware('solo:administrador');

    Route::apiResource('mantenimientos', MantenimientoController::class)->only(['index', 'show'])->middleware('varios:administrador,gestor');
    Route::apiResource('mantenimientos', MantenimientoController::class)->only(['store', 'update', 'destroy'])->middleware('solo:administrador'); 

    Route::post('/naves-estelares/{nave_estelar}/asignarPiloto', [PilotoController::class, 'asignarPiloto'])->middleware('varios:administrador,gestor');
    Route::post('/naves-estelares/{nave_estelar}/quitarPiloto', [PilotoController::class, 'quitarPiloto'])->middleware('varios:administrador,gestor');

    Route::get('/mantenimientos/buscar/fecha', [MantenimientoController::class, 'listarPorFecha'])->middleware('varios:administrador,gestor');
    Route::get('/naves-sin-piloto', [PilotoController::class, 'navesSinPiloto'])->middleware('varios:administrador,gestor');
    Route::get('/pilotos/historialAsignaciones', [PilotoController::class, 'historialAsignacionesPiloto'])->middleware('varios:administrador,gestor');
    Route::get('/pilotos/asignacionesActuales', [PilotoController::class, 'asignacionesActualesPiloto'])->middleware('varios:administrador,gestor');

    Route::post('/subirlocal', [ControladorLocal::class,'subirImagenLocal'])->middleware('solo:administrador');
    Route::get('/mostrar/{filename}', [ControladorLocal::class, 'mostrarImagen'])->middleware('solo:administrador');
    Route::get('/descargar/{filename}', [ControladorLocal::class, 'descargarImagen'])->middleware('solo:administrador');

});