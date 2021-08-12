<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Ingrediente.
 *
 * @package namespace App\Entities;
 */
class Ingrediente extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'calorias',
        'carbohidratos',
        'grasas',
        'proteinas',
        'categoria',
    ];

}
