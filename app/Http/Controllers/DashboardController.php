<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Timesheet;
use App\Models\User;
use App\Models\timesheet_type_user;
use App\Libraries\Helpers\UserHelpers;
use Log;

class DashboardController extends Controller
{
    public function index()
    {
        $items = DB::table('timesheet_type_user')
                          ->select(
                            DB::raw("min(concat(type.typecategoryid,'|',typecategory.name)) as c"),
                            DB::raw("min(concat(timesheet_type_user.typeid,'|',type.name)) as t"),
                            DB::raw('TRUNCATE(sum(TIMESTAMPDIFF(MINUTE,timesheet_type_user.starttime,timesheet_type_user.endtime))/60, 1) as h')
                          )
                          ->join('timesheet', function ($join) {
                                $join->on('timesheet.id', '=', 'timesheet_type_user.timesheetid')
                                     ->where('timesheet.status', '=', 1)
                                     ->where('timesheet.approved', '=', 1);
                            })
                          ->leftJoin('type', 'timesheet_type_user.typeid', '=', 'type.id')
                          ->leftJoin('typecategory', 'type.typecategoryid', '=', 'typecategory.id')
                          ->groupBy('type.typecategoryid', 'type.id')
                          ->where('type.status', '=', 1)
                          ->where('timesheet_type_user.status', '=', 1)
                          ->orderBy('type.typecategoryid', 'ASC')
                          ->get();
        //Log::info("at 35, items: " . json_encode($items));
        $result = array();
        $current_key = '';
        foreach($items as $item) {
            if($current_key === '') {
                $current_key = $item->c;
            } else if($item->c !== $current_key) {
                if(!isset($result[$item->c])) {
                    $result[$item->c] = array();
                }
            }
            $result[$item->c][$item->t] = floatval($item->h);
        }

        //Log::info("at 49, result: " . json_encode($result));
        $temps2 = User::where('status', 1)
            ->select('id', DB::raw("concat(first_name,' ',last_name) as name"))
            ->orderBy('id')
            ->get()->all();    

        $users = array();
        $usersReportToThisUser = UserHelpers::getAssociatesForManager(parent::getUID());
        foreach($temps2 as $temp) {
			if(UserHelpers::isAdmin()) {
				$users[$temp['id']] = $temp['name'];
            } else if(in_array($temp['id'], $usersReportToThisUser)) {
                $users[$temp['id']] = $temp['name'];
            } else if($temp['id'] === parent::getUID()){
                $users[$temp['id']] = $temp['name'];
            }
        }

        $temps3 = DB::table('type AS t')
            ->leftJoin('typecategory AS c', 'c.id', '=', 't.typecategoryid')
            ->where('t.status', 1)
            ->where('c.status', 1)
            ->select("t.typecategoryid as mykey", DB::raw("group_concat(concat(t.id,'_',t.name) SEPARATOR '|') as list"))
            ->groupBy('t.typecategoryid')
            ->orderBy('mykey', 'asc')
            ->get();

        $types = array();
        foreach($temps3 as $val) {
            $mytemp = array();
            foreach($val as $k => $v) {
                $mytemp[] = $v;
            }
            $types[$mytemp[0]] = $mytemp[1];
        }
        //Log::info("at 84, types: " . json_encode($types));
        return view('dashboard.index', ['items' => $result, 'users' => $users, 'types' => $types]);
    }

