<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\RecetaRepository;
use App\Repositories\RecetaRepositoryEloquent;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /*$this->app->bind(\App\Repositories\IngredienteRepository::class, \App\Repositories\IngredienteRepositoryEloquent::class);
        $this->app->bind(RecetaRepository::class, RecetaRepositoryEloquent::class);*/
        $this->app->bind(\App\Repositories\ComidaRepository::class, \App\Repositories\ComidaRepositoryEloquent::class);
        //:end-bindings:
    }
}
