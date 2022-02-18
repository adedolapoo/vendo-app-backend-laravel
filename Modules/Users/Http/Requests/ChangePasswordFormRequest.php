<?php
namespace Modules\Users\Http\Requests;

use Modules\Core\Http\Requests\AbstractFormRequest;

class ChangePasswordFormRequest extends AbstractFormRequest {

    public function rules()
    {
        $rules = [
            'password'=>'required',
            'confirm_password'=>'required|same:password',
        ];

        return $rules;
    }
}
