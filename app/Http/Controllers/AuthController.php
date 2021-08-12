<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Registro de usuario
     */
    public function signUp(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
            'sexo' => 'required|in:H,M',
            'edad' => 'required|numeric|integer|min:18',
            'peso' => 'required|numeric|min:0',
            'actividad_fisica' => 'required|in:1,2,3,4,5,6',
            'calorias' => 'numeric|integer|nullable',
            'grasas' => 'required_with:calorias|numeric|integer|nullable',
            'proteinas' => 'required_with:calorias|numeric|integer|nullable',
            'carbohidratos' => 'required_with:calorias|numeric|integer|nullable',
        ]);

        $array = [
            'name' => $request->nombre,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'sexo' => $request->sexo,
            'edad' => $request->edad,
            'peso' => $request->peso,
            'actividad_fisica' => $request->actividad_fisica,
        ];

        if(!empty($request->calorias)){
            $array = array_merge($array, [
                'calorias' => $request->calorias,
                'grasas' => $request->grasas,
                'proteinas' => $request->proteinas,
                'carbohidratos' => $request->carbohidratos,
            ]);    
        } else{
            $array = array_merge($array, $this->calcular_requerimiento_nutricional(
                $request->edad, $request->sexo, $request->peso, $request->actividad_fisica
            ));
        }

        User::create($array);

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    /**
     * Inicio de sesión y creación de token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
        ]);
    }

    /**
     * Cierre de sesión (anular el token)
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Obtener el objeto User como json
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
