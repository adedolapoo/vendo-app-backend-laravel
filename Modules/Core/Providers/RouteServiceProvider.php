<?php

namespace Modules\Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

abstract class RouteServiceProvider extends ServiceProvider
{
    /**
     * The root namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $namespace = '';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->loadApiRoutes();
    }

    /**
     * @return string
     */
    abstract protected function getApiRoutes();


    /**
     * @param Router $router
     * @return void
     */
    private function loadApiRoutes()
    {
        $routes = $this->getApiRoutes();
        $this->routes(function () use ($routes){
            $api = Route::prefix('api/v1')
                ->middleware('api')
                ->namespace($this->namespace);
            if(file_exists($routes)){
                $api->group(function() use ($routes){
                    require $routes;
                });
            }
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            //return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
