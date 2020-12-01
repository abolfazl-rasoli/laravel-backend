<?php

namespace Main\User\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Validation\Rules\In;
use Main\App\Helper\Helper;
use Main\App\Request\MainRequest;
use Main\User\Model\User;
use Main\User\Rules\EmailPhoneRule;

class UserRequest extends MainRequest
{

    public function getRule($rule)
    {
        $size = env('AVATAR_MAX_SIZE_IMAGE');
        $minPass = env('MIN_LENGTH_PASSWORD_FOR_REGISTER');
        $field = Helper::fieldEmailOrMobile($this->username);
        $tokenLength= Str::length(Helper::makeToken());

        $rulesList =
        [
            'name' => 'nullable|string|max:50',
            'password' => 'required|string|min:' . $minPass . '|max:60|confirmed',
            'avatar' => 'nullable|max:' . $size . '|image',
            'email' => 'required_without:mobile|unique:users,email|max:191|regex:' . User::$login['email'],
            'mobile' => 'required_without:email|unique:users,mobile|max:191|regex:' . User::$login['mobile'],
            'user_id' => 'required|exists:users,id|numeric',
            'username' => ['required', new EmailPhoneRule(), 'max:191'],
            'token' => 'required|size:'.$tokenLength,
            'search' => 'nullable|max:191|string',
            'status' => ['nullable', 'string', new In(['active', 'inactive'])],
            'created_at' => 'nullable|numeric',
            'verified_at' => 'nullable|numeric',
            'role' => 'nullable|numeric|exists:roles,id',

            // different key
            'password_null' => 'nullable|string|min:' . $minPass . '|max:60|confirmed',
            'username_exists' => ['required','string','max:191', new EmailPhoneRule(), 'exists:users,'.$field],
            'username_unique' => ['required','string','max:191', new EmailPhoneRule(), 'unique:users,'.$field],
            'avatar_required' => 'required|max:' . $size . '|image',

        ];

        if (isset($rulesList[$rule]))
            return $rulesList[$rule];
        return '';
    }

    public function getRules($rules = [], $merge = []){
        $result = [];
        foreach ($rules as $rule){
            $result[$rule] = $this->getRule($rule);
        }
        return array_merge($result , $merge);
    }

}
