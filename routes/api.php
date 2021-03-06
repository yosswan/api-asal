<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IngredientesController;
use App\Http\Controllers\RecetasController;
use App\Http\Controllers\UserController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'signUp']);

    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
    });
});
Route::group([
    'middleware' => 'auth:api'
], function() {
    Route::apiResource('ingredientes', IngredientesController::class);
    Route::apiResource('recetas', RecetasController::class);
    /** Ruta oculta XD */
    Route::post('ingredientes/importar_excel', [IngredientesController::class, 'importar_excel']);
    Route::post('ingredientes/truncate', [IngredientesController::class, 'destroy_all']);
    Route::put('user', [UserController::class, 'update']);
    Route::delete('user', [UserController::class, 'destroy']);
    Route::post('user/comida', [UserController::class, 'agregar_comida']);
    Route::get('user/comida', [UserController::class, 'obtener_comidas']);
    Route::delete('user/comida', [UserController::class, 'eliminar_comida']);
    Route::get('user/comida/actual', [UserController::class, 'obtener_comidas_actuales']);
    Route::get('user/consumo/actual', [UserController::class, 'obtener_consumo_actual']);
    Route::get('user/requerimiento', [UserController::class, 'obtener_requerimientos']);
});