<?php

namespace Modules\Core\Console\Commands;

use Nwidart\Modules\Commands\GeneratorCommand;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class PresenterMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;


    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-presenter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new presenter class for the specified module';

    public function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/presenter.stub', [
            'NAMESPACE'         => $this->getClassNamespace($module),
            'CLASS'             => $this->getClass(),
            'LOWER_NAME'        => $module->getLowerName(),
        ]))->render();
    }

    public function getDestinationFilePath()
    {
        $path       = $this->laravel['modules']->getModulePath($this->getModuleName());

        $presenterPath = GenerateConfigReader::read('presenter');

        return $path . $presenterPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    protected function getFileName()
    {
        return \Illuminate\Support\Str::studly($this->argument('name'));
    }

    public function getDefaultNamespace() : string
    {
        return $this->laravel['modules']->config('paths.generator.presenter.path', 'Presenters');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the presenter.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }
}
