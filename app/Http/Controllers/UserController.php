<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
            'password' => 'required|string|min:4',
            'new_password' => 'string|min:4',
        ]);

        try{
            if($validator->fails()){
                return $this->sendError($validator->errors());
            }

            $user = $request->user();
            if(!Hash::check($request->password, $user->password)){
                return $this->sendError('Contraseña incorrecta');
            }

            if(!empty($request->nombre)){
                $array['password'] = bcrypt($request->new_password);
            }
            if(!empty($request->nombre)){
                $array['name'] = $request->nombre;
            }
            if(!empty($request->email)){
                $array['email'] = $request->email;
            }
            $user->update($array);
            $user->refresh();

            return $this->sendResponse($user, 'Usuario actualizado con éxito');

        } catch (Exception $e){
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
