<?php

namespace Modules\Core\Facades;


use Illuminate\Support\Facades\Facade;

class MyApp extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'myapp';
    }
}
