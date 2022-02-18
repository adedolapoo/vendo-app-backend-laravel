<?php

namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Console\Commands\ControllerMakeCommand;
use Modules\Core\Console\Commands\FacadeMakeCommand;
use Modules\Core\Console\Commands\ModuleMakeCommand;
use Modules\Core\Console\Commands\PresenterMakeCommand;
use Modules\Core\Console\Commands\ProviderMakeCommand;
use Modules\Core\Console\Commands\RepositoryInterfaceMakeCommand;
use Modules\Core\Console\Commands\RepositoryMakeCommand;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     * The available commands
     *
     * @var array
     */
    protected $commands = [
        ProviderMakeCommand::class,
        ControllerMakeCommand::class,
        ModuleMakeCommand::class,
        RepositoryMakeCommand::class,
        RepositoryInterfaceMakeCommand::class,
        FacadeMakeCommand::class,
        PresenterMakeCommand::class
    ];

    /**
     * Register the commands.
     */
    public function register()
    {
        $this->commands($this->commands);
    }

    /**
     * @return array
     */
    public function provides()
    {
        $provides = $this->commands;

        return $provides;
    }
}
