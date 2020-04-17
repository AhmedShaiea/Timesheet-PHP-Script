<?php

namespace App\Libraries\Helpers;

use App\Models\User;
use App\Models\Role;
use App\Models\Type;
use App\Models\Access;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Constant;
use Session;
use Log;

class UserHelpers
{
    public static function getAssociatesForManager(int $managerid) {
        $temps = User::where('status', 1)
            ->where('reportto', $managerid)
            ->select('id')
            ->orderBy('id')
            ->get()->all();
        $users = array();
        foreach($temps as $k => $v) {
            $users[] = $v->id;
        }
        return $users;
    }
	
	public static function isAdmin() {
		return strtolower(UserHelpers::getUserRoleName()) === strtolower(UserHelpers::getConstants('ADMIN_ROLE_NAME'));
	}
	
    public static function getAssociatesAndSelfForManager(int $managerid) {
        $temps = User::where('status', 1)
            ->where('reportto', $managerid)
            ->select('id')
            ->orderBy('id')
            ->get()->all();
        $users = array();
        $users[] = $managerid;
        foreach($temps as $k => $v) {
            $users[] = $v->id;
        }
        return $users;
    }
	
	public static function hasAssociates(int $id) {
        $temps = User::where('status', 1)
            ->where('reportto', $id)
            ->select('id')
            ->orderBy('id')
            ->get()->all();
		if(count($temps)){return true;}
		return false;
    }

    public static function getUID() {
        return Session::get('UID') > 0 ? Session::get('UID') : 0;
    }

    public static function getUsername() {
        $temp = User::where('status', 1)
            ->where('id', Session::get('UID'))
            ->select(DB::raw("concat(first_name,' ',last_name) as username"))
            ->get()->first();
        if(!empty($temp)) { return $temp->username;} else {return '';}
    }

    public static function getUserrole() {
        $temp = User::where('status', 1)
            ->where('id', Session::get('UID'))
            ->select('role')
            ->get()->first();
        if(!empty($temp)) { return $temp->role;} else {return 0;}
    }

    public static function getUserRoleName() {
        $temp = DB::table('users AS t')
            ->leftJoin('role', 'role.id', '=', 't.role')
            ->select('role.name as rolename')
            ->where('t.status', 1)
            ->where('t.id', UserHelpers::getUID())
            ->get()->first();
        if(!empty($temp)) { return $temp->rolename;} else {return '';}
    }

    public static function getUserEmailAddress() {
        $temps = DB::table('users')
            ->where('id', Session::get('UID'))
            ->where('status', 1)
            ->select('email')
            ->first();
        if(empty($temps)) {
            return "";
        } else {
            return $temps->email;
        }
    }

    public static function getManagerUID() {
        $temps = User::where('status', 1)
            ->where('id', Session::get('UID'))
            ->select('reportto')
            ->first();
        if(empty($temps)) {
            return "";
        } else {
            return $temps->reportto;
        }
    }

    public static function getManagerEmailAddress() {
        $temps = DB::table('users AS t')
            ->leftJoin('activeuser AS a', 'a.id', '=', 't.reportto')
            ->where('t.id', Session::get('UID'))
            ->where('t.status', 1)
            ->select('a.email')
            ->first();
        if(empty($temps)) {
            return "";
        } else {
            return $temps->email;
        }
    }

    public static function getAdminEmailAddresses() {
        $temps = DB::table('users AS t')
            ->leftJoin('role AS a', 'a.id', '=', 't.role')
            ->where('a.name', UserHelpers::getConstants('ADMIN_ROLE_NAME'))
            //->where('t.id', Session::get('UID'))
            ->where('t.status', 1)
            ->select('t.email')
            ->get()->all();
        if(empty($temps)) {
            return "";
        } else {
            $emails = array();
            foreach($temps as $k => $v) {
                $emails[] = $v->email;
            }
            return $emails;
        }
    }

    public static function getConstants(string $key) {
        //first, if it's already in the session, get the value directly
        if(Session::has(strtoupper($key))) {
            return Session::get(strtoupper($key));
        } else {
            $constant = Constant::where('status', 1)
                ->where('name', $key)
                ->get()->first();
            //Log::info("at 154, constant: " . json_encode($constant));
            if(empty($constant)) {
                return "";
            } else {
                Session::put(strtoupper($key), $constant['description']);
                return $constant->description;
            }
        }
    }

    public static function removeAssociatesFromUserArray($userid, $users) {
        $associatesArr = self::getAssociatesForManager($userid);
        $usersArr = explode(",", $users);
        $temp = array();
        foreach($usersArr as $k) {
            if(!in_array($k, $associatesArr)) {
                $temp[] = $k;
            }
        }
        return implode(",",$temp);
    }

