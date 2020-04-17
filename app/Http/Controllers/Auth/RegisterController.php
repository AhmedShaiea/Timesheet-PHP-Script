<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
	
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|string|max:255',
			'last_name' => 'required|string|max:255',
			'phone' => 'string|max:45',
			'address' => 'string|max:45',
			'address2' => 'string|max:45',
			'city' => 'string|max:45',
			'province' => 'string|max:45',			
			'country' => 'string|max:45',
			'zip' => 'string|max:45',
            'email' => 'required|string|email|max:255|unique:users',
			'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
		
		
        return User::create([
            'first_name' => $data['first_name'],
			'last_name' => $data['last_name'],
			'phone' => $data['phone'],
			'address' => $data['address'],
			'address2' => $data['address2'],
			'city' => $data['city'],
			'province' => $data['province'],			
			'country' => $data['country'],
			'zip' => $data['zip'],
            'email' => $data['email'],
			'username' => $data['username'],
            'password' => bcrypt($data['password']),
			'status' => 0,
        ]);
    }

    /**
     * Use username for registration.
     *
     * @return username
     */	
	/*public function username()
	{
		return 'username';
	}*/
	
	protected function guard()
	{
		return Auth::guard('web');
	}
}
