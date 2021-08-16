<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReceta extends Model
{
    use HasFactory;

    protected $table = 'receta_user';
}
