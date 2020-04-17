<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use Illuminate\Support\Facades\DB;
use App\Libraries\Helpers\UserHelpers;
use Config;
use Log;

class DivisionController extends Controller
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

        $divisions = Division::where('status', 1)
            ->orderBy($mysortby, $myorder)
            ->offset($offset)->limit($amountperpage)
            ->get();

        $total = Division::where('status', 1)
            ->select(DB::raw('count(*) as total'))
            ->get();
        $showpagination = ($total[0]['total'] > intval(UserHelpers::getConstants('ROW_PER_TABLE_INT'))) ? true : false;
        $totalpagenumber = ceil($total[0]['total'] / (float)$amountperpage);
        return view('division.index', ['divisions' => $divisions, 'showpagination' => $showpagination, 'rowperpage' => $amountperpage, 'totalpagenumber' => $totalpagenumber, 'topagenumber' => $pagenumber, 'sortby' => $mysortby, 'order' => $myorder]);
    }

    public function create(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('division.create');
        } else if ($request->isMethod('post')) {
            $input = $request->all();   			
			$maxId = DB::table('division')->max('id');			
			
            $division = new Division();
			$division->id = $maxId ? ($maxId * 2) : 1;
            $division->name = trim($input["name"]);
            $division->description = trim($input["description"]);
            $division->created = Date('Y-m-d H:i:s');
			$division->status = 1;
            $division->save();
            return redirect('division');
        }
    }

    public function edit(Request $request, $id)
    {
        if ($request->isMethod('get')) {
            $division = Division::find($id);
            return view('division.edit', ['division' => $division]);
        } else if ($request->isMethod('post')) {
            $input = $request->all();   
            $division = Division::find($id);
            $division->name = trim($input["name"]);
            $division->description = trim($input["description"]);
            $division->save();
            return redirect('division');
        }
    }

    public function delete(Request $request, $id)
    {
		if(UserHelpers::isAdmin()) {
			$division = Division::find($id);
			$division->status = 0;
			$division->save();  
		}
        return redirect('division');
    }

    /*public function detail(Request $request, $id)
    {
        $division = Division::find($id);
        return view('division.detail', ['division' => $division]);
    }*/
}
