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

    Route::get('/usuarios', [UserController::class, 'index'])->middleware('solo:administrador');
    Route::get('/usuario/{id}', [UserController::class, 'show'])->middleware('solo:administrador');
    Route::put('/actualizarUsuario/{id}', [UserController::class, 'update'])->middleware('solo:administrador');
    Route::post('/generarUsuario', [UserController::class, 'store'])->middleware('solo:administrador');
    Route::delete('/eliminarUsuario/{id}', [UserController::class, 'destroy'])->middleware('solo:administrador');

    Route::get('/navesEstelares', [NaveEstelarController::class, 'index'])->middleware('varios:administrador,gestor');
    Route::get('/navesEstelar/{id}', [NaveEstelarController::class, 'show'])->middleware('varios:administrador,gestor');

    Route::put('/modificarNaveEstelar/{id}', [UserController::class, 'update'])->middleware('solo:administrador');
    Route::post('/generarNaveEstelar', [UserController::class, 'store'])->middleware('solo:administrador');
    Route::delete('/eliminarNaveEstelar/{id}', [UserController::class, 'destroy'])->middleware('solo:administrador');

    Route::get('/planetas', [PlanetaController::class, 'index'])->middleware('varios:administrador,gestor');
    Route::get('/planeta/{id}', [PlanetaController::class, 'show'])->middleware('varios:administrador,gestor');

    Route::put('/modificarPlaneta/{id}', [PlanetaController::class, 'update'])->middleware('solo:administrador');
    Route::post('/generarPlaneta', [PlanetaController::class, 'store'])->middleware('solo:administrador');
    Route::delete('/eliminarPlaneta/{id}', [PlanetaController::class, 'destroy'])->middleware('solo:administrador');

    Route::get('/pilotos', [PilotoController::class, 'index'])->middleware('varios:administrador,gestor');
    Route::get('/piloto/{id}', [PilotoController::class, 'show'])->middleware('varios:administrador,gestor');

    Route::put('/modificarPiloto/{id}', [PilotoController::class, 'update'])->middleware('solo:administrador');
    Route::post('/generarPiloto', [PilotoController::class, 'store'])->middleware('solo:administrador');
    Route::delete('/eliminarPiloto/{id}', [PilotoController::class, 'destroy'])->middleware('solo:administrador');

    Route::get('/mantenimientos', [MantenimientoController::class, 'index'])->middleware('varios:administrador,gestor');
    Route::get('/mantenimiento/{id}', [MantenimientoController::class, 'show'])->middleware('varios:administrador,gestor');

    Route::put('/modificarMantenimiento/{id}', [MantenimientoController::class, 'update'])->middleware('solo:administrador');
    Route::post('/generarMantenimiento', [MantenimientoController::class, 'store'])->middleware('solo:administrador');
    Route::delete('/eliminarMantenimiento/{id}', [MantenimientoController::class, 'destroy'])->middleware('solo:administrador');

    Route::post('/naves-estelares/{id_nave_estelar}/asignarPiloto', [PilotoController::class, 'asignarPiloto'])->middleware('varios:administrador,gestor');
    Route::post('/naves-estelares/{id_nave_estelar}/quitarPiloto', [PilotoController::class, 'quitarPiloto'])->middleware('varios:administrador,gestor');

    Route::get('/mantenimientos/buscar/fecha', [MantenimientoController::class, 'listarPorFecha']);
    Route::get('/naves-sin-piloto', [PilotoController::class, 'navesSinPiloto']);
    Route::get('/pilotos/historialAsignaciones', [PilotoController::class, 'historialAsignacionesPiloto']);
    Route::get('/pilotos/asignacionesActuales', [PilotoController::class, 'asignacionesActualesPiloto']);

    Route::post('/subirlocal', [ControladorLocal::class,'subirImagenLocal'])->middleware('solo:administrador');
    Route::get('/mostrar/{filename}', [ControladorLocal::class, 'mostrarImagen'])->middleware('solo:administrador');
    Route::get('/descargar/{filename}', [ControladorLocal::class, 'descargarImagen'])->middleware('solo:administrador');

});