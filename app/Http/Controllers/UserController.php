<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Division;
use App\Models\Role;
use App\Libraries\Helpers\UserHelpers;
use Config;
use Session;
use Log;

class UserController extends Controller
{
    //if the user is admin role, show all users; if the user is manager role, show only this manager's associates;
    //if the user is at the lowest level, show only this user
    public function index(Request $request)
    {
        $input = $request->all(); 
        // a - amount per page; o - order (asc, desc); p - page number; s - sortby
        $amountperpage = empty($input['a']) ? intval(UserHelpers::getConstants('ROW_PER_TABLE_INT')) : intval($input['a']);
        $pagenumber = empty($input['p']) ? 1 : intval($input['p']);
        $offset = ($pagenumber - 1) * $amountperpage;
        $mysortby = empty($input['s']) ? 't.id' : preg_replace("/[^.a-zA-Z0-9]+/", "", $input['s']);
        $myorder = empty($input['o']) ? 'asc' : (strtolower($input['o']) === "asc" ? "asc" : "desc");
        $users; $total;

        $user = DB::table('users AS t')
            ->leftJoin('role', 'role.id', '=', 't.role')
            ->select('t.*', 'role.name as rolename')
            ->where('t.status', 1)
            ->where('t.id', UserHelpers::getUID())
            ->get()->first();

        if($user->rolename === UserHelpers::getConstants('ADMIN_ROLE_NAME')) {
            //get both status = 1 or 0 records
            $users = DB::table('users AS t')
                ->leftJoin('activeuser', 'activeuser.id', '=', 't.reportto')
                ->leftJoin('division', 'division.id', '=', 't.division')
                ->leftJoin('role', 'role.id', '=', 't.role')
                ->select('t.*', DB::raw("concat(t.first_name, ' ', t.last_name) as name"), DB::raw("concat(IFNULL(activeuser.first_name, ''), ' ', IFNULL(activeuser.last_name,'')) as reporttoperson"), 'division.name as divisionname', 'role.name as rolename')
                ->orderBy($mysortby, $myorder)
                ->offset($offset)->limit($amountperpage)
                ->get();

            $total = DB::table('users AS t')
                ->leftJoin('activeuser', 'activeuser.id', '=', 't.reportto')
                ->leftJoin('division', 'division.id', '=', 't.division')
                ->leftJoin('role', 'role.id', '=', 't.role')
                ->select(DB::raw('count(*) as total'))
                ->get();
        } else if($user->rolename === UserHelpers::getConstants('MANAGER_ROLE_NAME')) {
            //get only status = 1 records
            $users = DB::table('users AS t')
                ->leftJoin('activeuser', 'activeuser.id', '=', 't.reportto')
                ->leftJoin('division', 'division.id', '=', 't.division')
                ->leftJoin('role', 'role.id', '=', 't.role')
                ->select('t.*', DB::raw("concat(t.first_name, ' ', t.last_name) as name"), DB::raw("concat(IFNULL(activeuser.first_name, ''), ' ', IFNULL(activeuser.last_name,'')) as reporttoperson"), 'division.name as divisionname', 'role.name as rolename')
                ->where('t.status', 1)
                ->whereIn('t.id', UserHelpers::getAssociatesAndSelfForManager(Session::get('UID')))
                ->orderBy($mysortby, $myorder)
                ->offset($offset)->limit($amountperpage)
                ->get();

            $total = DB::table('users AS t')
                ->leftJoin('activeuser', 'activeuser.id', '=', 't.reportto')
                ->leftJoin('division', 'division.id', '=', 't.division')
                ->leftJoin('role', 'role.id', '=', 't.role')
                ->select(DB::raw('count(*) as total'))
                ->where('t.status', 1)
                ->whereIn('t.id', UserHelpers::getAssociatesAndSelfForManager(Session::get('UID')))
                ->get();

        } else {
            //get only self
            $users = DB::table('users AS t')
                ->leftJoin('activeuser', 'activeuser.id', '=', 't.reportto')
                ->leftJoin('division', 'division.id', '=', 't.division')
                ->leftJoin('role', 'role.id', '=', 't.role')
                ->select('t.*', DB::raw("concat(t.first_name, ' ', t.last_name) as name"), DB::raw("concat(IFNULL(activeuser.first_name, ''), ' ', IFNULL(activeuser.last_name,'')) as reporttoperson"), 'division.name as divisionname', 'role.name as rolename')
                ->where('t.status', 1)
                ->where('t.id', UserHelpers::getUID())
                ->orderBy($mysortby, $myorder)
                ->offset($offset)->limit($amountperpage)
                ->get();

            $total = DB::table('users AS t')
                ->leftJoin('activeuser', 'activeuser.id', '=', 't.reportto')
                ->leftJoin('division', 'division.id', '=', 't.division')
                ->leftJoin('role', 'role.id', '=', 't.role')
                ->select(DB::raw('count(*) as total'))
                ->where('t.status', 1)
                ->where('t.id', UserHelpers::getUID())
                ->get();
        }
        $showpagination = ($total[0]->total > intval(UserHelpers::getConstants('ROW_PER_TABLE_INT'))) ? true : false;
        $totalpagenumber = ceil($total[0]->total / (float)$amountperpage);
        
        return view('user.index', ['users' => $users, 'showpagination' => $showpagination, 'rowperpage' => $amountperpage, 'totalpagenumber' => $totalpagenumber, 'topagenumber' => $pagenumber, 'sortby' => $mysortby, 'order' => $myorder]);
    }

