<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Libraries\Helpers\UserHelpers;
use Config;
use Log;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $input = $request->all(); 
        // a - amount per page; o - order (asc, desc); p - page number; s - sortby
        $amountperpage = empty($input['a']) ? intval(UserHelpers::getConstants('ROW_PER_TABLE_INT')) : intval($input['a']);
        $pagenumber = empty($input['p']) ? 1 : intval($input['p']);
        $offset = ($pagenumber - 1) * $amountperpage;
        $mysortby = empty($input['s']) ? 'id' : preg_replace("/[^.a-zA-Z0-9]+/", "", $input['s']);
        $myorder = empty($input['o']) ? 'asc' : (strtolower($input['o']) === "asc" ? "asc" : "desc");

        $roles = Role::where('status', 1)
            ->orderBy($mysortby, $myorder)
            ->offset($offset)->limit($amountperpage)
            ->get();

        $total = Role::where('status', 1)
            ->select(DB::raw('count(*) as total'))
            ->get();
        $showpagination = ($total[0]['total'] > intval(UserHelpers::getConstants('ROW_PER_TABLE_INT'))) ? true : false;
        $totalpagenumber = ceil($total[0]['total'] / (float)$amountperpage);

        return view('role.index', ['roles' => $roles, 'showpagination' => $showpagination, 'rowperpage' => $amountperpage, 'totalpagenumber' => $totalpagenumber, 'topagenumber' => $pagenumber, 'sortby' => $mysortby, 'order' => $myorder]);
    }

    public function create(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('role.create');
        } else if ($request->isMethod('post')) {
            $input = $request->all();   
            $role = new Role();
            $role->name = $input["name"];
            $role->description = $input["description"];
            $role->assumestarttime = $input["assumestarttime"];
            $role->assumeendtime = $input["assumeendtime"];
            $role->actualstarttime = $input["actualstarttime"];
            $role->actualendtime = $input["actualendtime"];
            $role->save();
            return redirect('role');
        }
    }

    public function edit(Request $request, $id)
    {
        if ($request->isMethod('get')) {
            $role = Role::find($id);
            return view('role.edit', ['role' => $role]);
        } else if ($request->isMethod('post')) {
            $input = $request->all();   
            $role = Role::find($id);
            $role->name = $input["name"];
            $role->description = $input["description"];
            $role->status = $input["status"];
            $role->save();    
            return redirect('role');
        }
    }

    public function delete(Request $request, $id)
    {
		if(UserHelpers::isAdmin()) {
			$role = Role::find($id);
			$role->status = 0;
			$role->save();
		}
        return redirect('role');
    }

    /*public function detail(Request $request, $id)
    {
        $role = Role::find($id);
        return view('role.detail', ['role' => $role]);
    }*/
}
