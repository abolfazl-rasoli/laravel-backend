<?php

namespace Main\Follow\Http\Controllers;
use App\Http\Controllers\Controller;
use Main\App\Helper\TryCache;
use Main\Follow\Http\Requests\AttachFollowRequest;
use Main\Follow\Http\Requests\FollowersRequest;
use Main\Follow\Http\Requests\FollowingRequest;
use Main\Follow\Http\Resources\FollowsResource;

class FollowsController extends Controller
{

    public function attach(AttachFollowRequest $request)
    {

        return TryCache::render(function () use($request){

            auth()->user()->to()->toggle([$request->to->id]);

            return new FollowsResource(auth()->user()->to);


        }, true);

    }

    public function following(FollowingRequest $request)
    {

        return TryCache::render(function () use($request){

            if($request->user_query){

                return new FollowsResource($request->user_query->to->toArray());

            }

            if(auth('api')->user()){
                return new FollowsResource(auth('api')->user()->to->toArray());
            }

            return new FollowsResource([]);

        });

    }

    public function followers(FollowersRequest $request)
    {

        return TryCache::render(function () use($request){

            if($request->user_query){

                return new FollowsResource($request->user_query->from->toArray());

            }

            if(auth('api')->user()){

                return new FollowsResource(auth('api')->user()->from->toArray());

            }

            return new FollowsResource([]);

        });

    }


}