    public function create(Request $request)
    {
        if ($request->isMethod('get')) {
            $divisions = Division::where('status', 1)
			    ->orderBy('id')
                ->get();

            $roles = Role::where('status', 1)
			    ->orderBy('id')
                ->get();
				
			$users = User::where('status', 1)
			    ->orderBy('id')
                ->get();

            return view('user.create', ['divisions' => $divisions, 'roles' => $roles, 'users' => $users]);
        } else if ($request->isMethod('post')) {
			try{
				$input = $request->all();
				if(trim($input["first_name"]) === '' || trim($input["last_name"]) === '' || 
				   trim($input["email"]) === '' || trim($input["division"]) === '0' || 
				   trim($input["role"]) === '0' || trim($input["password"]) === '' || trim($input["reportto"]) === '0' ||
				   trim($input["password_confirmation"]) === '' || trim($input["username"]) === '') {
					return redirect('user/create')->with('status', 'Error: please fill all the required fields');
				} else if(trim($input["password"]) !== trim($input["password_confirmation"])) {
					return redirect('user/create')->with('status', 'Error: 2 password should be identical');
				} else {
					$postcheck = Validator:: make($input, array(
						'first_name' => 'required|string|max:255',
						'last_name' => 'required|string|max:255',
						'phone' => 'nullable|string|max:45',
						'address' => 'nullable|string|max:45',
						'address2' => 'nullable|string|max:45',
						'city' => 'nullable|string|max:45',
						'province' => 'nullable|string|max:45',
						'country' => 'nullable|string|max:45',
						'zip' => 'nullable|string|max:45',
						'email' => 'required|string|email|max:255|unique:users',
						'username' => 'required|string|max:255|unique:users',
						'password' => 'required|string|min:8|confirmed'
					));
					if ($postcheck-> fails()) {
						return redirect() -> to('/user/create')->withErrors($postcheck)->withInput();
					} else {
						$user = new User();
						$user->username = trim($input["username"]);
						$user->password = bcrypt(trim($input["password"]));
						$user->first_name = trim($input["first_name"]);
						$user->last_name = trim($input["last_name"]);
						$user->phone = empty($input["phone"]) ? null : trim($input["phone"]);
						$user->email = empty($input["email"]) ? null : trim($input["email"]);
						$user->address = empty($input["address"]) ? null : trim($input["address"]);
						$user->address2 = empty($input["address2"]) ? null : trim($input["address2"]);
						$user->city = empty($input["city"]) ? null : trim($input["city"]);
						$user->province = empty($input["province"]) ? null : trim($input["province"]);
						$user->country = empty($input["country"]) ? null : trim($input["country"]);
						$user->zip = empty($input["zip"]) ? null : trim($input["zip"]);
						$user->description = empty($input["desc"]) ? null : trim($input["desc"]);
						$user->status = intval(trim($input["status"]));
						$user->division = empty($input["division"]) ? null : trim($input["division"]);
						$user->role = empty($input["role"]) ? null : trim($input["role"]);
						$user->reportto = empty($input["reportto"]) ? null : trim($input["reportto"]);
						$user->hourlyrate = empty($input["hourlyrate"]) ? null : number_format(floatval($input["hourlyrate"]), 2);
						$user->yearlyrate = empty($input["yearlyrate"]) ? null : number_format(floatval($input["yearlyrate"]), 2);
						$user->created_at = Date('Y-m-d H:i:s');

						$user->save();

						$user2 = User::find($user->id);
						if($user2 !== null) {
							$file = $request->file('picture');
							if (!empty($file)) {
								if(self::headImagesExist($user->id)) {
									foreach(json_decode(UserHelpers::getConstants('USER_HEAD_IMAGE_TYPES_ARRAY'), true) as $k=>$temp) {
										if(file_exists(base_path() . '/public/images/users/' .  $user->id . '/head_' . $user->id . '.' . $temp)) {
											if(!unlink(base_path() . '/public/images/users/' .  $user->id . '/head_' . $user->id . '.' . $temp)) {
												redirect('user')->with('status', 'Delete old file failed. Please ask Administrator.');
											}
										}
									}
								}
								if($file->getClientSize() > intval(UserHelpers::getConstants('USER_HEAD_IMAGE_SIZE_LIMIT_INT'))) {
									redirect('user')->with('status', 'Upload failed. File too big. It should be less than 1MB. Please try again.');
								} else {
									$file->move(base_path() . '/public/images/users/' .  $user->id ,'head_' . $user->id . '.' . $file->getClientOriginalExtension());
								}
								$user2->picture = 'head_' . $user->id . '.' . $file->getClientOriginalExtension();
							}
							$user2->save();
						}
						return redirect('user');
					}
				}
			}catch(Exception $e) {
				return redirect('user')->with('status', 'Exception: ' . $e->getMessage());
			}
        }
    }

