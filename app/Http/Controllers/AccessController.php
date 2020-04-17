<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Access;
use App\Models\User;
use App\Models\Role;
use App\Models\Division;
use App\Libraries\Helpers\UserHelpers;
use Illuminate\Support\Facades\DB;
use Config;
use Log;

class AccessController extends Controller
{
    public function index(Request $request)
    {
        $divisions = Division::where('status', 1)
		    ->whereNotIn('id',[intval(UserHelpers::getConstants('ADMIN_DIVISION_ID'))])
            ->select('id', 'name', 'description')
            ->orderBy('id')
            ->get()->all(); 
        //Log::info("at 24, divisions: " . json_encode($divisions));
        //Each checkbox's id:
        //accessid(in the access table, primary key) | tab index | access read/edit/search/delete | access read/edit/search/delete value

        $input = $request->all(); 

        $accesses = Access::where('status', 1)
            ->orderBy('id')
            ->get()->all();

        $temps = Role::where('status', 1)
		    ->whereNotIn('id',[intval(UserHelpers::getConstants('ADMIN_ROLE_ID'))])
            ->select('id', 'name')
            ->orderBy('id')
            ->get()->all();

        $roles = array();
        foreach($temps as $temp) {
            $roles[$temp['id']] = $temp['name'];
        }

        $temps2 = User::where('status', 1)
            ->select('id', DB::raw("concat(first_name,' ',last_name) as name"))
            ->orderBy('id')
            ->get()->all();

        $users = array();
        foreach($temps2 as $temp) {
            $users[$temp['id']] = $temp['name'];
        }

        return view('access.index', ['accesses' => $accesses, 'divisions'=> $divisions, 'roles' => $roles, 'users' => $users]);
    }

    public function create(Request $request)
    {
        if ($request->isMethod('get')) {
            $divisions = Division::where('status', 1)
                ->select('id', 'name', 'description')
                ->orderBy('id')
                ->get()->all();

            //Log::info("at 66, divisions: " . json_encode($divisions));
            //Each checkbox's id:
            //accessid(in the access table, primary key) | tab index | access read/edit/search/delete | access read/edit/search/delete value

            $input = $request->all(); 

            $target_temp = Access::where('status', 1)
                ->select('target')
                ->orderBy('id')
                ->get()->all();

            $targets = array();
            foreach($target_temp as $temp) {
                $targets[] = strtolower($temp['target']);
            }
            //Log::info("at 81, targets: " . json_encode($targets));
            $roleaccesses = Access::where('status', 1)
                ->select('id', 'title', 'role')
                ->orderBy('id')
                ->get()->all();

            $useraccesses = Access::where('status', 1)
                ->select('id', 'title', 'employee')
                ->orderBy('id')
                ->get()->all();

            $userexceptionaccesses = Access::where('status', 1)
                ->select('id', 'title', 'exceptionemployee')
                ->orderBy('id')
                ->get()->all();

            $temps = Role::where('status', 1)
                ->select('id', 'name')
                ->orderBy('id')
                ->get()->all();

            $roles = array();
            foreach($temps as $temp) {
                $roles[$temp['id']] = $temp['name'];
            }

            $temps2 = User::where('status', 1)
                ->select('id', DB::raw("concat(first_name,' ',last_name) as name"))
                ->orderBy('id')
                ->get()->all();

            $users = array();
            foreach($temps2 as $temp) {
                $users[$temp['id']] = $temp['name'];
            }

            return view('access.create', ['targets' => $targets, 'roleaccesses' => $roleaccesses, 'useraccesses' => $useraccesses, 'userexceptionaccesses' => $userexceptionaccesses, 'divisions'=> $divisions, 'roles' => $roles, 'users' => $users]);
        } else if ($request->isMethod('post')) {
            $input = $request->all();

            $temp = Access::where(DB::raw('TRIM(name)'), trim($input["name"]))->get();
            $accesses = collect($temp)->map(function($x){ return (array) $x; })->toArray();
            //Log::info("at 123, accesses: " . json_encode($accesses));
            $status = '';
            if(count($accesses)) {
                $access = new Access();
                $access->name = trim($input["name"]);
                $access->description = $input["description"];
				date_default_timezone_set(UserHelpers::getConstants('TIMEZONE'));
                $access->created = date("Y-m-d H:i:s");
                $access->status = 1;
				$access->billable = 1;
                $access->save();
            } else {
                $status = 'duplicated';
            }
            return redirect('access')->with('status', $status);
        }
    }

