<?php namespace Modules\Users\Repositories\Sentinel;

use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Hash;
use Modules\Users\Exceptions\UserNotFoundException;
use Modules\Users\Repositories\UserInterface;

class SentinelUser implements UserInterface
{
    /**
     * @var \Modules\Users\Entities\Sentinel\User
     */
    protected $user;
    /**
     * @var \Cartalyst\Sentinel\Roles\EloquentRole
     */
    protected $role;

    public function __construct()
    {
        $this->user = Sentinel::getUserRepository()->createModel();
        $this->role = Sentinel::getRoleRepository()->createModel();
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
     * Returns all the users
     * @return object
     */
    public function all()
    {
        return $this->user->all();
    }

    /**
     * Create a user resource
     * @param $data
     * @return mixed
     */
    public function create(array $data)
    {
        $this->hashPassword($data);
        $data = $this->_createOrUpdateWorkflowUser($data);
        return $this->user->create((array) $data);
    }

    /**
     * Create a user and assign roles to it
     * @param  array $data
     * @param  array $roles
     * @param bool $activated
     */
    public function createWithRoles($data, $roles, $activated = false)
    {
        $this->hashPassword($data);

        $data = $this->_createOrUpdateWorkflowUser($data);

        $user = $this->create((array) $data);


        if (!empty($roles)) {
            $user->roles()->attach($roles);
        }

        if ($activated) {
            $activation = Activation::create($user);
            Activation::complete($user, $activation->code);
        }
        return $user;
    }

    /**
     * Find a user by its ID
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->user->find($id);
    }

    /**
     * Update a user
     * @param $user
     * @param $data
     * @return mixed
     */
    public function update($user, $data)
    {

        $user = $user->update($data);

        event(new UserWasUpdated($user));

        return $user;
    }

    /**
     * @param $userId
     * @param $data
     * @param $roles
     * @internal param $user
     * @return mixed
     */
    public function updateAndSyncRoles($userId, $data, $roles)
    {
        $user = $this->user->find($userId);

        $this->checkForNewPassword($data);

        $this->checkForManualActivation($user, $data);

       $data = $this->_createOrUpdateWorkflowUser($data, $user);

        $user = $user->fill($data);
        $user->save();

        event(new UserWasUpdated($user));

        if (!empty($roles)) {
            $user->roles()->sync($roles);
        }

        return $user;
    }

    /**
     * Deletes a user
     * @param $id
     * @throws UserNotFoundException
     * @return mixed
     */
    public function delete($id)
    {
        if ($user = $this->user->find($id)) {
            return $user->delete();
        };

        throw new UserNotFoundException();
    }

    /**
     * Find a user by its credentials
     * @param  array $credentials
     * @return mixed
     */
    public function findByCredentials(array $credentials)
    {
        return Sentinel::findByCredentials($credentials);
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
     * Check and manually activate or remove activation for the user
     * @param $user
     * @param array $data
     */
    private function checkForManualActivation($user, array &$data)
    {
        if (Activation::completed($user) && empty($data['activated'])) {
            return Activation::remove($user);
        }

        if (!Activation::completed($user) && !empty($data['activated'])) {
            $activation = Activation::create($user);

            return Activation::complete($user, $activation->code);
        }
    }

    public function getForDataTable(){
        $this->role_slug = $role_slug = request()->get('role', 'admin');
        $model = config('auth.providers.users.model');

        //dd($model);
        $query= $model::select([
            'users.id as user_id',
            'first_name',
            'last_name',
            'username',
            'email',
            'wf_user_id',
            'users.created_at as user_created_at',
            'positions.name as position_name'
        ]);

        $query = $query->leftJoin('positions', 'positions.id', '=', 'users.position_id');

        $query = $query->whereHas('roles', function ($query) {
            return $query->where('roles.slug', $this->role_slug);
        });

        return $query;
    }

    public function countAll(){
        $model = config('auth.providers.users.model');
        //dd($model);
        return $model::count();
    }

    public function getFirstBy($key, $value)
    {
        return $this->user->where($key, '=', $value)->first();
    }

    public function _createOrUpdateWorkflowUser($data,$user = null)
    {
        if(empty($user->wf_user_id)){
            $password_hash = \Config::get('myapp.wf_password_hash', 'sha256');
            $wf_password = substr(md5(rand()), 0, 7);
            $data['wf_password'] = hash($password_hash, $wf_password);
            $data['wf_user_id'] = \RestWorkflow::createUser($data['first_name'], $data['last_name'],$data['email'], $wf_password);
        }
        /*try{
            $first_name = isset($data['first_name']) ? $data['first_name'] : $user->first_name;
            $last_name  = isset($data['last_name']) ? $data['last_name'] : $user->last_name;
            \RestWorkflow::updateUser($user->wf_user_id,[
                'usr_email' => $data['email'],
                'usr_username' => $data['email'],
                'usr_firstname' => $first_name,
                'usr_lastname' => $last_name,
            ]);
        }catch (\Exception $e){
            if(str_contains(strtolower($e->getMessage()),['usr_uid','usr_username'])){
                $password = substr(md5(rand()), 0, 7);
                $data['wf_user_id'] = $wf_user_id = \RestWorkflow::createUser($first_name, $last_name, $data['email'], $password);
                $password_hash = \Config::get('myapp.wf_password_hash', 'sha256');
                $data['wf_password'] =  hash($password_hash,$password);
            }else{
                //throw $e;
            }
        }*/
        return $data;
    }
}
