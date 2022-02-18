<?php namespace Modules\Users\Repositories\Sanctum;

use Illuminate\Support\Facades\Hash;
use Modules\Core\Repositories\RepositoriesAbstract;
use Modules\Users\Entities\Sanctum\User;
use Modules\Users\Repositories\UserInterface;

class SanctumUser extends RepositoriesAbstract implements UserInterface
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Get empty model
     *
     * @return model
     */
    public function getModel()
    {
        $model = config('auth.providers.users.model');

        return $model::getModel();
    }

    /**
     * Get table name
     *
     * @return string
     */
    public function getTable()
    {
        return 'users';
    }

    /**
     * Create a user resource
     * @param $data
     * @return mixed
     */
    public function create(array $data)
    {
        $data['role'] = empty($data['role']) ? 'user' : $data['role'];

        $this->hashPassword($data);

        return $this->model->create((array) $data);
    }

    /**
     * Find a user by its ID
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->byId($id);
    }

    /**
     * Hash the password key
     * @param array $data
     */
    private function hashPassword(array &$data)
    {
        $data['password'] = Hash::make($data['password']);
    }

    /**
     * Check if there is a new password given
     * If not, unset the password field
     * @param array $data
     */
    private function checkForNewPassword(array &$data)
    {
        if (empty($data['password'])) {
            unset($data['password']);

            return;
        }

        $data['password'] = Hash::make($data['password']);
    }

    /**
     * @return mixed
     */
    public function countAll(){
        $model = config('auth.providers.users.model');

        return $model::count();
    }

}
