<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Typecategory;
use App\Libraries\Helpers\UserHelpers;
use Config;
use Log;

class TypecategoryController extends Controller
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

        $typecategories = Typecategory::where('status', 1)
            ->orderBy($mysortby, $myorder)
            ->offset($offset)->limit($amountperpage)
            ->get();
        $total = Typecategory::where('status', 1)
            ->select(DB::raw('count(*) as total'))
            ->get();
        $showpagination = ($total[0]['total'] > intval(UserHelpers::getConstants('ROW_PER_TABLE_INT'))) ? true : false;
        $totalpagenumber = ceil($total[0]['total'] / (float)$amountperpage);
        return view('typecategory.index', ['typecategories' => $typecategories, 'showpagination' => $showpagination, 'rowperpage' => $amountperpage, 'totalpagenumber' => $totalpagenumber, 'topagenumber' => $pagenumber, 'sortby' => $mysortby, 'order' => $myorder]);
    }

    public function create(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('typecategory.create');
        } else if ($request->isMethod('post')) {
            $input = $request->all();

            $temp = Typecategory::where(DB::raw('TRIM(name)'), trim($input["name"]))->get();
            $typecategories = collect($temp)->map(function($x){ return (array) $x; })->toArray();
            //Log::info("at 45, typecategories: " . json_encode($typecategories));
            $status = '';
            if(count($typecategories) === 0) {
                $typecategory = new Typecategory();
                $typecategory->name = trim($input["name"]);   //str_replace(' ', '_', trim($input["name"]));
                $typecategory->description = $input["desc"];
				date_default_timezone_set(UserHelpers::getConstants('TIMEZONE'));
                $typecategory->created = date("Y-m-d H:i:s");
                $typecategory->status = 1;
                $typecategory->save();
            } else {
                $status = 'duplicated';
            }
            return redirect('typecategory')->with('status', $status);
        }
    }

    public function edit(Request $request, $id)
    {
        if ($request->isMethod('get')) {
            $typecategory = Typecategory::find($id);
            return view('typecategory.edit', ['typecategory' => $typecategory]);
        } else if ($request->isMethod('post')) {
            $input = $request->all();
            $typecategory = Typecategory::find($id);
            $typecategory->name = trim($input["name"]);   //str_replace(' ', '_', trim($input["name"]));
            $typecategory->description = $input["desc"];
            $typecategory->save();
            return redirect('typecategory');
        }
    }

    public function delete(Request $request, $id)
    {				
		if(UserHelpers::isAdmin()) {
			DB::table('type')->where('typecategoryid', '=', $id)->delete();
			$typecategory = Typecategory::find($id);
			//$typecategory->status = 0;
			$typecategory->delete();
		}
        return redirect('typecategory');
    }
        
    /*public function detail(Request $request, $id)
    {
        $typecategory = Typecategory::find($id);
        return view('typecategory.detail', ['typecategory' => $typecategory]);
    }*/
}
