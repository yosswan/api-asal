<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IngredienteUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $array = [
            'Cereales',
            'Carnes',
            'Pescados',
            'Huevos',
            'Lácteos',
            'Leguminosas',
            'Tubérculos y Raíces',
            'Legumbres',
            'Frutas',
            'Bebidas Alcohólicas',
            'Nueces y Afines',
            'Alimentos preparados',
            'Alimentos Varios'
        ];
        return [
            'kilocalorias' => 'numeric|integer|min:1',
            'carbohidratos' => 'numeric|integer|min:1',
            'grasas' => 'numeric|integer|min:1',
            'proteinas' => 'numeric|integer|min:1',
            'categoria' => Rule::in($array)
        ];
    }
}
