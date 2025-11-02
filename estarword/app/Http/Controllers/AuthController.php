<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $auth = Auth::user();
            // return $auth;
            //$tokenResult = $auth->createToken('LaravelSanctumAuth');
            $tokenResult = $auth->createToken('LaravelSanctumAuth', [$auth->rol]);

            // Actualizar expiración
            // $hours = (int) env('SANCTUM_EXPIRATION_HOURS', 2);
            // $tokenResult->accessToken->expires_at = now()->addHours($hours);
            // $tokenResult->accessToken->save();

            $success = [
                'id'         => $auth->id,
                'name'       => $auth->name,
                'token'      => $tokenResult->plainTextToken,
                'expires_at' => $tokenResult->accessToken->expires_at == null ? null :  $tokenResult->accessToken->expires_at->toDateTimeString()
            ];

            return response()->json(["success"=>true,"data"=>$success, "message" => "User logged-in!"]);
        }
        else{
            return response()->json("Unauthorised",204);
        }
    }

    public function register(Request $request)
    {
        // Validación robusta de los datos de entrada
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', 
            'rol' => 'required|string|in:administrador,gestor' 
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Error de validación",
                "errors" => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'rol' => $request->rol,
        ]);

        // Crear el token asignando su rol como 'ability'
        // Esto es crucial para la autorización.
        $token = $user->createToken('api-token', [$user->rol])->plainTextToken;

        $success = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'rol' => $user->rol,
            'token' => $token,
        ];

        return response()->json([
            "success" => true,
            "data" => $success,
            "message" => "Usuario registrado con éxito."
        ], 201); // 201 Created
    }

     /**
     * Por defecto los tokens de Sanctum no expiran. Se puede modificar esto añadiendo una cantidad en minutos a la variable 'expiration' en el archivo de config/sanctum.php.
     */
     public function logout(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $cantidad = Auth::user()->tokens()->delete();
            return response()->json(["success"=>true, "message" => "Tokens Revoked: ".$cantidad],200);
        }
        else {
            return response()->json("Unauthorised",204);
        }

    }
}
