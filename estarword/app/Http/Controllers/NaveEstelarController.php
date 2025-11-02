<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nave_Estelar;

class NaveEstelarController extends Controller
{
    public function index()
    {
        $naves_estelares = Nave_Estelar::all();
        return response()->json($naves_estelares,200);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $nave_estelar = Nave_Estelar::create($input);
        return response()->json(["success"=>true,"data"=>$parte, "message" => "Created"],201);
    }

    public function show($id)
    {
        $nave_estelar = Nave_Estelar::find($id);
        if (is_null($nave_estelar)) {
            return response()->json("Nave estelar no encontrada",404);
        }
        return response()->json(["success"=>true,"data"=>$nave_estelar, "message" => "Retrieved"]);
    }

    public function update($id, Request $request)
    {
        $input = $request->all();

        $nave_estelar = Nave_Estelar::find($id);
        if (is_null($nave_estelar)) {
            return response()->json(["success"=>false, "message" => "Not found"],404);
        }
        else {
            $nave_estelar->nombre = $input['nombre'];
            $nave_estelar->modelo = $input['modelo'];
            $nave_estelar->tripulacion = $input['tripulacion'];
            $nave_estelar->pasajeros = $input['pasajeros'];
            $nave_estelar->clase_nave = $input['clase_nave'];
            $nave_estelar->save();

            return response()->json(["success"=>true,"data"=>$nave_estelar, "message" => "Updated"]);
        }
    }

    public function destroy($id)
    {
        $nave_estelar = Nave_Estelar::find($id);
        if (is_null($nave_estelar)) {
            return response()->json(["success"=>false, "message" => "Not found"],404);
        }
        else {
            $nave_estelar->delete();
            return response()->json(["success"=>true,"data"=>$nave_estelar, "message" => "Deleted"],200);
        }
    }
}
