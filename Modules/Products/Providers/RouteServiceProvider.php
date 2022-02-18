<?php

namespace Modules\Products\Providers;

use Modules\Core\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The root namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $namespace = 'Modules\Products\Http\Controllers';

    /**
     * @return string
     */
    protected function getApiRoutes()
    {
        return __DIR__ . '/../Http/routes.php';
    }
}
