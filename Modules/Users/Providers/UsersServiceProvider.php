<?php

namespace Modules\Users\Providers;

use Illuminate\Support\ServiceProvider;

class UsersServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

    /**
     * Boot the application events.
     */
	public function boot()
	{
		$this->registerConfig();
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$driver = config('users.driver', 'Sanctum');

		$this->app->bind(
			'Modules\Users\Repositories\UserInterface',
			"Modules\\Users\\Repositories\\{$driver}\\{$driver}User"
		);

		$this->app->bind(
			'Modules\Users\Repositories\AuthenticationInterface',
			"Modules\\Users\\Repositories\\{$driver}\\{$driver}Authentication"
		);
	}

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('users.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'users'
        );
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

}
