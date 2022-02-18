<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Support\Str;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Commands\ProviderMakeCommand as GeneratorCommand;

class ProviderMakeCommand extends GeneratorCommand
{
    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $stub = $this->option('master') ? 'scaffold/provider' : 'provider';

        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/' . $stub . '.stub', [
            'NAMESPACE'         => $this->getClassNamespace($module),
            'CLASS'             => $this->getClass(),
            'LOWER_NAME'        => $module->getLowerName(),
            'MODULE'            => $this->getModuleName(),
            'NAME'              => $this->getFileName(),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'SINGULAR_MODULENAME' => Str::singular($module->getStudlyName()),
            'MODULE_NAMESPACE'  => $this->laravel['modules']->config('namespace'),
            'PATH_VIEWS'        => GenerateConfigReader::read('views')->getPath(),
            'PATH_LANG'         => GenerateConfigReader::read('lang')->getPath(),
            'PATH_CONFIG'       => GenerateConfigReader::read('config')->getPath(),
            'MIGRATIONS_PATH'   => GenerateConfigReader::read('migration')->getPath(),
            'FACTORIES_PATH'   => GenerateConfigReader::read('factory')->getPath(),
        ]))->render();
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name'));
    }
}
