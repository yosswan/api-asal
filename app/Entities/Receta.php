<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Receta.
 *
 * @package namespace App\Entities;
 */
class Receta extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre'];

    public function ingredientes() {
        return $this->belongsToMany(Ingrediente::class)->withPivot('cantidad');
    }

    public $timestamps = false;

}
