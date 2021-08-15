<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;
use App\Repositories\RecetaRepository;
use App\Repositories\RecetaRepositoryEloquent;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (env('REDIRECT_HTTPS')) {
            $this->app['request']->server->set('HTTPS', true);
        }
        $this->app->bind(\App\Repositories\IngredienteRepository::class, \App\Repositories\IngredienteRepositoryEloquent::class);
        $this->app->bind(RecetaRepository::class, RecetaRepositoryEloquent::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        if (env('REDIRECT_HTTPS')) {
            $url->formatScheme('https://');
        }
        Schema::defaultStringLength(191);
    }
}
