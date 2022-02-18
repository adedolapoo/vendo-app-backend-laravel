<?php

namespace Modules\Users\Presenters;


use Illuminate\Database\Eloquent\Model;
use Modules\Core\Presenters\Presenter;

class ModulePresenter extends Presenter {

/*
* @return string translated 'yes' or 'no'
*/
    public function activated()
    {
        return $this->entity->isActivated() ? trans('global.Yes') : trans('global.No') ;
    }

    /**
     * Is user superuser ?
     *
     * @return string translated 'yes' or 'no'
     */
    public function superUser()
    {
        return $this->entity->isSuperUser() ? trans('global.Yes') : trans('global.No') ;
    }

    /**
     * Get title by concatenating first_name and last_name
     *
     * @return string
     */
    public function title()
    {
        return $this->entity->first_name . ' ' . $this->entity->last_name;
    }

    public function fullName()
    {
        return $this->entity->first_name . ' ' . $this->entity->last_name;
    }

    public function imgNotFound($file = 'uploads/avatar.png')
    {
        return $file;
    }

    public function createdAt()
    {
        return $this->entity->created_at->diffForHumans();
    }

    public function menu()
    {
        return $this->entity->created_at->diffForHumans();
    }

    public function role()
    {
        return $this->entity->roles()->first();
    }

}
