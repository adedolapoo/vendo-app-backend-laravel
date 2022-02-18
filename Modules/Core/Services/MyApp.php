<?php
namespace Modules\Core\Services;

use Exception;
use Illuminate\Support\Facades\File;
use Nwidart\Modules\Facades\Module;

/**
 * LangSwitcher
 */
class MyApp
{
    private $model;

    /**
     * Set model
     *
     * @param Model $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * Get Homepage URI
     *
     * @return string
     */
    public function homepage()
    {
        $uri = '/';

        return $uri;
    }



    /**
     * Get public url when no model loaded
     *
     * @return string
     */
    public function getPublicUrl()
    {
        return '/';
    }

    /**
     * Build public link
     *
     * @param array $attributes
     * @return string
     */
    public function publicLink(array $attributes = array())
    {
        $url = $this->getPublicUrl();
        $title = ucfirst(trans('core::global.view website', array(), null));
        return HTML::link($url, $title, $attributes);
    }

    /**
     * Build admin link
     *
     * @param array $attributes
     * @return string
     */
    public function adminLink(array $attributes = array())
    {
        $url = route('dashboard');
        $title = ucfirst(trans('global.admin side', array(), null, Config::get('myapp.admin_locale')));
        if ($this->model) {
            if (! $this->model->id) {
                $url = $this->model->indexUrl();
            } else {
                $url = $this->model->editUrl();
            }
            $url .= '?locale=' . App::getLocale();
        }
        return HTML::link($url, $title, $attributes);
    }

    /**
     * Build admin or public link
     *
     * @param array $attributes
     * @return string
     */
    public function otherSideLink(array $attributes = array())
    {
        if ($this->isAdmin()) {
            return $this->publicLink($attributes);
        }
        return $this->adminLink($attributes);
    }

    /**
     * Check if we are on back office
     *
     * @return boolean true if we are on backend
     */
    public function isAdmin()
    {
        if (Request::segment(1) == 'admin') {
            return true;
        }
        return false;
    }

    /**
     * Get all modules for permissions table.
     *
     * @return array
     */
    public function modules()
    {
        $modules = config('myapp.modules');
        ksort($modules);

        return $modules;
    }

    /**
     * Get all modules for a select/options.
     *
     * @return array
     */
    public function getModulesForSelect()
    {
        $modules = Module::allEnabled();
        $options = ['' => ''];
        foreach ($modules as $module) {
            $options[$module->getLowerName()] = trans($module->getName().'::global.name');
        }
        $options = array_only($options,config('myapp.linkable_to_page'));
        asort($options);

        return $options;
    }

    /**
     * Check if there is a logo.
     *
     * @return bool
     */
    public function hasLogo()
    {
        return (bool) config('myapp.image');
    }

    /**
     * Get title from settings.
     *
     * @return string
     */
    public function title()
    {
        return config('myapp.'.config('app.locale').'.website_title');
    }

    /**
     * Return the first page found linked to a module.
     *
     * @param string $module
     *
     * @return \TypiCMS\Modules\Pages\Models\Page
     */
    public function getPageLinkedToModule($module = null)
    {
        $pages = $this->getPagesLinkedToModule($module);

        return reset($pages);
    }

    /**
     * Return an array of pages linked to a module.
     *
     * @param string $module
     *
     * @return array
     */
    public function getPagesLinkedToModule($module = null)
    {
        $module = strtolower($module);
        $routes = app('myapp.routes');
        $pages = [];
        foreach ($routes as $page) {
            if ($page->module == $module) {
                $pages[] = $page;
            }
        }

        return $pages;
    }

    /**
     * List templates files from directory.
     *
     * @return array
     */
    public function templates($directory = 'views/modules/pages/public')
    {
        $templates = [];
        try {
            $files = File::allFiles(resource_path($directory));
        } catch (Exception $e) {
            //$files = File::allFiles(resource_path('Modules/Pages/Resources/views/public'));
            $files = [];
        }
        foreach ($files as $key => $file) {
            $name = str_replace('.blade.php', '', $file->getRelativePathname());
            if ($name[0] != '_' && $name != 'master') {
                $templates[$name] = ucfirst($name);
            }
        }

        return ['' => ''] + $templates;
    }
}
