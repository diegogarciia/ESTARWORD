<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Piloto;
use Illuminate\Support\Facades\Storage;

/**
 * php artisan storage:link
 * Esto hace que la carpeta storage/app/public sea pública.
 */
class ControladorLocal extends Controller
{

    public function subirImagenLocal(Request $request){
        $messages = [
            'max' => 'El campo se excede del tamaño máximo',
            'required' => 'Falta el archivo',
            'mimes' => 'Tipo no soportado',
            'piloto_id.required' => 'Debes proporcionar un ID de piloto.',
            'piloto_id.exists' => 'El piloto con ese ID no existe.',
        ];

        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'piloto_id' => 'required|integer|exists:pilotos,id', 
        ], $messages);

        if ($validator->fails()){
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            
            $id = $request->input('piloto_id');

            $piloto = Piloto::find($id);
            
            if (is_null($piloto)) {
                return response()->json(["success"=>false, "message" => "Piloto no encontrado"], 404);
            }
            
            $file = $request->file('image');
            $filename = uniqid('img_') . '.' . $file->getClientOriginalExtension(); 
            
            $path = $file->storeAs('perfiles', $filename, 'public');
            
            $piloto->imagen_piloto = $path; 
            $piloto->save();
            
            return response()->json([
                "success"=>true,
                "message" => "Imagen actualizada para el piloto: " . $piloto->nombre,
                "data"=>$piloto,
                "url_publica" => Storage::url($path) 
            ]);
        }
        
        return response()->json(['error' => 'No se recibió ningún archivo.'], 400);
    }

    public function mostrarImagen($filename)
    {
        $path = 'public/perfiles/' . $filename;

        if (!Storage::exists($path)) {
            return response()->json(['error' => 'Imagen no encontrada'], 404);
        }

        return response()->file(Storage::path($path));
    }

    public function descargarImagen($filename)
    {
        $path = 'public/perfiles/' . $filename;

        if (!Storage::exists($path)) {
            return response()->json(['error' => 'Imagen no encontrada'], 404);
        }

        return response()->download(Storage::path($path));
    }
}