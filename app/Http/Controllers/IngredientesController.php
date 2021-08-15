<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\IngredienteCreateRequest;
use App\Http\Requests\IngredienteUpdateRequest;
use App\Imports\IngredienteImport;
use App\Repositories\IngredienteRepository;
use App\Validators\IngredienteValidator;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use App\Entities\Ingrediente;

/**
 * Class IngredientesController.
 *
 * @package namespace App\Http\Controllers;
 */
class IngredientesController extends Controller
{
    /**
     * @var IngredienteRepository
     */
    protected $repository;


    /**
     * IngredientesController constructor.
     *
     * @param IngredienteRepository $repository
     */
    public function __construct(IngredienteRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ingredientes = $this->repository->all();

        return $this->sendResponse($ingredientes->toArray(), 'Ingredientes');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  IngredienteCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(IngredienteCreateRequest $request)
    {
        try {

            $ingrediente = $this->repository->create($request->all());

            return $this->sendResponse($ingrediente, 'Ingrediente created.');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function importar_excel(Request $request)
    {
        try {
            if(!$request->hasFile('ingredientes')){
                return $this->sendError('Archivo no encontrado');
            }
            $file = $request->ingredientes->path();
            Excel::import(new IngredienteImport, $file);

            return $this->sendResponse('', 'Ingredientes creados.');
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
    public function show($id)
    {
        $ingrediente = $this->repository->find($id);

        return $this->sendResponse($ingrediente, '');
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
     * @param  IngredienteUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(IngredienteUpdateRequest $request, $id)
    {
        try {
            $ingrediente = $this->repository->update($request->all(), $id);

            return $this->sendResponse($ingrediente, 'Ingrediente updated.');
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
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        return $this->sendResponse($deleted, 'Ingrediente deleted.');
    }

    public function destroy_all()
    {
        
        try {
            $deleted = Ingrediente::truncate();

            return $this->sendResponse($deleted, 'Ingredientes deleted.');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
