<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\RecetaCreateRequest;
use App\Http\Requests\RecetaUpdateRequest;
use App\Repositories\RecetaRepository;
use App\Validators\RecetaValidator;
use Exception;

/**
 * Class RecetasController.
 *
 * @package namespace App\Http\Controllers;
 */
class RecetasController extends Controller
{
    /**
     * @var RecetaRepository
     */
    protected $repository;

    /**
     * RecetasController constructor.
     *
     * @param RecetaRepository $repository
     * @param RecetaValidator $validator
     */
    public function __construct(RecetaRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $recetas = $request->user()->recetas;

        return $this->sendResponse($recetas, 'Recetas');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RecetaCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(RecetaCreateRequest $request)
    {
        try {
            $recetum = $request->user()->recetas()->create($request->all());
            foreach ($request->ingredientes as $value) {
                $recetum->ingredientes()->attach($value['id'], ['cantidad' => $value['cantidad']]);
            }
            

            return $this->sendResponse($recetum, 'Receta created.');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $id)
    {
        try {
            $recetum = $request->user()->recetas()->findOrFail($id);
            $recetum->ingredientes;
            return $this->sendResponse($recetum, 'Receta');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RecetaUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(RecetaUpdateRequest $request, $id)
    {
        try {
            $request->user()->recetas()->findOrFail($id);
            $recetum = $this->repository->update($request->all(), $id);
            if(!empty($request->ingredientes)){
                $recetum->ingredientes()->detach();
                foreach ($request->ingredientes as $value) {
                    $recetum->ingredientes()->attach($value['id'], ['cantidad' => $value['cantidad']]);
                }
            }

            return $this->sendResponse($recetum, 'Receta updated.');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $receta = $request->user()->recetas()->findOrFail($id);
            $receta->ingredientes()->detach();
            $deleted = $this->repository->delete($id);

            return $this->sendResponse($deleted, 'Receta deleted.');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
