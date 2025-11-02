<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Piloto;
use App\Models\Nave_Estelar;

class PilotoController extends Controller
{
    public function index()
    {
        $pilotos = Piloto::all();
        return response()->json($pilotos,200);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $piloto = Piloto::create($input);
        return response()->json(["success"=>true,"data"=>$piloto, "message" => "Created"],201);
    }

    public function show($id)
    {
        $piloto = Piloto::find($id);
        if (is_null($piloto)) {
            return response()->json("Piloto no encontrado",404);
        }
        return response()->json(["success"=>true,"data"=>$piloto, "message" => "Retrieved"]);
    }

    public function update($id, Request $request)
    {
        $input = $request->all();

        $piloto = Piloto::find($id);
        if (is_null($piloto)) {
            return response()->json(["success"=>false, "message" => "Not found"],404);
        }
        else {
            $piloto->nombre = $input['nombre'];
            $piloto->altura = $input['altura'];
            $piloto->anio_nacimiento = $input['anio_nacimiento'];
            $piloto->genero = $input['genero'];
            $piloto->save();

            return response()->json(["success"=>true,"data"=>$piloto, "message" => "Updated"]);
        }
    }

    public function updateImagenPiloto($id, Request $request)
    {
        $input = $request->all();

        $piloto = Piloto::find($id);
        if (is_null($piloto)) {
            return response()->json(["success"=>false, "message" => "Not found"],404);
        }
        else {
            $piloto->imagen_piloto = $input['imagen_piloto'];
            $piloto->save();

            return response()->json(["success"=>true,"data"=>$piloto, "message" => "Updated"]);
        }
    }

    public function destroy($id)
    {
        $piloto = Piloto::find($id);
        if (is_null($piloto)) {
            return response()->json(["success"=>false, "message" => "Not found"],404);
        }
        else {
            $piloto->delete();
            return response()->json(["success"=>true,"data"=>$piloto, "message" => "Deleted"],200);
        }
    }

    public function asignarPiloto(Request $request, Nave_Estelar $nave_estelar)
    {
        // ✨ CORRECCIÓN: Estandarizado a 'piloto_id'
        $validator = Validator::make($request->all(), [
            'piloto_id' => 'required|integer|exists:pilotos,id',
            'fecha_inicio' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // ✨ CORRECCIÓN: Consulta más robusta para evitar ambigüedades
        // Comprueba si este piloto ('pilotos.id') ya está en esta nave
        // y su asignación sigue activa ('fecha_fin' es null en la pivote).
        $asignacionActiva = $nave_estelar->pilotos()
            ->where('pilotos.id', $request->piloto_id)
            ->wherePivotNull('fecha_fin') // 'wherePivotNull' es más explícito
            ->exists();

        if ($asignacionActiva) {
            return response()->json(['message' => 'Este piloto ya tiene una asignación activa en esta nave.'], 409); // 409 Conflict
        }

        // Usamos attach para añadir el registro en la tabla pivote
        // ✨ CORRECCIÓN: Estandarizado a 'piloto_id'
        $nave_estelar->pilotos()->attach($request->piloto_id, [
            'fecha_inicio' => $request->fecha_inicio,
        ]);

        return response()->json(['message' => 'Piloto asignado correctamente a la nave.'], 200);
    }

    /**
     * Desasigna un piloto de una nave, estableciendo la fecha de fin.
     * POST /naves-estelares/{nave_estelar}/quitarPiloto
     */
    public function quitarPiloto(Request $request, Nave_Estelar $nave_estelar)
    {
        // ✨ CORRECCIÓN: Estandarizado a 'piloto_id'
        $validator = Validator::make($request->all(), [
            'piloto_id' => 'required|integer|exists:pilotos,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Usamos updateExistingPivot para actualizar el registro en la tabla pivote
        // Solo actualizamos la asignación que no tiene fecha de fin (la activa)
        // ✨ CORRECCIÓN: Estandarizado a 'piloto_id'
        $resultado = $nave_estelar->pilotos()
            ->wherePivotNull('fecha_fin') // Más explícito
            ->updateExistingPivot($request->piloto_id, [
                'fecha_fin' => now(), // now() establece la fecha y hora actual
            ]);

        if ($resultado) {
            return response()->json(['message' => 'Piloto desasignado correctamente.'], 200);
        }

        return response()->json(['message' => 'No se encontró una asignación activa para este piloto en esta nave.'], 404);
    }

    /**
     * Lista todas las naves que no tienen ningún piloto asignado actualmente.
     * (Esta función ya estaba correcta)
     * GET /naves-sin-piloto
     */
    public function navesSinPiloto()
    {
        $naves = NaveEstelar::whereDoesntHave('pilotos', function ($query) {
            $query->whereNull('fecha_fin');
        })->get();

        return response()->json($naves);
    }

    /**
     * Muestra todos los pilotos que han tenido al menos una asignación (historial completo).
     * (Esta función ya estaba correcta)
     * GET /pilotos/historialAsignaciones
     */
    public function historialAsignacionesPiloto()
    {
        $pilotos = Piloto::has('navesEstelares')
            ->with('navesEstelares') // Carga las naves y la info de la tabla pivote
            ->get();

        return response()->json($pilotos);
    }

    /**
     * Muestra solo los pilotos que están asignados a una nave AHORA MISMO.
     * (Esta función ya estaba correcta)
     * GET /pilotos/asignacionesActuales
     */
    public function asignacionesActualesPiloto()
    {
        // 'whereHas' filtra los pilotos que cumplen la condición en la relación.
        $pilotos = Piloto::whereHas('navesEstelares', function ($query) {
            $query->whereNull('fecha_fin'); // La asignación está activa
        })
        ->with(['navesEstelares' => function ($query) {
            // También filtramos las naves que cargamos para mostrar solo las activas
            $query->whereNull('fecha_fin');
        }])
        ->get();

        return response()->json($pilotos);
    }

}