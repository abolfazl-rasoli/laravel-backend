<?php

namespace Main\User\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Main\App\Exceptions\ValidateHandler;

class VerifyRegisterUserRequest extends UserRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->getRules(['token'], [
            'username' => $this->getRule('username_exists')
        ]);
    }

  /*  /**
     * dont show validate user_id for hide number column table users
     * @param Validator $validator
     * @throws ValidateHandler

    protected function failedValidation(Validator $validator)
    {
        throw new ValidateHandler(trans('User::messages.invalidVerify'), 402);
    }*/

}
