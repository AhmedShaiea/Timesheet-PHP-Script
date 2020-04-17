<?php

namespace App\Libraries\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class AccessHelpers
{
    public static function accessPages(int $userid) {
        //get userid's division id, role id
        $division_temp = User::where($userid)
            ->select('division', 'role')
            ->get()->all();
        $division_id = $division_temp[0]["division"];
        $role_id = $division_temp[0]["role"];

        //get associates' ids
        $associates_temp = User::where('reportto', $userid)
            ->select('id')
            ->get()->all();

        $associates = array();
        foreach($associates_temp as $k => $v) {
            $associates[] = $v->id;
        }

        $temps = Access::where('status', 1)
            ->select('id')
            ->orderBy('id')
            ->get()->all();
        //[{"id":2},{"id":5}] 
        $users = array();
        foreach($temps as $k => $v) {
            $users[] = $v->id;
        }
    }
}
