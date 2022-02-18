<?php
namespace Modules\Core\Console\Generators;

use Illuminate\Support\Str;
use Nwidart\Modules\Generators\ModuleGenerator as Generator;

class ModuleGenerator extends Generator
{
    /**
     * Generate some resources.
     */
    public function generateResources()
    {
        $this->console->call('module:make-seed', [
            'name' => $this->getName(),
            'module' => $this->getName(),
            '--master' => true,
        ]);

        $this->console->call('module:make-provider', [
            'name' => $this->getName() . 'ServiceProvider',
            'module' => $this->getName(),
            '--master' => true,
        ]);

        $this->console->call('module:make-controller', [
            'controller' => $this->getName() . 'Controller',
            'module' => $this->getName(),
            '--api' => true,
        ]);

        $this->console->call('module:make-model', [
            'model' => Str::singular($this->getName()),
            'module' => $this->getName(),
            /*'-m' => true,*/
        ]);

        $this->console->call('module:make-repo', [
            'name' => 'Eloquent'.Str::singular($this->getName()),
            'module' => $this->getName(),
        ]);

        $this->console->call('module:make-repo-interface', [
            'name' => Str::singular($this->getName()).'Interface',
            'module' => $this->getName(),
        ]);

        $this->console->call('module:make-facade', [
            'name' => 'Facade',
            'module' => $this->getName(),
        ]);

        $this->console->call('module:make-request', [
            'name' => 'FormRequest',
            'module' => $this->getName(),
        ]);

        $this->console->call('module:make-presenter', [
            'name' => 'ModulePresenter',
            'module' => $this->getName(),
        ]);

        $this->console->call('module:route-provider', [
            'module' => $this->getName(),
        ]);
    }

    /**
     * Get the module name in lower Singular case.
     *
     * @return string
     */
    protected function getSingularLowerNameReplacement()
    {
        return Str::singular($this->getLowerNameReplacement());
    }

    /**
     * Get the module name in lower Singular case.
     *
     * @return string
     */
    protected function getSingularStudlyNameReplacement()
    {
        return Str::singular($this->getStudlyNameReplacement());
    }

}
