<?php

namespace Modules\Users\Repositories;

interface UserInterface
{
    /**
     * Returns all the users
     * @return object
     */
    public function all();

    /**
     * Create a user resource
     * @param  array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Find a user by its ID
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * Update a user
     * @param $data
     * @return mixed
     */
    public function update(array $data);

    /**
     * Deletes a user
     * @param $id
     * @return mixed
     */
    public function delete($id);
}
