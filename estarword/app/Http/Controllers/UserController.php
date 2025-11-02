<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        return response()->json($usuarios,200);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $usuario = User::create($input);
        return response()->json(["success"=>true,"data"=>$usuario, "message" => "Created"],201);
    }

    public function show($id)
    {
        $usuario = Mantenimiento::find($id);
        if (is_null($usuario)) {
            return response()->json("Usuario no encontrado",404);
        }
        return response()->json(["success"=>true,"data"=>$usuario, "message" => "Retrieved"]);
    }

    public function update($id, Request $request)
    {
        $input = $request->all();

        $usuario = User::find($id);
        if (is_null($usuario)) {
            return response()->json(["success"=>false, "message" => "Not found"],404);
        }
        else {
            $usuario->name = $input['name'];
            $usuario->email = $input['email'];
            $usuario->password = $input['password'];
            $usuario->rol = $input['rol'];
            $usuario->save();

            return response()->json(["success"=>true,"data"=>$usuario, "message" => "Updated"]);
        }
    }

    public function destroy($id)
    {
        $usuario = User::find($id);
        if (is_null($usuario)) {
            return response()->json(["success"=>false, "message" => "Not found"],404);
        }
        else {
            $usuario->delete();
            return response()->json(["success"=>true,"data"=>$usuario, "message" => "Deleted"],200);
        }
    }
}
