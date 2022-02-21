<?php namespace Modules\Products\Repositories;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Repositories\RepositoriesAbstract;

class EloquentProduct extends RepositoriesAbstract implements ProductInterface
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function allPaginatedForSeller($seller_id = null,array $with = [])
    {
        $query = $this->make($with);

        $request = request();

        $per_page  = $request->get('per_page',20);

        if(!empty($seller_id)){
            $query = $query->where('seller_id',$seller_id);
        }

        if(method_exists($this->model,'scopeModelQuery')){
            $query = $query->modelQuery($request);
        }

        if(method_exists($this->model,'scopeSearch')){
            $query = $query->search($request);
        }

        if(method_exists($this->model,'scopeSort')){
            $query = $query->sort($request);
        }

        $query =  empty($per_page) ? $query->get() : $query->paginate($per_page);

        return $query;
    }
}
