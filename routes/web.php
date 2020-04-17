<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::any('{name}/{method}/{id}', array('as'=>'{name} {method}', 'uses'=>'{name}Controller@{method}'));
use Illuminate\Http\Request;
//use Log;

Route::group(['middleware' => ['language']], function() {
	
Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home')->middleware('auth', 'access');
	
Route::any('/home', function () {
	try {
	    return redirect('timesheet');
        //return view('welcome');
	} catch (Exception $e){Log::info("error for / routing: " . $e->getMessage());
		return redirect('login');
		//App::abort(404);
	}
})->middleware('auth', 'access');

Route::any('/', function () {
	try {
	    return redirect('timesheet');
        //return view('welcome');
	} catch (Exception $e){Log::info("error for / routing: " . $e->getMessage());
	    return redirect('login');
		//App::abort(404);
	}
})->middleware('auth', 'access');

Route::any('/{any}/{action}/{id}', function (Request $request, $any, $action, $id) {
	try {
		$controller = App::make('App\\Http\\Controllers\\' . ucfirst(strtolower($any)) . 'Controller');
		return $controller->callAction($action, array($request, $id));
	} catch (Exception $e){//Log::info("error for any action id routing: " . $e->getMessage());
		return redirect('login');
		//App::abort(404);
	}
})->where('any', '.*')->middleware('auth', 'access');
	
Route::any('/{any}/{action}', function (Request $request, $any, $action) {
	try {
		$controller = App::make('App\\Http\\Controllers\\' . ucfirst(strtolower($any)) . 'Controller');
		return $controller->callAction($action, array($request));
	} catch (Exception $e){Log::info("error for any action routing: " . $e->getMessage());
		//return redirect('timesheet')->with('status', $e->getMessage()); 
		return redirect('login');
		//App::abort(404);
	}
})->where('any', '.*')->middleware('auth', 'access');

Route::any('/{any}/{id}', function (Request $request, $any, $id) {
	try {
		$controller = App::make('App\\Http\\Controllers\\' . ucfirst(strtolower($any)) . 'Controller');
		return $controller->callAction('detail', array($request, $id));
	} catch (Exception $e){//Log::info("error for any id routing: " . $e->getMessage());
	    return redirect('login');
		//App::abort(404);
	}
})->where('any', '.*')->middleware('auth', 'access');

Route::any('/{any}', function (Request $request, $any) {
	try {
        $controller = App::make('App\\Http\\Controllers\\' . ucfirst(strtolower($any)) . 'Controller');
		if(!$controller){
			return redirect('timesheet');
		} else {
			return $controller->callAction('index', array($request));
		}
	} catch (Exception $e){Log::info("error for any routing: " . $e->getMessage());
		return redirect('login');
		//App::abort(404);
	}
})->where('any', '.*')->middleware('auth', 'access');

//Route::group(['middleware' => ['auth']], function() {
	//my routes
	//Route::any('{name}/{method}/{id}', array('as'=>'{name} {method}', 'uses'=>'{name}Controller@{method}'));
 
 	//Route::any('{name}', array('as'=>'{name}', 'uses'=>'{name}Controller@index'));
	//Route::get('/timesheet', 'TimesheetController@index');
});


