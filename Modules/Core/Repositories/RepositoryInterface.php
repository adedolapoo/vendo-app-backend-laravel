<?php
namespace Modules\Core\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use stdClass;
use Modules\Core\Collections\NestableCollection;

interface RepositoryInterface
{
    /**
     * Get empty model
     *
     * @return Model
     */
    public function getModel();

    /**
     * Get table name
     *
     * @return string
     */
    public function getTable();

    /**
     * Make a new instance of the entity to query on
     *
     * @param array $with
     */
    public function make(array $with = array());

    /**
     * Find a single entity by key value
     *
     * @param string $key
     * @param string $value
     * @param array  $with
     */
    public function getFirstBy($key, $value, array $with = array());

    /**
     * Retrieve model by id
     * regardless of status
     *
     * @param int $id model ID
     * @param array $with
     * @return Model
     */
    public function byId($id, array $with = array());

    /**
     * Get all models
     *
     * @param  array       $with Eager load related models
     * @return Collection
     */
    public function all(array $with = array());

    /**
     * Get all models by key/value
     *
     * @param  string     $key
     * @param  string     $value
     * @param  array      $with
     * @return Collection
     */
    public function allBy($key, $value, array $with = array());

    /**
     * Get latest models
     *
     * @param  integer      $number number of items to take
     * @param  array        $with array of related items
     * @return Collection
     */
    public function latest($number = 10, array $with = array());

    /**
     * Get single model by Slug
     *
     * @param  string $slug slug
     * @param  array  $with related tables
     * @return mixed
     */
    public function bySlug($slug, array $with = array());

    /**
     * Return all results that have a required relationship
     *
     * @param  string $relation
     * @param  array  $with
     * @return Collection
     */
    public function has($relation, array $with = array());

    /**
     * Create a new model
     *
     * @param  array  Data needed for model creation
     * @return mixed Model or false on error during save
     */
    public function create(array $data);

    /**
     * Update an existing model
     *
     * @param array $data
     * @param null $model
     * @return boolean
     */
    public function update(array $data, $model = null);

    /**
     * Sort models
     *
     * @param  array  Data to update Pages
     * @return boolean
     */
    public function sort(array $data);

    /**
     * Build a select menu for a module
     *
     * @param  string  $method     with method to call from the repository ?
     * @param  boolean $firstEmpty generate an empty item
     * @param  string  $value      witch column as value ?
     * @param  string  $key        witch column as key ?
     * @return array               array with key = $key and value = $value
     */
    public function select($method = 'all', $firstEmpty = false, $value = 'title', $key = 'id');

    /**
     * Delete model
     *
     * @param $model
     * @return boolean
     */
    public function delete($model);
}
