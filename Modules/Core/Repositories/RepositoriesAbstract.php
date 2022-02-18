<?php

namespace Modules\Core\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Input;

abstract class RepositoriesAbstract implements RepositoryInterface
{
    /**
     * @var
     */
    protected $model;

    /**
     * Get empty model
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get table name
     *
     * @return string
     */
    public function getTable()
    {
        return $this->model->getTable();
    }

    /**
     * @param array $with
     * @return mixed
     */
    public function make(array $with = [])
    {
        return $this->model->with($with);
    }

    /**
     * Find a single entity
     *
     * @param array $with
     */
    public function getFirst(array $with = [])
    {
        $query = $this->make($with);
        return $query->first();
    }

    /**
     * Find a single entity by key value
     *
     * @param string $key
     * @param string $value
     * @param array $with
     */
    public function getFirstBy($key, $value, array $with = [])
    {
        $query = $this->make($with);

        return $query->where($key, '=', $value)->first();
    }

    /**
     * Retrieve model by id
     * regardless of status
     *
     * @param int $id model ID
     * @param array $with
     * @return Model
     */
    public function byId($id, array $with = [])
    {
        $query = $this->make($with)->where('id', $id);

        return $query->firstOrFail();
    }

    /**
     * Get all models
     *
     * @param  array $with Eager load related models
     * @return Collection
     */
    public function all(array $with = [])
    {
        $query = $this->make($with);

        // Query ORDER BY
        if(method_exists($this->model,'scopeOrder')) {
            $query->order();
        }

        return $query->get();
    }

    /**
     * Get all models by key/value
     *
     * @param  string $key
     * @param  string $value
     * @param  array $with
     * @return Collection
     */
    public function allBy($key, $value, array $with = [])
    {
        $query = $this->make($with);

        $query->where($key, $value);

        // Query ORDER BY
        $query->order();

        return $query->get();
    }

    /**
     * Get latest models
     *
     * @param  integer $number number of items to take
     * @param  array $with array of related items
     * @return Collection
     */
    public function latest($number = 10, array $with = [])
    {
        $query = $this->make($with);

        return $query->order()->take($number)->get();
    }

    /**
     * Get single model by Slug
     *
     * @param  string $slug slug
     * @param  array $with related tables
     * @return mixed
     */
    public function bySlug($slug, array $with = [])
    {
        $model = $this->make($with)
            ->where('slug', '=', $slug)
            ->first();

        return $model;

    }

    /**
     * Return all results that have a required relationship
     *
     * @param string $relation
     * @param array $with
     * @return Collection
     */
    public function has($relation, array $with = [])
    {
        $entity = $this->make($with);

        return $entity->has($relation)->get();
    }

    /**
     * Create a new model
     *
     * @param  array $data
     * @return mixed Model or false on error during save
     */
    public function create(array $data)
    {
        // Create the model
        $model = $this->model->fill($data);

        if ($model->save()) {
            return $model;
        }

        return false;
    }

    /**
     * Create a new model with sync
     *
     * @param  array $data
     * @return mixed Model or false on error during save
     */
    public function createWithSync(array $data)
    {
        // Create the model
        $model = $this->model->fill($data);

        if ($model->save()) {
            $this->syncRelation($model, $data, 'categories');
            return $model;
        }

        return false;
    }

    /**
     * Update an existing model
     *
     * @param array $data
     * @param null $model
     * @return boolean
     */
    public function update(array $data, $model = null)
    {
        if(empty($model)){
            if(isset($data['uuid'])){
                $model = $this->model->where('uuid',$data['uuid'])->firstOrFail();
            }else{
                $model = $this->model->findOrFail($data['id']);
            }
        }

        $model->fill($data);

        if ($model->save()) {
            return $model;
        }

        return false;

    }

    /**
     * Update an existing model with sync
     *
     * @param  array $data
     * @return boolean
     */
    public function updateWithSync(array $data)
    {
        $model = $this->model->find($data['id']);

        $model->fill($data);

        $this->syncRelation($model, $data, 'categories');

        if ($model->save()) {
            return $model;
        }

        return false;

    }

    /**
     * Sort models
     *
     * @param  array $data updated data
     * @return void
     */
    public function sort(array $data)
    {
        foreach ($data['item'] as $position => $item) {

            $page = $this->model->find($item['item_id']);

            $sortData = $this->getSortData($position, $item);

            $page->update($sortData);

            if ($data['moved'] == $item['item_id']) {
                $this->fireResetChildrenUriEvent($page);
            }

        }

    }

    /**
     * Get sort data
     *
     * @param  integer $position
     * @param  array $item
     * @return array
     */
    protected function getSortData($position, $item)
    {
        return [
            'position' => $position
        ];
    }

    /**
     * Fire event to reset children’s uri
     * Only applicable on nestable collections
     *
     * @param  Page $page
     * @return void|null
     */
    protected function fireResetChildrenUriEvent($page)
    {
        return null;
    }

    /**
     * Build a select menu for a module
     *
     * @param  string $method with method to call from the repository ?
     * @param  boolean $firstEmpty generate an empty item
     * @param  string $value witch column as value ?
     * @param  string $key witch column as key ?
     * @return array               array with key = $key and value = $value
     */
    public function select($method = 'all', $firstEmpty = false, $value = 'title', $key = 'id')
    {
        $items = $this->$method()->pluck($value, $key)->all();
        if ($firstEmpty) {
            $items = ['' => ' -- Select -- '] + $items;
        }
        return $items;
    }

    /**
     * Delete model
     *
     * @param $model
     * @return void
     */
    public function delete($model)
    {
        if(is_numeric($model)) $model =  $this->byId($model);
        $model->delete();
    }

    /**
     * Sync related items for model
     *
     * @param  Model $model
     * @param  array $data
     * @param  string $table
     * @return false|null
     */
    protected function syncRelation($model, array $data, $table = null)
    {
        if (!method_exists($model, $table)) {
            return false;
        }

        // add related items
        $pivotData = [];
        $position = 0;
        if (isset($data[$table])) {
            foreach ($data[$table] as $id) {
                $pivotData[$id] = ['position' => $position++];
            }
        }

        // Sync related items
        $model->$table()->sync($pivotData);
    }


    public function countAll()
    {
        return $this->model->count();
    }

    public function saveRelationInput($input, $column = 'id')
    {
        if($input){
            if(is_numeric($input)){
                $input = $this->model->find($input)->$column;
            }else{
                $input = $this->model->firstOrCreate(['name'=>$input])->$column;
            }
            return $input;
        }
        return NULL;
    }

    public function allPaginated(array $with = [])
    {
        $query = $this->make($with);

        $request = request();

        $per_page  = $request->get('per_page',10);

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
