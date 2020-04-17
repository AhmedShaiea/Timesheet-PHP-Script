<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Type;
use App\Models\Typecategory;
use App\Models\User;
use App\Models\Role;
use App\Models\Division;
use Illuminate\Support\Facades\DB;
use App\Libraries\Helpers\UserHelpers;
use Config;
use Log;

class TypeController extends Controller
{
    public function index(Request $request)
    {
        $input = $request->all();
        // a - amount per page; o - order (asc, desc); p - page number; s - sortby
        $amountperpage = empty($input['a']) ? intval(UserHelpers::getConstants('ROW_PER_TABLE_INT')) : intval($input['a']);
        $pagenumber = empty($input['p']) ? 1 : intval($input['p']);
        $offset = ($pagenumber - 1) * $amountperpage;
        $mysortby = empty($input['s']) ? 't.id' : preg_replace("/[^.a-zA-Z0-9]+/", "", $input['s']);
        $myorder = empty($input['o']) ? 'asc' : (strtolower($input['o']) === "asc" ? "asc" : "desc");
        $types = DB::table('type AS t')
            ->join('typecategory', 'typecategory.id', '=', 't.typecategoryid')
            ->where('t.status', 1)
            ->where('typecategory.status', 1)
            ->select('t.*', 'typecategory.name as typecategoryname')
            ->orderBy($mysortby, $myorder)
            ->offset($offset)->limit($amountperpage)
            ->get();
        $total = Type::where('status', 1)
            ->select(DB::raw('count(*) as total'))
            ->get();
        $showpagination = ($total[0]['total'] > intval(UserHelpers::getConstants('ROW_PER_TABLE_INT'))) ? true : false;
        $totalpagenumber = ceil($total[0]['total'] / (float)$amountperpage);

        return view('type.index', ['types' => $types, 'showpagination' => $showpagination, 'rowperpage' => $amountperpage, 'totalpagenumber' => $totalpagenumber, 'topagenumber' => $pagenumber, 'sortby' => $mysortby, 'order' => $myorder]);
    }

    public function create(Request $request)
    {
        if ($request->isMethod('get')) {
            $typecategories = Typecategory::where('status', 1)
                ->orderBy('name', 'asc')
                ->get();

            $divisions = Division::where('status', 1)
				->whereNotIn('id',[intval(UserHelpers::getConstants('ADMIN_DIVISION_ID'))])
                ->select('id', 'name', 'description')
                ->orderBy('id')
                ->get()->all(); 

            //Log::info("at 56, divisions: " . json_encode($divisions));
            //Each checkbox's id:
            //typeid(in the type table, primary key) | tab index | type read/edit/delete/search | type read/edit/delete/search value

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

            return view('type.create', ['typecategories' => $typecategories, 'divisions'=> $divisions, 'roles' => $roles, 'users' => $users]);
        } else if ($request->isMethod('post')) {
            //no duplicated type name
            $input = $request->all();
            $temp = Type::where(DB::raw('TRIM(name)'), trim($input["name"]))->get();
            $types = collect($temp)->map(function($x){ return (array) $x; })->toArray();
            $status = '';
            if(count($types) === 0) {
                $type = new Type();
                $type->name = $input["name"];
                $type->description = $input["desc"];
                $type->typecategoryid = $input["typecategory"];
                $type->billable = $input["billable"];
				date_default_timezone_set(UserHelpers::getConstants('TIMEZONE'));
                $type->created = date("Y-m-d H:i:s");
                $type->status = 1;
                $type->save();
            } else {
                $status = 'duplicated';
            }
            return redirect('type')->with('status', $status);
        }
    }

    public function edit(Request $request, $id)
    {
        if ($request->isMethod('get')) {
            $type = Type::find($id);
            $typecategories = Typecategory::where('status', 1)
                ->orderBy('name', 'asc')
                ->get();

            $divisions = Division::where('status', 1)
				->whereNotIn('id',[intval(UserHelpers::getConstants('ADMIN_DIVISION_ID'))])
                ->select('id', 'name', 'description')
                ->orderBy('id')
                ->get()->all(); 

            //Log::info("at 116, divisions: " . json_encode($divisions));
            //Each checkbox's id:
            //typeid(in the type table, primary key) | tab index | type read/update/search | type read/update/search value 

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

            return view('type.edit', ['type' => $type, 'typecategories' => $typecategories, 'divisions'=> $divisions, 'roles' => $roles, 'users' => $users]);
        } else if ($request->isMethod('post')) {
            $input = $request->all();
            $type = Type::find($id);
            $type->name = $input["name"];
            $type->description = $input["desc"];
            $type->typecategoryid = $input["typecategory"];
            $type->billable = $input["billable"];
            $type->save();    
            return redirect('type');
        }
    }