    public function getHoursByEachUser(Request $request) {
        if ($request->isMethod('post')) {
            $input = $request->all();
            //Log::info("at 89, input: " . json_encode($input));

            if(!empty($input['userid']) && !empty($input['typecategoryid'])) {
                $items = null;
                if(empty($input['from']) && empty($input['to'])) {
                    $items = DB::table('timesheet_type_user')
                          ->select(
                            DB::raw("min(concat(type.typecategoryid,'|',typecategory.name)) as c"),
                            DB::raw("min(concat(timesheet_type_user.typeid,'|',type.name)) as t"),
                            DB::raw('TRUNCATE(sum(TIMESTAMPDIFF(MINUTE,timesheet_type_user.starttime,timesheet_type_user.endtime))/60, 1) as h')
                          )
                          ->join('timesheet', function ($join) {
                                $join->on('timesheet.id', '=', 'timesheet_type_user.timesheetid')
                                     ->where('timesheet.status', '=', 1)
                                     ->where('timesheet.approved', '=', 1);
                            })
                          ->leftJoin('type', 'timesheet_type_user.typeid', '=', 'type.id')
                          ->leftJoin('typecategory', 'type.typecategoryid', '=', 'typecategory.id')
                          ->groupBy('type.typecategoryid', 'type.id')
                          ->where('type.status', '=', 1)
                          ->where('typecategory.id', '=', intval($input['typecategoryid']))
                          ->where('timesheet_type_user.status', '=', 1)
                          ->where('timesheet_type_user.userid', '=', intval($input['userid']))
                          ->orderBy('type.typecategoryid', 'ASC')
                          ->get();
                } else if(!empty($input['from']) && empty($input['to'])) {
                    if(strtotime($input['from']) !== FALSE) {
                        $items = DB::table('timesheet_type_user')
                              ->select(
                                DB::raw("min(concat(type.typecategoryid,'|',typecategory.name)) as c"),
                                DB::raw("min(concat(timesheet_type_user.typeid,'|',type.name)) as t"),
                                DB::raw('TRUNCATE(sum(TIMESTAMPDIFF(MINUTE,timesheet_type_user.starttime,timesheet_type_user.endtime))/60, 1) as h')
                              )
                              ->join('timesheet_timerange', function ($join) use($input) {
                                    $join->on('timesheet_timerange.timesheetid', '=', 'timesheet_type_user.timesheetid')
                                         ->where('timesheet_timerange.fordate', '>=', trim($input['from']));
                                })
                              ->join('timesheet', function ($join) {
                                    $join->on('timesheet.id', '=', 'timesheet_type_user.timesheetid')
                                         ->where('timesheet.status', '=', 1)
                                         ->where('timesheet.approved', '=', 1);
                                })
                              ->leftJoin('type', 'timesheet_type_user.typeid', '=', 'type.id')
                              ->leftJoin('typecategory', 'type.typecategoryid', '=', 'typecategory.id')
                              ->groupBy('type.typecategoryid', 'type.id')
                              ->where('type.status', '=', 1)
                              ->where('typecategory.id', '=', intval($input['typecategoryid']))
                              ->where('timesheet_type_user.status', '=', 1)
                              ->where('timesheet_type_user.userid', '=', intval($input['userid']))
                              ->orderBy('type.typecategoryid', 'ASC')
                              ->get();
                    }
                } else if(empty($input['from']) && !empty($input['to'])) {
                    if(strtotime($input['to']) !== FALSE) {
                        $items = DB::table('timesheet_type_user')
                              ->select(
                                DB::raw("min(concat(type.typecategoryid,'|',typecategory.name)) as c"),
                                DB::raw("min(concat(timesheet_type_user.typeid,'|',type.name)) as t"),
                                DB::raw('TRUNCATE(sum(TIMESTAMPDIFF(MINUTE,timesheet_type_user.starttime,timesheet_type_user.endtime))/60, 1) as h')
                              )
                              ->join('timesheet_timerange', function ($join) use($input) {
                                    $join->on('timesheet_timerange.timesheetid', '=', 'timesheet_type_user.timesheetid')
                                         ->where('timesheet_timerange.fordate', '<=', trim($input['to']));
                                })
                              ->join('timesheet', function ($join) {
                                    $join->on('timesheet.id', '=', 'timesheet_type_user.timesheetid')
                                         ->where('timesheet.status', '=', 1)
                                         ->where('timesheet.approved', '=', 1);
                                })
                              ->leftJoin('type', 'timesheet_type_user.typeid', '=', 'type.id')
                              ->leftJoin('typecategory', 'type.typecategoryid', '=', 'typecategory.id')
                              ->groupBy('type.typecategoryid', 'type.id')
                              ->where('type.status', '=', 1)
                              ->where('typecategory.id', '=', intval($input['typecategoryid']))
                              ->where('timesheet_type_user.status', '=', 1)
                              ->where('timesheet_type_user.userid', '=', intval($input['userid']))
                              ->orderBy('type.typecategoryid', 'ASC')
                              ->get();
                    }
                } else if(!empty($input['from']) && !empty($input['to'])) {
                    if(strtotime($input['from']) !== FALSE && strtotime($input['to']) !== FALSE) {
                        $items = DB::table('timesheet_type_user')
                              ->select(
                                DB::raw("min(concat(type.typecategoryid,'|',typecategory.name)) as c"),
                                DB::raw("min(concat(timesheet_type_user.typeid,'|',type.name)) as t"),
                                DB::raw('TRUNCATE(sum(TIMESTAMPDIFF(MINUTE,timesheet_type_user.starttime,timesheet_type_user.endtime))/60, 1) as h')
                              )
                              ->join('timesheet_timerange', function ($join) use($input) {
                                    $join->on('timesheet_timerange.timesheetid', '=', 'timesheet_type_user.timesheetid')
                                         ->where('timesheet_timerange.fordate', '>=', trim($input['from']))
                                         ->where('timesheet_timerange.fordate', '<=', trim($input['to']));
                                })
                              ->join('timesheet', function ($join) {
                                    $join->on('timesheet.id', '=', 'timesheet_type_user.timesheetid')
                                         ->where('timesheet.status', '=', 1)
                                         ->where('timesheet.approved', '=', 1);
                                })
                              ->leftJoin('type', 'timesheet_type_user.typeid', '=', 'type.id')
                              ->leftJoin('typecategory', 'type.typecategoryid', '=', 'typecategory.id')
                              ->groupBy('type.typecategoryid', 'type.id')
                              ->where('type.status', '=', 1)
                              ->where('typecategory.id', '=', intval($input['typecategoryid']))
                              ->where('timesheet_type_user.status', '=', 1)
                              ->where('timesheet_type_user.userid', '=', intval($input['userid']))
                              ->orderBy('type.typecategoryid', 'ASC')
                              ->get();
                    }
                }
                $result = array();
                $current_key = '';
                //Log::info("at 199, items: " . json_encode($items));
                foreach($items as $item) {
                    if($current_key === '') {
                        $current_key = $item->c;
                    } else if($item->c !== $current_key) {
                        if(!isset($result[$item->c])) {
                            $result[$item->c] = array();
                        }
                    } else if($item->c === $current_key) {
                    }
                    $result[$item->c][$item->t] = floatval($item->h);
                }
                //Log::info("at 211, result: " . json_encode($result));
                $mydata = array();
                $typecategoryname = '';
                foreach ($result as $t => $item) {
                    $temp0 = explode('|', $t, 2);
                    $typecategoryname = $temp0[1];
                    foreach($item as $k => $v) {
                        $temp2 = explode('|', $k, 2);
                        //no duplicate type and typecategory name
                        $mydata[$temp2[1]] = $v;
                    }
                }
            
                return json_encode(array('typecategoryname'=>$typecategoryname, 'mydata'=>$mydata));
            } else {
                return '';
            }
        }
    }

