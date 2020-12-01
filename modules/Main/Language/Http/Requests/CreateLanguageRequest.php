<?php

namespace Main\Language\Http\Requests;

use Illuminate\Support\Facades\Gate;

class CreateLanguageRequest extends LanguageRequest
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
        return $this->getRules([], [
            'lang' => $this->getRule('lang_unique'),
        ]);
    }

}
