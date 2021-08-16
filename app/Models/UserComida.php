<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Entities\Receta;

class UserComida extends Model
{
    use HasFactory;

    protected $table = 'user_comida';

    public $timestamps = false;

    public function recetas() {
        return $this->belongsToMany(Receta::class, 'usercomida_receta', 'usercomida_id');
    }
}
