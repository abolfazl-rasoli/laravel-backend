<?php

namespace Main\User\Http\Requests;

use Illuminate\Support\Facades\Gate;

class SearchUserRequest extends UserRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return  Gate::allows(__CLASS__);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return $this->getRules(['search', 'status', 'created_at', 'verified_at']);
    }


}
