<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserComida;
use App\Models\User;
use App\Entities\Comida;
use App\Models\UserReceta;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'string',
            'email' => 'string|email|unique:users',
            'new_password' => 'string|min:4',
            'password' => 'required_with:new_password|string|min:4',
            'sexo' => 'in:H,M',
            'fecha_nacimiento' => 'date_format:d-m-Y',
            'peso' => 'numeric|min:0',
            'actividad_fisica' => 'in:1,2,3,4,5,6',
            'calorias' => 'numeric|integer|nullable',
            'grasas' => 'required_with:calorias|numeric|integer|nullable',
            'proteinas' => 'required_with:calorias|numeric|integer|nullable',
            'carbohidratos' => 'required_with:calorias|numeric|integer|nullable',
        ]);

        try{
            if($validator->fails()){
                return $this->sendError($validator->errors());
            }

            $user = $request->user();
            $array = [];

            if(!empty($request->fecha_nacimiento)){
                $fecha = Carbon::createFromFormat('d-m-Y', $request->fecha_nacimiento);
                $fecha_actual = Carbon::now();
                $edad = $fecha_actual->diffInYears($fecha);
                if($edad < 18){
                    return $this->sendError('La edad debe ser mayor a 17 a??os');
                }
                $array['fecha_nacimiento'] = $fecha;
            }

            if(!empty($request->password) && !Hash::check($request->password, $user->password)){
                return $this->sendError('Contrase??a incorrecta');
            }
            if(!empty($request->new_password)){
                $array['password'] = bcrypt($request->new_password);
            }
            
            if(!empty($request->nombre)){
                $array['name'] = $request->nombre;
            }

            $lista = ['sexo', 'peso', 'actividad_fisica'];
            foreach ($lista as $value) {
                if(!empty($request->$value)){
                    $array[$value] = $request->$value;
                }
            }

            if(!empty($request->calorias)){
                $array = array_merge($array, [
                    'calorias' => $request->calorias,
                    'grasas' => $request->grasas,
                    'proteinas' => $request->proteinas,
                    'carbohidratos' => $request->carbohidratos,
                ]);    
            } else{
                $array = array_merge($array, $this->calcular_requerimiento_nutricional(
                    $edad, $request->sexo, $request->peso, $request->actividad_fisica
                ));
            }
            
            $user->update($array);
            $user->refresh();

            return $this->sendResponse($user, 'Usuario actualizado con ??xito');

        } catch (Exception $e){
            return $this->sendError($e->getMessage());
        }
    }

    public function agregar_comida(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'recetas' => 'required|array',
                'recetas.*' => 'required|exists:recetas,id',
                'tipo' => 'required|in:desayuno,almuerzo,merienda,cena',
            ]);
            if($validator->fails()){
                return $this->sendError($validator->errors());
            }

            $fecha = Carbon::now()->format('Y-m-d');

            $user = $request->user();
            $array = [];
            foreach ($request->recetas as $value) {
                $array[$value] = [
                    'tipo' => $request->tipo,
                    'fecha' => $fecha
                ];
            }
            $user->comidas()->attach($array);

            return $this->sendResponse('', 'Comida agregada');

        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function eliminar_comida(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'receta_id' => 'required|exists:recetas,id',
                'tipo' => 'required|in:desayuno,almuerzo,merienda,cena',
            ]);
            if($validator->fails()){
                return $this->sendError($validator->errors());
            }

            $fecha = Carbon::now()->format('Y-m-d');

            $user = $request->user();
            $userreceta = UserReceta::where('user_id', $user->id)
            ->where('receta_id', $request->receta_id)
            ->where('fecha', $fecha)
            ->where('tipo', $request->tipo)->get();

            if($userreceta->isEmpty()){
                return $this->sendError('Comida no registrada');
            }else{
                $userreceta = $userreceta[0];
            }
            $deleted = $userreceta->delete();

            return $this->sendResponse($deleted, 'Comida eliminada');

        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function obtener_comidas(Request $request){
        try {
            $user = $request->user();
            $comidas = $user->comidas;
            $array = [];
            $fechas = $comidas->groupBy('pivot.fecha');
            foreach ($fechas as $key => $value) {
                $comidas = $value->groupBy('pivot.tipo');
                foreach ($comidas as $tipo => $comida) {
                    $calorias = $grasas = $proteinas = $carbohidratos = 0;
                    foreach ($comida as $receta) {
                        foreach ($receta->ingredientes as $ingrediente) {
                            $calorias += $ingrediente->calorias/100*$ingrediente->pivot->cantidad;
                            $grasas += $ingrediente->grasas/100*$ingrediente->pivot->cantidad;
                            $proteinas += $ingrediente->proteinas/100*$ingrediente->pivot->cantidad;
                            $carbohidratos += $ingrediente->carbohidratos/100*$ingrediente->pivot->cantidad;
                        }
                    }
                    $array[$key][$tipo] = [
                        'recetas' => $comida->all(),
                        'calorias' => round($calorias),
                        'carbohidratos' => round($carbohidratos, 1),
                        'proteinas' => round($proteinas, 1),
                        'grasas' => round($grasas, 1),
                    ];
                }
            }

            return $this->sendResponse($array, 'Comidas');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function obtener_comidas_actuales(Request $request){
        try {
            $user = $request->user();
            $fecha = Carbon::now()->format('Y-m-d');
            return $this->sendResponse($user->comidas()->where('fecha', $fecha)->get(), 'Comidas');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function obtener_requerimientos(Request $request){
        try {
            $user = $request->user();
            
            $array = [
                'calorias' => $user->calorias,
                'grasas' => $user->grasas,
                'carbohidratos' => $user->carbohidratos,
                'proteinas' => $user->proteinas
            ];
            return $this->sendResponse($array, 'Requerimiento nutricional');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function obtener_consumo_actual(Request $request){
        try {
            $user = $request->user();
            $fecha = Carbon::now()->format('Y-m-d');
            $calorias = $grasas = $proteinas = $carbohidratos = 0;
            $comidas = $user->comidas()->where('fecha', $fecha)->get();
            foreach ($comidas as $comida) {
                foreach ($comida->ingredientes as $ingrediente) {
                    $calorias += $ingrediente->calorias/100*$ingrediente->pivot->cantidad;
                    $grasas += $ingrediente->grasas/100*$ingrediente->pivot->cantidad;
                    $proteinas += $ingrediente->proteinas/100*$ingrediente->pivot->cantidad;
                    $carbohidratos += $ingrediente->carbohidratos/100*$ingrediente->pivot->cantidad;
                }
            }
            $array = [
                'calorias' => round($calorias),
                'carbohidratos' => round($carbohidratos, 1),
                'proteinas' => round($proteinas, 1),
                'grasas' => round($grasas, 1),
            ];
            return $this->sendResponse($array, 'Informaci??n nutricional del consumo actual');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:4',
        ]);

        try{
            if($validator->fails()){
                return $this->sendError($validator->errors());
            }
            $user = $request->user();
            if(!Hash::check($request->password, $user->password)){
                return $this->sendError('Contrase??a incorrecta');
            }
            $user->token()->revoke();
            $delete = $user->delete();
            return $this->sendResponse($delete, 'Usuario eliminado');
        } catch (Exception $e){
            return $this->sendError($e->getMessage());
        }
    }
}
