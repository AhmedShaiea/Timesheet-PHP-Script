<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;

class Home2Controller extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {error_log("error at homecontroller 25");Log::info("at homecontroller 25");
		if(parent::verifyUID()) {error_log("error at homecontroller 26");
			return redirect('timesheet');
		} else {error_log("error at homecontroller 28");
			return redirect('login');
		}
    }
}
