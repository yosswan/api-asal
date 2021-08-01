<?php

namespace App\Imports;

use App\Entities\Ingrediente;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class IngredienteImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Ingrediente([
            'nombre' => $row['nombre'],
            'kilocalorias' => $row['kilocalorias'],
            'carbohidratos' => $row['carbohidratos'],
            'grasas' => $row['grasas'],
            'proteinas' => $row['proteinas'],
            'categoria' => $row['categoria'],
        ]);
    }
}
