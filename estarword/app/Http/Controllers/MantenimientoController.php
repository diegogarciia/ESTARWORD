<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mantenimiento;

class MantenimientoController extends Controller
{
    public function index()
    {
        $mantenimientos = Mantenimiento::all();
        return response()->json($partes,200);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $mantenimiento = Mantenimiento::create($input);
        return response()->json(["success"=>true,"data"=>$mantenimiento, "message" => "Created"],201);
    }

    public function show($id)
    {
        $mantenimiento = Mantenimiento::find($id);
        if (is_null($mantenimiento)) {
            return response()->json("Mantenimiento no encontrado",404);
        }
        return response()->json(["success"=>true,"data"=>$mantenimiento, "message" => "Retrieved"]);
    }

    public function update($id, Request $request)
    {
        $input = $request->all();

        $mantenimiento = Mantenimiento::find($id);
        if (is_null($mantenimiento)) {
            return response()->json(["success"=>false, "message" => "Not found"],404);
        }
        else {
            $mantenimiento->id_nave_estelar = $input['id_nave_estelar'];
            $mantenimiento->fecha_mantenimiento = $input['fecha_mantenimiento'];
            $mantenimiento->descripcion = $input['descripcion'];
            $mantenimiento->coste = $input['coste'];
            $mantenimiento->save();

            return response()->json(["success"=>true,"data"=>$mantenimiento, "message" => "Updated"]);
        }
    }

    public function destroy($id)
    {
        $mantenimiento = Mantenimiento::find($id);
        if (is_null($mantenimiento)) {
            return response()->json(["success"=>false, "message" => "Not found"],404);
        }
        else {
            $mantenimiento->delete();
            return response()->json(["success"=>true,"data"=>$mantenimiento, "message" => "Deleted"],200);
        }
    }

    public function listarPorFecha(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Usamos la columna 'fecha_mantenimiento' de tu modelo
        $mantenimientos = Mantenimiento::whereBetween('fecha_mantenimiento', [
                $request->fecha_inicio,
                $request->fecha_fin
            ])
            ->with('naveEstelar') // Carga la relación con la nave
            ->orderBy('fecha_mantenimiento', 'desc')
            ->get();

        return response()->json($mantenimientos);
    }

}
