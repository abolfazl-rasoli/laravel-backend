<?php

namespace Main\App\Middleware;

use Closure;
use Main\Language\Model\Language;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $x_lang = $request->header('X-localization');
        // Check header request and determine localization
        $local = $x_lang && Language::firstWhere('lang' ,$x_lang) ? $x_lang:
            Language::firstWhere('primary', 1)->lang  ;

        // set laravel localization
        app()->setLocale($local);

        // continue request
        return $next($request);
    }
}
