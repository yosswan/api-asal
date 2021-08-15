<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\RecetaRepository;
use App\Entities\Receta;
use App\Validators\RecetaValidator;

/**
 * Class RecetaRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class RecetaRepositoryEloquent extends BaseRepository implements RecetaRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Receta::class;
    }
    
}