    public function edit(Request $request, $id)
    {
        if ($request->isMethod('get')) {
            $user = User::find($id);
            $divisions = Division::where('status', 1)
                ->select('id', 'name')
                ->orderBy('id')
                ->get();
            $roles = Role::where('status', 1)
                ->select('id', 'name')
                ->orderBy('id')
                ->get();				
			$users = User::where('status', 1)
			    ->select('id', 'first_name', 'last_name')
			    ->orderBy('id')
                ->get();
            //Log::info("at 220, user: " . json_encode($user));
            //Log::info("at 221, divisions: " . json_encode($divisions));
            //Log::info("at 222, roles: " . json_encode($roles));
            return view('user.edit', ['user' => $user, 'divisions' => $divisions, 'roles' => $roles, 'users' => $users]);
        } else if ($request->isMethod('post')) {
            try {
                $input = $request->all();
                $user = User::find($id);
				if($user === null){return redirect('user')->with('status', 'Error: user not found');}
                $user->first_name = $input["first_name"];
                $user->last_name = $input["last_name"];				
				$user->phone = empty($input["phone"]) ? null : trim($input["phone"]);
				$user->email = empty($input["email"]) ? null : trim($input["email"]);
				$user->address = empty($input["address"]) ? null : trim($input["address"]);
				$user->address2 = empty($input["address2"]) ? null : trim($input["address2"]);
				$user->city = empty($input["city"]) ? null : trim($input["city"]);
				$user->province = empty($input["province"]) ? null : trim($input["province"]);
				$user->country = empty($input["country"]) ? null : trim($input["country"]);
				$user->zip = empty($input["zip"]) ? null : trim($input["zip"]);
				$user->description = empty($input["desc"]) ? null : trim($input["desc"]);
				$user->status = intval(trim($input["status"]));
				$user->division = empty($input["division"]) ? null : trim($input["division"]);
				$user->role = empty($input["role"]) ? null : trim($input["role"]);
				$user->reportto = empty($input["reportto"]) ? null : trim($input["reportto"]);
				$user->hourlyrate = empty($input["hourlyrate"]) ? null : number_format(floatval($input["hourlyrate"]), 2);
				$user->yearlyrate = empty($input["yearlyrate"]) ? null : number_format(floatval($input["yearlyrate"]), 2);									
                $user->updated_at = Date('Y-m-d H:i:s');
				$mytemp = trim($input['ended_at']);
				if(!empty($mytemp)) {
                    $user->ended_at = Date('Y-m-d H:i:s', strtotime($input['ended_at']));
					$user->status = 0;
				}
                $file = $request->file('imgfile');
                if (!empty($file)) {
                    if(self::headImagesExist($id)) {
                        foreach(json_decode(UserHelpers::getConstants('USER_HEAD_IMAGE_TYPES_ARRAY'), true) as $k=>$temp) {
                            if(file_exists(base_path() . '/public/images/users/' . $id . '/head_' . $id . '.' . $temp)) {
                                if(!unlink(base_path() . '/public/images/users/' . $id . '/head_' . $id . '.' . $temp)) {
                                    redirect('user')->with('status', 'Delete old file failed. Please ask Administrator.');
                                }
                            }
                        }
                    }
                    if($file->getClientSize() > intval(UserHelpers::getConstants('USER_HEAD_IMAGE_SIZE_LIMIT_INT'))) {
                        redirect('user')->with('status', 'Upload failed. File too big. It should be less than 1MB. Please try again.');
                    } else {
                        $file->move(base_path() . '/public/images/users/' .  $id ,'head_' . $id . '.' . $file->getClientOriginalExtension());
                    }
                    $user->picture = 'head_' . $id . '.' . $file->getClientOriginalExtension();
                }
                $user->save();
				//if ended self, then redirect to login
				if(!empty($mytemp) && $id == UserHelpers::getUID()) {session_unset();Session::flush();Auth::logout();return view('auth.login');}
                return redirect('user');
            } catch (Exception $e) {
                return redirect('user')->with('status', 'Error: ' . $e->getMessage());
            }
        }
    }

