<?php
namespace Modules\Users\Http\Requests;

use Modules\Core\Http\Requests\AbstractFormRequest;

class FormEditRequest extends AbstractFormRequest {

    public function rules()
    {
        return [
            'email'=> ['email'],
        ];
    }
}
