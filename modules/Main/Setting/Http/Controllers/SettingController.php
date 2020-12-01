<?php

namespace Main\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
use Main\App\Helper\TryCache;
use Main\Setting\Http\Requests\UpdateSettingRequest;
use Main\Setting\Http\Resources\SettingResource;
use Main\Setting\Http\Requests\ViewSettingRequest;
use Main\Setting\Traits\SettingTraits;

class SettingController extends Controller
{

    use SettingTraits;
    /**
     * view settings
     * @param ViewSettingRequest $request
     * @return mixed
     */
    public function view(ViewSettingRequest $request)
    {
       return TryCache::render(function () use ($request) {

           return new SettingResource($this->get($request->settings));

       });
    }

    /**
     * update settings
     * @param UpdateSettingRequest $request
     * @return mixed
     */
    public function update(UpdateSettingRequest $request)
    {
       return TryCache::render(function () use ($request) {

           return new SettingResource($this->put($request->validated()));

       });
    }

}