    public function createtype(Request $request)
    {
        $status = '';
        $counter = 0;
        if ($request->isMethod('post')) {
            $input = $request->all();   
            $data = $input["mydata"];
            $dataArr = json_decode($data, true);
            //Log::info("at 165, dataArr: " . json_encode($dataArr["type"]));
            $type = new Type();
            foreach ($dataArr["type"] as $key => $val) {
                $myvalue = 0;
                if($key === 'read' || $key === 'create' || $key === 'edit' || $key === 'delete' || $key === 'search') {
                    if($val !== ''){
                        $valArr = explode("|", $val);
                        foreach($valArr as $temp) {
                            if(!empty($temp)) {
                                $myvalue += intval($temp);
                            }
                        }
                    }
                }
                switch($key) {
                    case "read" : $type->read = $myvalue; break;
                    case "create" : $type->create = $myvalue; break;
                    case "edit" : $type->edit = $myvalue; break;
                    case "delete" : $type->delete = $myvalue; break;
                    case "search" : $type->search = $myvalue; break;
                    case "role" : $type->role = $val; break;
                    case "employee" : $type->employee = $val; break;
                    case "exceptionemployee" : $type->exceptionemployee = $val; break;
                }
                $counter++;
            }
            $type->typecategoryid = intval($dataArr["typecategory"]);
            $type->name = $dataArr["name"];
            $type->description = $dataArr["desc"];
            $type->status = 1;
            $type->billable = intval($dataArr["billable"]);
			date_default_timezone_set(UserHelpers::getConstants('TIMEZONE'));
            $type->created = date("Y-m-d H:i:s");
            if(!empty($type->typecategoryid)) {
                $type->save();
                if($counter === count($dataArr["type"])) {
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
        return redirect('type')->with('status', $status);
    }

    public function edittype(Request $request, $id)
    {
        $status = '';
        $counter = 0;
        if ($request->isMethod('post')) {
            $input = $request->all();
            $data = $input["mydata"];
            $dataArr = json_decode($data, true);
            //Log::info("at 221, dataArr: " . json_encode($dataArr["type"]));
            $type = Type::find($id);
            foreach ($dataArr["type"] as $key => $val) {
                $myvalue = 0;
                if($key === 'read' || $key === 'create' || $key === 'edit' || $key === 'delete' || $key === 'search') {
                    if($val !== ''){
                        $valArr = explode("|", $val);
                        foreach($valArr as $temp) {
                            if(!empty($temp)) {
                                $myvalue += intval($temp);
                            }
                        }
                    }
                }
                switch($key) {
                    case "read" : $type->read = intval($type->read) + $myvalue; break;
                    case "create" : $type->create = intval($type->create) + $myvalue; break;
                    case "edit" : $type->edit = intval($type->edit) + $myvalue; break;
                    case "delete" : $type->delete = intval($type->delete) + $myvalue; break;
                    case "search" : $type->search = intval($type->search) + $myvalue; break;
                    case "role" : $type->role = $val; break;
                    case "employee" : $type->employee = UserHelpers::removeAssociatesFromUserArray(UserHelpers::getUID(), $type->employee) . "," . $val; break;
                    case "exceptionemployee" : $type->exceptionemployee = UserHelpers::removeAssociatesFromUserArray(UserHelpers::getUID(), $type->exceptionemployee) . "," . $val; break;
                }
                $counter++;
            }
            $type->typecategoryid = intval($dataArr["typecategory"]);
            $type->name = $dataArr["name"];
            $type->description = $dataArr["desc"];
            $type->status = 1;
            $type->billable = intval($dataArr["billable"]);
            if(!empty($type->typecategoryid)) {
                $type->save();
                if($counter === count($dataArr["type"])) {
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
        return redirect('type')->with('status', $status);
    }

    public function delete(Request $request, $id)
    {
		if(UserHelpers::isAdmin()) {
			$type = Type::find($id);
			$type->status = 0;
			$type->save();
		}
        return redirect('type');
    }

    /*public function detail(Request $request, $id)
    {
        $type = Type::find($id);
        return view('type.detail', ['type' => $type]);
    }*/
}
