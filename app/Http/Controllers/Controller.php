<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Notifications\Action;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function sendResponse($data, $message)
    {
        return Response::json([
            'success' => true,
            'data'    => $data,
            'message' => $message,
        ]);
    }

    /**
     * @param $error
     * @param int $code
     * @return \Illuminate\Support\Facades\Response
     */
    protected function sendError($error, $code = 404)
    {
        return Response::json([
            'success' => false,
            'message' => $error,
        ], $code);
    }

    protected function calcular_requerimiento_nutricional(int $edad, $sexo, int $peso, int $actividad_fisica){
        $calorias = $this->calcular_calorias($edad, $sexo, $peso, $actividad_fisica);
        $proteinas = $peso;
        $factor_proteinas = 1;
        $grasas = $peso;
        $factor_grasas = 1;
        $precision = true;

        $ciclo = true;

        while ($ciclo) {
            $factor_carbohidratos = ($calorias - $proteinas*4 - $grasas*9) / (4*$peso);
            if($factor_carbohidratos >= 4 && $factor_carbohidratos <= 6){
                $ciclo = false;
            } elseif ($factor_carbohidratos < 4) {
                if($factor_grasas > 0.8 || $factor_proteinas > 0.8){
                    if($factor_proteinas == $factor_grasas){
                        $factor_grasas -= 0.1;
                        $grasas = $factor_grasas * $peso;
                    } else{
                        $factor_proteinas -= 0.1;
                        $proteinas = $factor_proteinas * $peso;
                    }
                } elseif ($actividad_fisica < 6) {
                    $actividad_fisica++;
                    $calorias = $this->calcular_calorias($edad, $sexo, $peso, $actividad_fisica);
                } else{
                    $precision = false;
                    $ciclo = false;
                }
            } else {
                if($factor_grasas < 1.2 || $factor_proteinas < 1.2){
                    if($factor_proteinas == $factor_grasas){
                        $factor_proteinas += 0.1;
                        $proteinas = $factor_proteinas * $peso;
                    } else{
                        $factor_grasas += 0.1;
                        $grasas = $factor_grasas * $peso;
                    }
                } elseif ($actividad_fisica > 1) {
                    $actividad_fisica--;
                    $calorias = $this->calcular_calorias($edad, $sexo, $peso, $actividad_fisica);
                } else{
                    $precision = false;
                    $ciclo = false;
                }
            }
        }
        
        return [
            'calorias' => round($calorias),
            'proteinas' => round($proteinas, 1),
            'grasas' => round($grasas, 1),
            'carbohidratos' => round($factor_carbohidratos * $peso, 1),
            'precision' => $precision,
        ];
    }

    private function calcular_calorias(int $edad, $sexo, int $peso, int $actividad_fisica){
        $calorias1 = (24 + $actividad_fisica) * $peso;
        $calorias2 = $this->formula_por_sexo($sexo, $edad, $actividad_fisica, $peso);
        return ($calorias1 + $calorias2) / 2;
    }

    private function formula_por_sexo($sexo, int $edad, int $actividad_fisica, int $peso){
        if($sexo === 'M'){
            return $this->formula_por_edad_mujer($edad, $actividad_fisica, $peso);
        } elseif ($sexo === 'H') {
            return $this->formula_por_edad_hombre($edad, $actividad_fisica, $peso);
        } else {
            throw new Exception('Sexo inválido');
        }
    }

    private function formula_por_edad_hombre(int $edad, int $actividad_fisica, $peso){
        $valor = 0;
        if(in_array($edad, range(18,30))){
            $valor = 15.3 * $peso + 679;
        } elseif (in_array($edad, range(31,60))) {
            $valor = 11.6 * $peso + 879;
        } elseif ($edad > 60) {
            $valor = 13.5 * $peso + 487;
        } else {
            throw new Exception('Edad fuera del rango aceptado');
        }
        return $valor * $this->constante_actividad_fisica_hombre($actividad_fisica);
    }

    private function formula_por_edad_mujer(int $edad, int $actividad_fisica, $peso){
        $valor = 0;
        if(in_array($edad, range(18,30))){
            $valor = 14.7 * $peso + 496;
        } elseif (in_array($edad, range(31,60))) {
            $valor = 8.7 * $peso + 829;
        } elseif ($edad > 60) {
            $valor = 10.5 * $peso + 596;
        } else {
            throw new Exception('Edad fuera del rango aceptado');
        }
        return $valor * $this->constante_actividad_fisica_mujer($actividad_fisica);
    }

    private function constante_actividad_fisica_hombre(int $actividad_fisica){
        if(in_array($actividad_fisica, [1,2])){
            return 1.55;
        } elseif (in_array($actividad_fisica, [3,4])) {
            return 1.78;
        } elseif (in_array($actividad_fisica, [5,6])) {
            return 2.1;
        } else {
            throw new Exception('Nivel de actividad física no admitido');
        }
    }

    private function constante_actividad_fisica_mujer(int $actividad_fisica){
        if(in_array($actividad_fisica, [1,2])){
            return 1.56;
        } elseif (in_array($actividad_fisica, [3,4])) {
            return 1.64;
        } elseif (in_array($actividad_fisica, [5,6])) {
            return 1.82;
        } else {
            throw new Exception('Nivel de actividad física no admitido');
        }
    }
}
