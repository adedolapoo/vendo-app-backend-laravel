<?php

namespace Modules\Products\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\AbstractFormRequest;

class FormRequest extends AbstractFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'cost' => ['required', 'integer', Rule::in(config('users.deposit_range'))]
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'cost.in'=> __('The cost of product can only be in the range of 5,10,20,50,100')
        ];
    }
}
