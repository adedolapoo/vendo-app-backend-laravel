<?php

namespace Modules\Products\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\AbstractFormRequest;

class BuyFormRequest extends AbstractFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'products' => ['required', 'array'],
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
            'products.required'=> __('Select at least one product')
        ];
    }
}
