<?php namespace Modules\Products\Repositories;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Repositories\RepositoriesAbstract;

class EloquentProduct extends RepositoriesAbstract implements ProductInterface
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}
