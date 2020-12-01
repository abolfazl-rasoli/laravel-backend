<?php

namespace Main\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Main\App\Helper\MessagesResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return new MessagesResource(['data' => $this->resource, 's' => 200]);
    }
}
