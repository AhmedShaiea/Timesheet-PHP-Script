<?php

namespace App\Libraries;

use App\Models\User;
use Illuminate\Support\Facades\DB;


class Helpers
{
    public static function getAssociatesForManager(int $managerid) {
        $temps = User::where('status', 1)
            ->where('reportto', $managerid)
            ->select('id')
            ->orderBy('id')
            ->get()->all();
        //[{"id":2},{"id":5}] 
        $users = array();
        foreach($temps as $k => $v) {
            $users[] = $v->id;
        }
        return $users;
    }
}
