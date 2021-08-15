<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\IngredienteRepository;
use App\Entities\Ingrediente;
use App\Validators\IngredienteValidator;

/**
 * Class IngredienteRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class IngredienteRepositoryEloquent extends BaseRepository implements IngredienteRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Ingrediente::class;
    }
    
}
