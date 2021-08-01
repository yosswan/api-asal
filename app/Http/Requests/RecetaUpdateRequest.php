<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecetaUpdateRequest extends FormRequest
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
        return [
            'ingredientes.*.id' => 'required_with:ingredientes.*.cantidad|exists:ingredientes,id',
            'ingredientes.*.cantidad' => 'required_with:ingredientes.*.id|numeric|integer|min:1',
        ];
    }
}
