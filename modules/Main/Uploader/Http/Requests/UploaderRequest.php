<?php

namespace Main\Uploader\Http\Requests;

use Main\App\Request\MainRequest;
use Main\Uploader\Uploader;

class UploaderRequest extends MainRequest
{

    public function getRule($rule)
    {
        $mimes =  collect(Uploader::$mimes)->flatten()->join(',');
        $rulesList =
        [

            'files' => 'required|array',
            'files.*' => "required|max:". env('UPLOAD_MAX_SIZE') ."|mimes:" . $mimes,
            'urls' => 'required|array',
            'urls.*' => 'required|url'

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