    public static function getTypesForUserRCUS($userid) {
        $types = Type::where('status', 1)
            ->orderBy('id')
            ->get()->all();
        $user = User::find($userid);
        $read = array();
        $create = array();
        $update = array();
        $search = array();
        foreach($types as $type) {
            $roleArr = explode(",", $type->role);
            if(in_array(strval($user->role), $roleArr) || intval($user->role) === intval(UserHelpers::getConstants('ADMIN_ROLE_ID'))) {
                $read[strval($type->id)] = $type->name;
                $create[strval($type->id)] = $type->name;
                $update[strval($type->id)] = $type->name;
                $search[strval($type->id)] = $type->name;
            } else {
                $empArr = explode(",", $type->employee);
                if(in_array(strval($user->id), $empArr)) {
                    $read[strval($type->id)] = $type->name;
                    $create[strval($type->id)] = $type->name;
                    $update[strval($type->id)] = $type->name;
                    $search[strval($type->id)] = $type->name;
                } else {
                    if(($type->read & $user->division) === $user->division && $user->division !== 0 && $type->read !== 0) {
                        $read[strval($type->id)] = $type->name;
                    }
                    if(($type->create & $user->division) === $user->division && $user->division !== 0 && $type->create !== 0) {
                        $create[strval($type->id)] = $type->name;
                    }
                    if(($type->update & $user->division) === $user->division && $user->division !== 0 && $type->update !== 0) {
                        $update[strval($type->id)] = $type->name;
                    }
                    if(($type->search & $user->division) === $user->division && $user->division !== 0 && $type->search !== 0) {
                        $search[strval($type->id)] = $type->name;
                    }

                    $exceptionempArr = explode(",", $type->exceptionemployee);
                    if(in_array(strval($user->id), $exceptionempArr)) {
                        if(isset($read[strval($type->id)])) {
                            unset($read[strval($type->id)]);
                        }
                        if(isset($create[strval($type->id)])) {
                            unset($create[strval($type->id)]);
                        }
                        if(isset($update[strval($type->id)])) {
                            unset($update[strval($type->id)]);
                        }
                        if(isset($search[strval($type->id)])) {
                            unset($search[strval($type->id)]);
                        }
                    }
                    
                }
            }
        }

        $result = array();
        $result['read'] = $read;
        $result['create'] = $create;
        $result['update'] = $update;
        $result['search'] = $search;
        //Log::info("at 238, result: " . json_encode($result));
        return $result;
    }

    public static function getControllerExtraMethodsName() {
        $constant = Constant::where('status', 1)
                ->where('name', 'CONTROLLERS_EXTRA_METHODS_ARRAY')
                ->get()->first();
        if(empty($constant)) {
            return array();
        } else {
            $data = json_decode($constant->description, true);
            $result = array();
            foreach($data as $k => $v) {
                if(!isset($result[$k])) {
                    $result[$k] = array();
                }
                $temp = explode(',', $v);
                foreach($temp as $val) {
                    $result[$k][] = $val;
                }
            }
            //Log::info("at 260, result: " . json_encode($result));
            return $result;
        }
    }

    public static function controllerHasExtraMethodName($controller, $method) {
        $constant = Constant::where('status', 1)
                ->where('name', 'CONTROLLERS_EXTRA_METHODS_ARRAY')
                ->get()->first();
        if(empty($constant)) {
            return false;
        } else {
            $data = json_decode($constant->description, true);
            foreach($data as $k => $v) {
                if(strtolower($k) === strtolower($controller)) {
                    $temp = explode(',', $v);
                    foreach($temp as $val) {
                        if(strtolower($val) === strtolower($method)) {
                            return true;
                        }
                    }
                }
            }
            return false;
        }
    }