    private function headImagesExist($userid) {
        if(!file_exists(base_path() . '/public/images/users/' .  $userid)) {
            mkdir(base_path() . '/public/images/users/' .  $userid, 0777, true);
        }
        foreach(json_decode(UserHelpers::getConstants('USER_HEAD_IMAGE_TYPES_ARRAY'), true) as $k=>$temp) {
            if(file_exists(base_path() . '/public/images/users/' .  $userid . '/head.' . $temp)) {return true;}
        }
        return false;
    }

    public function delete(Request $request, $id)
    {
		$ids = UserHelpers::getAssociatesForManager(Session::get('UID'));
		if(UserHelpers::getUID() != $id && (!empty($ids)) && in_array($id, $ids)) {
			$user = User::find($id);
			$user->status = 0;
			$user->save();
		}
        return redirect('user');
    }

    public function impersonate(Request $request, $id)
    {
        //first, is this user's role admin or person's manager?
        $user = DB::table('users AS t')
            ->leftJoin('role', 'role.id', '=', 't.role')
            ->select('t.*', 'role.name as rolename')
            ->where('t.status', 1)
            ->where('t.id', UserHelpers::getUID())
            ->get()->first();
        if($user->rolename === UserHelpers::getConstants('ADMIN_ROLE_NAME') || ($user->rolename === UserHelpers::getConstants('MANAGER_ROLE_NAME') && in_array($id, UserHelpers::getAssociatesForManager(Session::get('UID'))))) {
            session_unset();
            Session::put('UID', intval($id));
            return redirect('timesheet');
        } else {
            return redirect('timesheet')->with('status', 'You can not do this impersonate.');
        }
    }

    /*public function detail(Request $request, $id)
    {
        $user = User::find($id);
        return view('user.detail', ['user' => $user]);
    }*/
}
