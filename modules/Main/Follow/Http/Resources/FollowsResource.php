<?php

namespace Main\Follow\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Main\App\Helper\MessagesResource;

class FollowsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return MessagesResource
     */
    public function toArray($request)
    {
        return new MessagesResource(['data' => $this->resource, 's' => 200]);
    }
}
