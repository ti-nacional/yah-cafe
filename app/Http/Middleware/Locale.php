<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App;
use Config;

class Locale
{
    /**
     * The availables languages.
     *
     * @array $languages
     */
    protected $languages = ['en','fr','ru','ch'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Session::has('locale'))
        {
            Session::put('locale', Config::get('app.locale'));
        }
        App::setLocale(Session::get('locale'));

        return $next($request);
    }
}