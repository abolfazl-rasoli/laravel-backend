<?php

namespace Main\User\Http\Requests;

use Illuminate\Support\Facades\Gate;

class EditAvatarUserRequest extends UserRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user_query) return Gate::allows(__CLASS__);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->getRules([] , [
            'avatar' => $this->getRule('avatar_required')
        ]);
    }


}
