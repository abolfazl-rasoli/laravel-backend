<?php

namespace Main\Language\Http\Requests;
use Main\App\Request\MainRequest;


class LanguageRequest extends MainRequest
{

    public function getRule($rule)
    {

        $edit = $this->language ? 'unique:languages,lang,'.  $this->language->id: null;

        $rulesList =
            [

                'lang' => ['required', 'string', 'max:3', $edit],
                'lang_unique' => 'required|string|unique:languages,lang|max:3',

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