    public static function getmenuExceptions($getFromDatabase = false) {
        if(!$getFromDatabase) {
            if(Session::has('menuExceptionArr')) {
                return Session::get('menuExceptionArr');
            } else {
                return array();
            }
        } else {
            //get user id, role, division
            $user_temp = User::where('status', 1)
                ->where('id', UserHelpers::getUID())
                ->select('role', 'division')
                ->get()->first();
            $user = array();
            $user['id'] = UserHelpers::getUID();
            $user['role'] = $user_temp['role'];
            $user['division'] = $user_temp['division'];
            //Log::info("at 304, user: " . json_encode($user));
			//Log::info("at 305, CONTROLLERS_ARRAY: " . UserHelpers::getConstants('CONTROLLERS_ARRAY'));
            $menuExceptionArr = array();
			if(intval(UserHelpers::getConstants('ADMIN_ROLE_ID')) !== intval($user['role'])) {
				foreach ((array)(json_decode(UserHelpers::getConstants('CONTROLLERS_ARRAY'), true)) as $k => $controllername) {
					$accesses = Access::where('status', 1)
						->where('target', $controllername)
						->select('id', 'target', 'role', 'employee', 'exceptionemployee', 'read', 'create', 'edit', 'search', 'delete')
						->orderBy('id')->limit(1)
						->get()->all();
					$skip = false;

					foreach((array)$accesses as $access) {
						if(!empty($access['role'])) {
							$tempArr = explode(',', $access['role']);
							foreach((array)$tempArr as $r) {
								if(intval(trim($r)) === $user['role']) {
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
									$menuExceptionArr[] = $controllername;
									$skip = true;
									continue;
								}
							}
						}
						if($skip){continue;}
						if((($access['read'] & $user['division']) === $user['division']) && $user['division'] !== 0 && $access['read'] !== 0) {
							//Log::info("at 349, read: " . $access['read'] . ", division: " . $user['division'] . ", read & division: " . ($access['read'] & $user['division']));
						} else {
							$menuExceptionArr[] = $controllername;
						}
					}
				}//end of foreach controllernames
			}
            //Log::info("at 356, menuExceptionArr: " . json_encode($menuExceptionArr));
            Session::put('menuExceptionArr', $menuExceptionArr);
            return $menuExceptionArr;
        }
    }

    public static function getAutoemailaddresses($createEditOrReview, $timesheetid = 0) {
        $autoemail = Constant::where('name', UserHelpers::getConstants('AUTOEMAIL_NAME'))
            ->get()->first();
        $temp = json_decode($autoemail->description, true);
		if($temp == null) {$temp = array();}
        $data = array();
        foreach($temp as $k => $v) {
            if($k === $createEditOrReview) {
                if("create" === $createEditOrReview || "edit" === $createEditOrReview) {
                    $temp1 = explode('|', $v);
                    foreach($temp1 as $val) {
                        if(!empty($val)) {
                            $temp1 = null;
                            $temp2 = array();
                            if(trim($val) === 'self') {
                                $temp1 = self::getUserEmailAddress();
                            } else if(trim($val) === 'manager') {
                                $temp1 = self::getManagerEmailAddress();
                            } else if(trim($val) === 'admin') {
                                $temp2 = self::getAdminEmailAddresses();
                            }
                            if(!empty($temp1)) {
                                $data[] = $temp1;
                            }
                            if(!empty($temp2)) {
                                foreach($temp2 as $t) {
                                    $data[] = $t;
                                }
                            }
                        }
                    }
                } else if("review" === $createEditOrReview) {
                    $temp0 = explode('|', $v);
                    foreach($temp0 as $val) {
                        if(!empty($val)) {
                            $temp1 = null;
                            $temp2 = array();
                            if(trim($val) === 'self' && $timesheetid > 0) {
                                $temps = DB::table('timesheet AS t')
                                    ->leftJoin('users AS a', 'a.id', '=', 't.userid')
                                    ->where('a.status', 1)
                                    ->where('t.id', intval($timesheetid))
                                    ->where('t.status', 1)
                                    ->select('a.email')
                                    ->get()->first();
                                if(empty($temps)) {
                                    return null;
                                } else {
                                    $temp1 = $temps->email;
                                }
                            } else if(trim($val) === 'manager') {
                                $temp1 = self::getUserEmailAddress();
                            } else if(trim($val) === 'admin') {
                                $temp2 = self::getAdminEmailAddresses();
                            }
                            if(!empty($temp1)) {
                                $data[] = $temp1;
                            }
                            if(!empty($temp2)) {
                                foreach($temp2 as $t) {
                                    $data[] = $t;
                                }
                            }
                        }
                    }
                }
            }
        }
		$data = array_unique($data);
        return $data;
    }

    public static function getWebhookUrls($createEditOrReview) {
        $webhook = Constant::where('name', UserHelpers::getConstants('WEBHOOK_NAME'))
            ->get()->first();
        $temp = json_decode($webhook->description, true);
        $data = array();
        foreach($temp as $k => $v) {
            if($k === $createEditOrReview) {
                $temp1 = explode('|', $v);
                foreach($temp1 as $val) {
                    if(!empty($val)) {
                        $data[] = $val;
                    }
                }
            }
        }
        return $data;
    }

}
