<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\Controller;
use App;
use App\Libraries\Helpers\UserHelpers;
use Log;

class BeforeMiddleware
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
		$access = Controller::getAccessArray();
        //Log::info("at 23, access: " . json_encode($access));		
		$temp = explode("/", $request->path());
		$controllerName = $temp[0];
		$actionName = empty($temp[1]) ? 'read' : $temp[1];
		try {
			if(intval(UserHelpers::getUserrole()) == intval(UserHelpers::getConstants('ADMIN_ROLE_ID'))) {//admin role
				return $next($request);
			} else if((!empty($controllerName)) && isset($access[$controllerName])) {
				if(isset($access[$controllerName][$actionName]) && $access[$controllerName][$actionName] == 1) {					
					return $next($request);
				} else if((!isset($access[$controllerName][$actionName])) && $access[$controllerName]['read'] == 1 && $access[$controllerName]['create'] == 1 && $access[$controllerName]['edit'] == 1 && UserHelpers::controllerHasExtraMethodName($controllerName, $actionName)) {						
					return $next($request);
				} else {
					return App::abort(404);
				}
			} else if(empty($controllerName)) {
				return $next($request);
			} else {
				return App::abort(404);
			}   
		} catch (Exception $e) {
			return redirect($request->fullUrl())->with('error',$e->getMessage());
		}
    }
}
