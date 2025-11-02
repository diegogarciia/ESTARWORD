<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Planeta;

class PlanetaController extends Controller
{
    public function index()
    {
        $planetas = Planeta::all();
        return response()->json($planetas,200);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $planeta = Planeta::create($input);
        return response()->json(["success"=>true,"data"=>$planeta, "message" => "Created"],201);
    }

    public function show($id)
    {
        $planeta = Planeta::find($id);
        if (is_null($planeta)) {
            return response()->json("Planeta no encontrado",404);
        }
        return response()->json(["success"=>true,"data"=>$planeta, "message" => "Retrieved"]);
    }

    public function update($id, Request $request)
    {
        $input = $request->all();

        $planeta = Planeta::find($id);
        if (is_null($planeta)) {
            return response()->json(["success"=>false, "message" => "Not found"],404);
        }
        else {
            $planeta->nombre = $input['nombre'];
            $planeta->periodo_rotacion = $input['periodo_rotacion'];
            $planeta->poblacion = $input['poblacion'];
            $planeta->clima = $input['clima'];
            $planeta->save();

            return response()->json(["success"=>true,"data"=>$planeta, "message" => "Updated"]);
        }
    }

    public function destroy($id)
    {
        $planeta = Planeta::find($id);
        if (is_null($parte)) {
            return response()->json(["success"=>false, "message" => "Not found"],404);
        }
        else {
            $planeta->delete();
            return response()->json(["success"=>true,"data"=>$planeta, "message" => "Deleted"],200);
        }
    }
}