    public function updateAccess(Request $request)
    {
        $status = '';
        $counter = 0;
        if ($request->isMethod('post')) {
            $input = $request->all();
            $data = $input["mydata"];
            $dataArr = json_decode($data, true);
            Log::info("at 148, dataArr: " . json_encode($dataArr));

            foreach ($dataArr as $k => $v) {
                $access = Access::find(intval($k));
                foreach($v as $key => $val) {
                    $myvalue = 0;
                    if($key === 'read' || $key === 'create' || $key === 'edit' || $key === 'search' || $key === 'delete') {
                        $valArr = explode("|", $val);
                        foreach($valArr as $temp) {
                            if(!empty($temp)) {
                                $myvalue += intval($temp);
                            }
                        }
                    }
                    switch($key) {
                        case "read" : $access->read = intval($access->read) + $myvalue; break;
                        case "create" : $access->create = intval($access->create) + $myvalue; break;
                        case "edit" : $access->edit = intval($access->edit) + $myvalue; break;
                        case "search" : $access->search = intval($access->search) + $myvalue; break;
                        case "delete" : $access->delete = intval($access->delete) + $myvalue; break;
                        case "role" : $access->role = $val; break;
                        case "employee" : $access->employee = UserHelpers::removeAssociatesFromUserArray(UserHelpers::getUID(), $access->employee) . "," . $val; break;
                        case "exceptionemployee" : $access->exceptionemployee = UserHelpers::removeAssociatesFromUserArray(UserHelpers::getUID(), $access->exceptionemployee) . "," . $val; break;
                    }
                }
                $access->save();
                $counter++;
            }
            if($counter === count($dataArr)) {
                $status = 'Your change has been saved';
            } else {
                $status = 'Some of your change has been saved';
            }
        } else {
            $status = 'Nothing was saved';
        }
        return redirect('access')->with('status', $status);
    }

     public function createAccess(Request $request)
    {
        $status = '';
        $counter = 0;
        if ($request->isMethod('post')) {
            $input = $request->all();
            $data = $input["mydata"];
            $dataArr = json_decode($data, true);
            //Log::info("at 193, dataArr: " . json_encode($dataArr));
            $access = new Access();  //::find(intval($k));
            foreach ($dataArr as $key => $val) {
                $myvalue = 0;
                if($key === 'read' || $key === 'create' || $key === 'edit' || $key === 'search' || $key === 'delete') {
                    $valArr = explode("|", $val);
                    foreach($valArr as $temp) {
                        if(!empty($temp)) {
                            $myvalue += intval($temp);
                        }
                    }
                }
                switch($key) {
                    case "read" : $access->read = $myvalue; break;
                    case "create" : $access->create = $myvalue; break;
                    case "edit" : $access->edit = $myvalue; break;
                    case "search" : $access->search = $myvalue; break;
                    case "delete" : $access->delete = $myvalue; break;
                    case "role" : $access->role = $val; break;
                    case "employee" : $access->employee = $val; break;
                    case "exceptionemployee" : $access->exceptionemployee = $val; break;
                    case "target" : $access->target = $val; break;
                }
                $counter++;
            }
			date_default_timezone_set(UserHelpers::getConstants('TIMEZONE'));
            $access->created = date("Y-m-d H:i:s");
            if(!empty($access->target)) {
                $access->save();
                if($counter === count($dataArr)) {
                    $status = 'Your input has been saved';
                } else {
                    $status = 'Some of your input has been saved';
                }
            } else {
                $status = 'Nothing was saved';
            }
        } else {
            $status = 'Nothing was saved';
        }
        return redirect('access')->with('status', $status);
    }

    public function delete(Request $request, $id)
    {
        $access = Access::find($id);
        $access->status = 0;
        $access->save();
        return redirect('access');
    }

    /*public function detail(Request $request, $id)
    {
        $access = Access::find($id);
        return view('access.detail', ['access' => $access]);
    }*/
}