    public function getHoursByEachType(Request $request) {
        if ($request->isMethod('post')) {
            $input = $request->all();
            //Log::info("at 234, input: " . json_encode($input));

            if(!empty($input['typeid']) && !empty($input['typecategoryid'])) {
                $items = null;
                if(empty($input['from']) && empty($input['to'])) {
                    $items = DB::table('timesheet_type_user')
                          ->select(
                            DB::raw("min(concat(type.typecategoryid,'|',typecategory.name)) as c"),
                            DB::raw("min(concat(timesheet_type_user.typeid,'|',type.name)) as t"),
                            DB::raw('TRUNCATE(sum(TIMESTAMPDIFF(MINUTE,timesheet_type_user.starttime,timesheet_type_user.endtime))/60, 1) as h')
                          )
                          ->join('timesheet', function ($join) {
                                $join->on('timesheet.id', '=', 'timesheet_type_user.timesheetid')
                                     ->where('timesheet.status', '=', 1)
                                     ->where('timesheet.approved', '=', 1);
                            })
                          ->leftJoin('type', 'timesheet_type_user.typeid', '=', 'type.id')
                          ->leftJoin('typecategory', 'type.typecategoryid', '=', 'typecategory.id')
                          ->groupBy('type.typecategoryid', 'type.id')
                          ->where('type.status', '=', 1)
                          ->where('typecategory.id', '=', intval($input['typecategoryid']))
                          ->where('timesheet_type_user.status', '=', 1)
                          ->where('timesheet_type_user.typeid', '=', intval($input['typeid']))
                          ->orderBy('type.typecategoryid', 'ASC')
                          ->get();
                } else if(!empty($input['from']) && empty($input['to'])) {
                    if(strtotime($input['from']) !== FALSE) {
                        $items = DB::table('timesheet_type_user')
                              ->select(
                                DB::raw("min(concat(type.typecategoryid,'|',typecategory.name)) as c"),
                                DB::raw("min(concat(timesheet_type_user.typeid,'|',type.name)) as t"),
                                DB::raw('TRUNCATE(sum(TIMESTAMPDIFF(MINUTE,timesheet_type_user.starttime,timesheet_type_user.endtime))/60, 1) as h')
                              )
                              ->join('timesheet_timerange', function ($join) use($input) {
                                    $join->on('timesheet_timerange.timesheetid', '=', 'timesheet_type_user.timesheetid')
                                         ->where('timesheet_timerange.fordate', '>=', trim($input['from']));
                                })
                              ->join('timesheet', function ($join) {
                                    $join->on('timesheet.id', '=', 'timesheet_type_user.timesheetid')
                                         ->where('timesheet.status', '=', 1)
                                         ->where('timesheet.approved', '=', 1);
                                })
                              ->leftJoin('type', 'timesheet_type_user.typeid', '=', 'type.id')
                              ->leftJoin('typecategory', 'type.typecategoryid', '=', 'typecategory.id')
                              ->groupBy('type.typecategoryid', 'type.id')
                              ->where('type.status', '=', 1)
                              ->where('typecategory.id', '=', intval($input['typecategoryid']))
                              ->where('timesheet_type_user.status', '=', 1)
                              ->where('timesheet_type_user.typeid', '=', intval($input['typeid']))
                              ->orderBy('type.typecategoryid', 'ASC')
                              ->get();
                    }
                } else if(empty($input['from']) && !empty($input['to'])) {
                    if(strtotime($input['to']) !== FALSE) {
                        $items = DB::table('timesheet_type_user')
                              ->select(
                                DB::raw("min(concat(type.typecategoryid,'|',typecategory.name)) as c"),
                                DB::raw("min(concat(timesheet_type_user.typeid,'|',type.name)) as t"),
                                DB::raw('TRUNCATE(sum(TIMESTAMPDIFF(MINUTE,timesheet_type_user.starttime,timesheet_type_user.endtime))/60, 1) as h')
                              )
                              ->join('timesheet_timerange', function ($join) use($input) {
                                    $join->on('timesheet_timerange.timesheetid', '=', 'timesheet_type_user.timesheetid')
                                         ->where('timesheet_timerange.fordate', '<=', trim($input['to']));
                                })
                              ->join('timesheet', function ($join) {
                                    $join->on('timesheet.id', '=', 'timesheet_type_user.timesheetid')
                                         ->where('timesheet.status', '=', 1)
                                         ->where('timesheet.approved', '=', 1);
                                })
                              ->leftJoin('type', 'timesheet_type_user.typeid', '=', 'type.id')
                              ->leftJoin('typecategory', 'type.typecategoryid', '=', 'typecategory.id')
                              ->groupBy('type.typecategoryid', 'type.id')
                              ->where('type.status', '=', 1)
                              ->where('typecategory.id', '=', intval($input['typecategoryid']))
                              ->where('timesheet_type_user.status', '=', 1)
                              ->where('timesheet_type_user.typeid', '=', intval($input['typeid']))
                              ->orderBy('type.typecategoryid', 'ASC')
                              ->get();
                    }
                } else if(!empty($input['from']) && !empty($input['to'])) {
                    if(strtotime($input['from']) !== FALSE && strtotime($input['to']) !== FALSE) {
                        $items = DB::table('timesheet_type_user')
                              ->select(
                                DB::raw("min(concat(type.typecategoryid,'|',typecategory.name)) as c"),
                                DB::raw("min(concat(timesheet_type_user.typeid,'|',type.name)) as t"),
                                DB::raw('TRUNCATE(sum(TIMESTAMPDIFF(MINUTE,timesheet_type_user.starttime,timesheet_type_user.endtime))/60, 1) as h')
                              )
                              ->join('timesheet_timerange', function ($join) use($input) {
                                    $join->on('timesheet_timerange.timesheetid', '=', 'timesheet_type_user.timesheetid')
                                         ->where('timesheet_timerange.fordate', '>=', trim($input['from']))
                                         ->where('timesheet_timerange.fordate', '<=', trim($input['to']));
                                })
                              ->join('timesheet', function ($join) {
                                    $join->on('timesheet.id', '=', 'timesheet_type_user.timesheetid')
                                         ->where('timesheet.status', '=', 1)
                                         ->where('timesheet.approved', '=', 1);
                                })
                              ->leftJoin('type', 'timesheet_type_user.typeid', '=', 'type.id')
                              ->leftJoin('typecategory', 'type.typecategoryid', '=', 'typecategory.id')
                              ->groupBy('type.typecategoryid', 'type.id')
                              ->where('type.status', '=', 1)
                              ->where('typecategory.id', '=', intval($input['typecategoryid']))
                              ->where('timesheet_type_user.status', '=', 1)
                              ->where('timesheet_type_user.typeid', '=', intval($input['typeid']))
                              ->orderBy('type.typecategoryid', 'ASC')
                              ->get();
                    }
                }
                $result = array();
                $current_key = '';
                //Log::info("at 344, items: " . json_encode($items));
                foreach($items as $item) {
                    if($current_key === '') {
                        $current_key = $item->c;
                    } else if($item->c !== $current_key) {
                        if(!isset($result[$item->c])) {
                            $result[$item->c] = array();
                        }
                    } else if($item->c === $current_key) {
                    }
                    $result[$item->c][$item->t] = floatval($item->h);
                }
                //Log::info("at 356, result: " . json_encode($result));
                $mydata = array();
                $typecategoryname = '';
                foreach ($result as $t => $item) {
                    $temp0 = explode('|', $t, 2);
                    $typecategoryname = $temp0[1];
                    foreach($item as $k => $v) {
                        $temp2 = explode('|', $k, 2);
                        //no duplicate type and typecategory name
                        $mydata[$temp2[1]] = $v;
                    }
                }
            
                return json_encode(array('typecategoryname'=>$typecategoryname, 'mydata'=>$mydata));
            } else {
                return '';
            }
        }
    }
}
