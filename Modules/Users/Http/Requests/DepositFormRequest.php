<?php
namespace Modules\Users\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\AbstractFormRequest;

class DepositFormRequest extends AbstractFormRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'deposit' => ['required', 'integer', Rule::in(config('users.deposit_range'))],
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
            'deposit.in'=> __('The deposit can only be one of 5,10,20,50,100 coins')
        ];
    }
}
