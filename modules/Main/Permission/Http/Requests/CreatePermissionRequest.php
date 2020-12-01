<?php

namespace Main\Permission\Http\Requests;

class CreatePermissionRequest extends PermissionRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // todo permission
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
            'title' => $this->getRule('title_unique')
        ]);
    }

}
