<?php

namespace Main\App\Helper;

use Illuminate\Http\Resources\Json\JsonResource;
use Main\App\Helper\Traits\StatusCode;

class MessagesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $message = $this->message();
        return [
            "data" => $this['data'] ?? [],
            "error" => $this['error'] ?? isset($this['s'])  ?  $this['s'] >= 400 : false,
            "success" => $this['success'] ?? isset($this['s'])  ?  $this['s'] < 400 : false,
            "message" => $message
        ];
        //return parent::toArray($request);
    }


    public function withResponse($request, $response)
    {
        if (isset($this['s']) && StatusCode::checkStatus($this['s'])){
            $response->setStatusCode($this['s']);
        }

        parent::withResponse($request, $response);
    }

    /**
     * @return array|mixed
     */
    public function message()
    {
        $message = [];
        if (isset($this['message'])) {
            if (gettype($this['message']) === 'array') {
                $message = $this['message'];
            } else {
                $message[] = $this['message'];
            }
        }
        return $message;
    }

}
