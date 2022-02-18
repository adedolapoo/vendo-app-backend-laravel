<?php
namespace Modules\Users\Http\Requests;

use Modules\Core\Http\Requests\AbstractFormRequest;

class RegisterFormRequest extends AbstractFormRequest {

    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email','unique:users'],
            'password' => ['required'],
            'confirm_password' => ['required','same:password']
        ];
    }
}
