<?php

namespace Main\App\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Main\App\Exceptions\UnautenticatedExceptionHandler;

class CustomAuth extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

   protected function unauthenticated($request, array $guards)
   {
       throw new UnautenticatedExceptionHandler(trans('messages.unauthenticated'), 401 );
   }
}
