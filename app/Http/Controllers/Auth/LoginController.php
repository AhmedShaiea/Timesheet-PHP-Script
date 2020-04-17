<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Libraries\Helpers\UserHelpers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Session;
use Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/timesheet';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

	/**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    /*public function index()
    {
		if ($request->isMethod('get')) {
            return view('auth.login');
		} elseif ($request->isMethod('post')) {
			if (Auth::attempt(['email' => $email, 'password' => $password])) {
                // Authentication passed...
                return redirect()->intended('dashboard');
            }
		}
    }  */	

	/**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
		try{
			session_unset();
			$input = $request->all();
			if (Auth::attempt(['username' => trim($input['username']), 'password' => trim($input['password']), 'status' => 1])) {
				// The user is active, not suspended, and exists.
				//set UID, UNAME, UMANAGERID, UMANAGERNAME, UASSOCIATES
				Session::put('UID', Auth::id());
				//Log::info("at 77, UID: " . Session::get('UID'));			
				return true;
			}
			return false;
		} catch(Exception $e) {
			return redirect('login');
		}
    }
	
	/*
	protected function redirectTo()
	{		
		return '/timesheet';
	}*/
	
    /**
     * Use username for authentication.
     *
     * @return username
     */	
	public function username()
	{
		return 'username';
	}
	
	protected function guard()
	{
		return Auth::guard('web');
	}
	
	public function logout() {
		session_unset();
		Session::flush();
		Auth::logout();
		return redirect('login');
	}
}