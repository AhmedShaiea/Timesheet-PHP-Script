<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Constant;
use App\Libraries\Helpers\UserHelpers;
use Config;
use Log;

class ConstantController extends Controller
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
        $constants = Constant::where('status', 1)
            ->orderBy($mysortby, $myorder)
            ->offset($offset)->limit($amountperpage)
            ->get();
        $total = Constant::where('status', 1)
            ->select(DB::raw('count(*) as total'))
            ->get();
        $showpagination = ($total[0]['total'] > intval(UserHelpers::getConstants('ROW_PER_TABLE_INT'))) ? true : false;
        $totalpagenumber = ceil($total[0]['total'] / (float)$amountperpage);

        return view('constant.index', ['constants' => $constants, 'showpagination' => $showpagination, 'rowperpage' => $amountperpage, 'totalpagenumber' => $totalpagenumber, 'topagenumber' => $pagenumber, 'sortby' => $mysortby, 'order' => $myorder]);
    }

    //constant name can end with: _ARRAY, _INT, _NUMBER
    public function create(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('constant.create');
        } else if ($request->isMethod('post')) {
            $input = $request->all();   
            $constant = new Constant();
            $constant->name = strtoupper($input["name"]);
            $constant->description = $input["desc"];
            $constant->status = intval($input["status"]);
            $constant->created = Date('Y-m-d H:i:s');
            $constant->save();
            return redirect('constant');
        }
    }

    public function edit(Request $request, $id)
    {
        if ($request->isMethod('get')) {
            $constant = Constant::find($id);
            return view('constant.edit', ['constant' => $constant]);
        } else if ($request->isMethod('post')) {
            $input = $request->all();   
            $constant = Constant::find($id);
            $constant->name = strtoupper($input["name"]);
            $constant->description = $input["desc"];
            $constant->status = intval($input["status"]);
            $constant->save();
            return redirect('constant');
        }
    }

    public function delete(Request $request, $id)
    {
        /*$constant = Constant::find($id);
        $constant->status = 0;
        $constant->save();*/
        return redirect('constant');
    }

    /*public function detail(Request $request, $id)
    {
        $constant = Constant::find($id);
        return view('constant.detail', ['constant' => $constant]);
    }*/
}
