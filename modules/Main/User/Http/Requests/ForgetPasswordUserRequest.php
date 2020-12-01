<?php

namespace Main\User\Http\Requests;

use Illuminate\Validation\Rules\In;
use Main\App\Request\MainRequest;
use Main\User\Rules\EmailPhoneRule;

class ForgetPasswordUserRequest extends UserRequest
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

        return $this->getRules([], [
            'username' => $this->getRule('username_exists')
        ]);
    }


}
