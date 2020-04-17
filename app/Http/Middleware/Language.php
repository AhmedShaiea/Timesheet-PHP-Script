<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Session;

class Language {

    public function __construct(Application $app, Request $request) {
        $this->app = $app;
        $this->request = $request;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		$my_locale = Session::get('my_locale');  // == null ? env('APP_LANGUAGE') : Session::get('my_locale');
        $this->app->setLocale($my_locale == null ? config('app.locale') : $my_locale);

        return $next($request);
    }

}