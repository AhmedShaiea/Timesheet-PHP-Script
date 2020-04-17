<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use App\Libraries\Helpers\UserHelpers;
use App\Models\Access;
use App\Models\User;
use Config;
use Session;
use Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public static function getUID() {
        Session::has('UID') ? Session::get('UID') : Session::put('UID', Auth::id());
        return Auth::id();
    }
	
	public function verifyUID() {
		if(Auth::check()) {
			if(Session::has('UID') && Session::get('UID')) {
			    return true;
			} else {
				session_unset();
				Session::flush();
				Auth::logout();
				return false;				
			}
		} else {
			session_unset();
			Session::flush();
			Auth::logout();
			return false;
		}
	}

    public static function getAccessArray() {
		if(!empty(UserHelpers::getUID())) {
			//get user id, role, division
			$user_temp = User::where('status', 1)
				->where('id', UserHelpers::getUID())
				->select('role', 'division')->limit(1)
				->get()->all();
			if(count($user_temp) == 0) {return array();}
			$user = array();
			foreach($user_temp as $temp) {
				$user['id'] = UserHelpers::getUID();
				$user['role'] = $temp['role'];
				$user['division'] = $temp['division'];
			}
			//Log::info("at 50, user: " . json_encode($user));
			$accessRulesArr = array();
			foreach ((array)(json_decode(UserHelpers::getConstants('CONTROLLERS_ARRAY'), true)) as $k => $controllername) {
				$accessRulesArr[$controllername] = array();

				$accesses = Access::where('status', 1)
					->where('target', $controllername)
					->select('id', 'target', 'role', 'employee', 'exceptionemployee', 'read', 'create', 'edit', 'search', 'delete')
					->orderBy('id')->limit(1)
					->get()->all();
				$skip = false;

				foreach((array)$accesses as $access) {
					if(isset($user['role']) && intval(UserHelpers::getConstants('ADMIN_ROLE_ID')) === $user['role']) {
						$accessRulesArr[$controllername]['read'] = 1;
						$accessRulesArr[$controllername]['create'] = 1;
						$accessRulesArr[$controllername]['edit'] = 1;
						$accessRulesArr[$controllername]['search'] = 1;
						$accessRulesArr[$controllername]['delete'] = 1;
						continue;
					}
					if(!empty($access['role'])) {
						$tempArr = explode(',', $access['role']);
						foreach((array)$tempArr as $r) {
							if(intval(trim($r)) === $user['role']) {
								$accessRulesArr[$controllername]['read'] = 1;
								$accessRulesArr[$controllername]['create'] = 1;
								$accessRulesArr[$controllername]['edit'] = 1;
								$accessRulesArr[$controllername]['search'] = 1;
								$accessRulesArr[$controllername]['delete'] = 1;
								$skip = true;
								continue;
							}
						}
					}
					if($skip){continue;}
					if(!empty($access['employee'])) {
						$tempArr = explode(',', $access['employee']);
						foreach((array)$tempArr as $r) {
							if(intval(trim($r)) === $user['id']) {
								$accessRulesArr[$controllername]['read'] = 1;
								$accessRulesArr[$controllername]['create'] = 1;
								$accessRulesArr[$controllername]['edit'] = 1;
								$accessRulesArr[$controllername]['search'] = 1;
								$accessRulesArr[$controllername]['delete'] = 1;
								$skip = true;
								continue;
							}
						}
					}
					if($skip){continue;}
					if(!empty($access['exceptionemployee'])) {
						$tempArr = explode(',', $access['exceptionemployee']);
						foreach((array)$tempArr as $r) {
							if(intval(trim($r)) === $user['id']) {
								$accessRulesArr[$controllername]['read'] = 0;
								$accessRulesArr[$controllername]['create'] = 0;
								$accessRulesArr[$controllername]['edit'] = 0;
								$accessRulesArr[$controllername]['search'] = 0;
								$accessRulesArr[$controllername]['delete'] = 0;
								$skip = true;
								continue;
							}
						}
					}
					if($skip){continue;}

					//'read', 'create', 'edit', 'search', 'delete'
					$accessRulesArr[$controllername]['read'] = 0;
					$accessRulesArr[$controllername]['create'] = 0;
					$accessRulesArr[$controllername]['edit'] = 0;
					$accessRulesArr[$controllername]['search'] = 0;
					$accessRulesArr[$controllername]['delete'] = 0;
					if((($access['read'] & $user['division']) === $user['division']) && $user['division'] !== 0 && $access['read'] !== 0) {
						//Log::info("at 124, read: " . $access['read'] . ", division: " . $user['division'] . ", read & division: " . ($access['read'] & $user['division']));
						$accessRulesArr[$controllername]['read'] = 1;
					}

					if((($access['create'] & $user['division']) === $user['division']) && $user['division'] !== 0 && $access['create'] !== 0) {
						$accessRulesArr[$controllername]['create'] = 1;
					}

					if((($access['edit'] & $user['division']) === $user['division']) && $user['division'] !== 0 && $access['edit'] !== 0) {
						$accessRulesArr[$controllername]['edit'] = 1;
					}

					if((($access['search'] & $user['division']) === $user['division']) && $user['division'] !== 0 && $access['search'] !== 0) {
						$accessRulesArr[$controllername]['search'] = 1;
					}

					if((($access['delete'] & $user['division']) === $user['division']) && $user['division'] !== 0 && $access['delete'] !== 0) {
						$accessRulesArr[$controllername]['delete'] = 1;
					}
				}

			}//end of foreach controllernames
			//Log::info("at 146, getAccessArray: " . json_encode($accessRulesArr));
			return $accessRulesArr;
		} else {
			return array();
		}
    }
}
