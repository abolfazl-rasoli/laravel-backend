<?php

namespace Main\App\Request;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Main\App\Exceptions\ValidateHandler;

class MainRequest extends FormRequest
{

    protected function failedValidation(Validator $validator)
    {
        throw new ValidateHandler($validator->errors()->messages(), 402);
    }

}
