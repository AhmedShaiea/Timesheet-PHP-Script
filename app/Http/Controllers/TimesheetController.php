<?php

namespace App\Http\Controllers;

use App\Mail\TimesheetCreated;
use App\Mail\TimesheetEdited;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Timesheet;
use App\Models\Timesheet_timerange;
use App\Models\Timesheet_type_user;
use App\Models\Type;
use App\Models\Typecategory;
use App\Libraries\Helpers\UserHelpers;
use Config;
use Session;
use Log;
use App;

class TimesheetController extends Controller
{
    public function index(Request $request)
    {
        if(parent::verifyUID()) {
            $input = $request->all(); 
            // a - amount per page; o - order (asc, desc); p - page number; s - sortby
            $amountperpage = empty($input['a']) ? intval(UserHelpers::getConstants('ROW_PER_TABLE_INT')) : intval($input['a']);
            $pagenumber = empty($input['p']) ? 1 : intval($input['p']);
            $offset = ($pagenumber - 1) * $amountperpage;
            $mysortby = empty($input['s']) ? 't.id' : preg_replace("/[^.a-zA-Z0-9]+/", "", $input['s']);
            $myorder = empty($input['o']) ? 'asc' : (strtolower($input['o']) === "asc" ? "asc" : "desc");

            $sql =  "SELECT t.id,group_concat(DISTINCT t.userid) as 'userid',group_concat(DISTINCT t.name) as 'name', group_concat(DISTINCT t.description) as 'description'," .
                    " group_concat(DISTINCT t.approved) as 'approved',group_concat(DISTINCT t.approvedbyname) as 'approvedbyname'," .
                    " group_concat(DISTINCT t.approvedtime) as 'approvedtime',group_concat(DISTINCT t.reviewnotes) as 'reviewnotes'," .
                    " group_concat(DISTINCT t.status) as 'status'," . 
                    " group_concat(DISTINCT concat(t.date,'_',t.hours) ORDER BY t.date ASC SEPARATOR '|') AS 'detail', " . 
                    " sum(t.hours) AS 'totalhours', " . 
                    " group_concat(DISTINCT t.fordate) as 'daterange' from (" .
                    " SELECT c.*,r.fordate from (SELECT min(a.id) as 'id',min(a.userid) as 'userid',min(a.managerid) as 'managerid',min(a.name) as 'name',min(a.description) as 'description',min(a.submitted) as 'submitted',min(a.approved) as 'approved',min(a.approvedby) as 'approvedby',min(a.approvedtime) as 'approvedtime',min(a.reviewnotes) as 'reviewnotes',min(a.status) as 'status',min(a.created) as 'created',min(a.lastupdated) as 'lastupdated', " .
                        " p.date, sum(p.hours) as 'hours', group_concat(distinct IFNULL(b.first_name,''), ' ', IFNULL(b.last_name,'')) as approvedbyname " .
                        " FROM timesheet AS a " .
                        " left join activeuser AS b " .
                        " ON b.id = a.approvedby " .
                        " join timesheet_type_user AS p " .
                        " on a.id = p.timesheetid " .
                        " join type AS y " .
                        " on p.typeid = y.id " .
                        " where a.status = 1 " .
                        " and y.billable = 1 " .
                        " and p.status = 1 " .
                        (strtolower(UserHelpers::getUserRoleName()) === strtolower(UserHelpers::getConstants('ADMIN_ROLE_NAME')) ? "" : (" and a.userid = " . strval(Session::get('UID'))) ) .
                        " group by a.id, p.date " .
                        " ) c " .
                        " join  " .
                        " ( " .
                        " SELECT timesheet_timerange.timesheetid,group_concat(distinct timesheet_timerange.fordate SEPARATOR ', ') as 'fordate' " .
                        " FROM timesheet_timerange " .
                        " where status = 1 " .
                        " group by timesheetid " .
                        " ) r " .
                        " on r.timesheetid = c.id " .
                    ") t " .
                    " group by t.id " .
                    " order by " . $mysortby . " " . $myorder .
                    " limit " . strval($amountperpage) .
                    " offset " . strval($offset);
            //Log::info("at 69, sql: "  . $sql);
            $timesheets = DB::select(DB::raw($sql));
            $timesheets = collect($timesheets)->map(function($x){ return (array) $x; })->toArray();
            //Log::info("at 72, timesheets: " . json_encode($timesheets));
            $result = array();
            $last_id = 0;
            if(count($timesheets)) {
                foreach($timesheets as $item) {
                    if($last_id == 0 && $item['id'] == 0) {
                        continue;
                    } else if($last_id == 0 && $item['id'] !== 0) {
                        if(!isset($result[strval($item['id'])])) {
                            $result[strval($item['id'])] = array();
                        }
                        $result[strval($item['id'])] = $item;
                    } else if($item['id'] !== $last_id) {
                        if(!isset($result[strval($item['id'])])) {
                            $result[strval($item['id'])] = array();
                        }
                        $result[strval($item['id'])] = $item;
                    }
                    $last_id = $item['id'];
                }
            }
            //Log::info("at 93, result: " . json_encode($result));

            $total = array();
            if(UserHelpers::isAdmin()) {
                $total = Timesheet::where('status', 1)
                ->select(DB::raw('count(*) as total'))
                ->get();
            } else {
                $total = Timesheet::where('status', 1)
                ->where('userid', Session::get('UID'))
                ->select(DB::raw('count(*) as total'))
                ->get();
            }
            $showpagination = ($total[0]['total'] > intval(UserHelpers::getConstants('ROW_PER_TABLE_INT'))) ? true : false;
            $totalpagenumber = ceil($total[0]['total'] / (float)$amountperpage);

            return view('timesheet.index', ['timesheets' => $result, 'showpagination' => $showpagination, 'rowperpage' => $amountperpage, 'totalpagenumber' => $totalpagenumber, 'topagenumber' => $pagenumber, 'sortby' => $mysortby, 'order' => $myorder]);
        } else {
            return view('auth.login');
            //return redirect('login');
        }
    }

    public function create(Request $request)
    {
        if ($request->isMethod('get')) {
            $types = DB::table('type AS t')
                        ->leftJoin('typecategory AS c', 'c.id', '=', 't.typecategoryid')
                        ->where('t.status', 1)
                        ->where('c.status', 1)
                        ->select(DB::raw("min(t.typecategoryid) as mykey"), DB::raw("group_concat(concat(t.id,'_',t.name) SEPARATOR '|') as list"))
                        ->groupBy('t.typecategoryid')
                        ->orderBy('mykey', 'asc')
                        ->get();

            $typecategories = Typecategory::where('status', 1)
                ->orderBy('name', 'asc')
                ->get();
            return view('timesheet.create', ['types' => $types, 'typecategories' => $typecategories]);
        } else if ($request->isMethod('post')) {
            try{
                $input = $request->all();
                //Log::info("at 135, input: " . json_encode($input));
                //first, make sure no any time range has already been saved into database - no duplicate!
                //$sql = 'SELECT id FROM timesheet_timerange WHERE status=1 AND userid = ? AND ( ((fordate >= ?) AND (fordate <= ?))) ';
                //$sql = 'SELECT id FROM timesheet_timerange where id NOT IN (SELECT id FROM timesheet_timerange WHERE status=1 AND userid = ? AND ( (fordate >= ?) OR (fordate >= ?)) )';

                $timeranges = trim($input["timeranges"]);
                $timerangeArr = explode(',', $timeranges);
                $counter = 0;

                foreach($timerangeArr as $v) {
                    //$temprange = explode('_', $v);
                    $starttime = trim($v);
                    //$endtime = trim($temprange[1]);
                    /*$duplicates = DB::table('timesheet_timerange')
                        ->whereNotIn('id', function($query) use ($endtime, $starttime)
                        {
                                $query->select('id')
                                ->from(with(new timesheet_timerange)->getTable())
                                ->where('status', 1)
                                ->where('userid', UserHelpers::getUID())
                                ->where(function ($query2) use ($starttime) {
                                    $query2->where('fordate', $starttime);
                                });
                        })
                        ->where('status', 1)
                        ->where('userid', UserHelpers::getUID())
                        ->get();*/
                    $duplicates = DB::table('timesheet_timerange')
                                ->where('status', 1)
                                ->where('userid', UserHelpers::getUID())
                                ->where(function ($query2) use ($starttime) {
                                    $query2->where('fordate', $starttime);
                                })
                                ->select('id')
                                ->get();
                    //Log::info("at 172, duplicates: " . json_encode($duplicates));
                    if(!empty($duplicates)) {
                        $counter += count($duplicates);break;
                    }
                }

                if(!$counter) {
                    DB::beginTransaction();
                    $tcount = 0;

                    $timesheet = new Timesheet();
                    $name = trim($input["name"]);
                    $timesheet->name = empty($name) ? 'untitled' : $name;
                    $timesheet->description = $input["desc"] ? $input["desc"] : '';
                    $timesheet->userid = UserHelpers::getUID();
                    $timesheet->managerid = UserHelpers::getManagerUID();
                    $timesheet->status = 1;
                    $timesheet->approved = 0;
                    //superuser doesn't need anyone to approve the timesheet
                    if(UserHelpers::getUserRoleName() === UserHelpers::getConstants('SUPERUSER_ROLE_NAME')) {
                        $timesheet->approved = 1;
                    }
                    date_default_timezone_set(UserHelpers::getConstants('TIMEZONE'));
                    $timesheet->created = date("Y-m-d H:i:s");
                    $tsaveresult = $timesheet->save();
                    if($tsaveresult) {
                        foreach($timerangeArr as $v) {
                            $timesheet_timerange = new timesheet_timerange(); 
                            $temprange = explode('_', $v);
                            $starttime = trim($v);
                            //$endtime = trim($temprange[1]);
                            $timesheet_timerange->userid = UserHelpers::getUID();
                            $timesheet_timerange->timesheetid = $timesheet->id;
                            $timesheet_timerange->fordate = $starttime;
                            //$timesheet_timerange->endtime = $endtime;
                            $timesheet_timerange->status = 1;
                            date_default_timezone_set(UserHelpers::getConstants('TIMEZONE'));
                            $timesheet_timerange->created = date("Y-m-d H:i:s");
                            if($timesheet_timerange->save()) {$tcount++;}
                        }
                    }

                    if($tsaveresult && $tcount == count($timerangeArr)) {
                        $result = trim($input["result"]);
                        if(!empty($result)){
                            $data = json_decode($result, true);
                            $savecount = $datacount = 0;
                            //Log::info("at 219, data: " . $result);
                            foreach($data as $key => $value) {
                                //key - date
                                foreach($value as $v) {
                                    foreach($v as $timerange => $myid) {
                                        $datacount++;
                                        $temp = explode('_', $myid);
                                        $theid = $temp[1];
                                        $timesheet_type_user = new timesheet_type_user();
                                        $timesheet_type_user->userid = UserHelpers::getUID();
                                        $timesheet_type_user->timesheetid = $timesheet->id;
                                        $timesheet_type_user->typeid = $theid;

                                        $rangeArr = explode('_', $timerange);
                                        $timesheet_type_user->starttime = $key . ' ' . $rangeArr[0] . ':00';
                                        $timesheet_type_user->endtime = $key . ' ' . $rangeArr[1] . ':00';
                                        $timesheet_type_user->date = $key;
                                        $timesheet_type_user->hours = round((strtotime($key . ' ' . $rangeArr[1] . ':00') - strtotime($key . ' ' . $rangeArr[0] . ':00'))/3600, 2);
                                        $timesheet_type_user->status = 1;
                                        if($timesheet_type_user->save()) {
                                            $savecount++;
                                        }
                                    }
                                }
                            }
                        }

                        if($savecount === $datacount) {
                            DB::commit();
                            //email
                            $contacts = UserHelpers::getAutoemailaddresses("create");
                            if(!empty($contacts)) {
                                foreach($contacts as $contact) {
                                    //Mail::to($contact)->send(new TimesheetCreated());
                                }
                            }

                            //use curl to POST to other urls
                            $urls_create = UserHelpers::getWebhookUrls("create");
                            $post_url_succes = false;
                            if(!empty($urls_create)) {
                                $counter2 = 0;
                                foreach($urls_create as $url) {
                                    $ch = curl_init();
                                    $token = UserHelpers::getConstants('WEBHOOK_TOKEN');
                                    //userid, username, type=create
                                    $userid = UserHelpers::getUID();
                                    $username = UserHelpers::getUsername();
                                    $temp = 'token='. $token . '&id=' . $userid . '&name=' . $username . '&type=create';

                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    curl_setopt($ch, CURLOPT_POST, 1);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $temp);
                                    // receive server response
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    $server_output = curl_exec ($ch);
                                    curl_close ($ch);

                                    if ($server_output == "OK") { $counter2++; }
                                }
                                if($counter2 == count($urls_create)) {
                                    return 'success-url';
                                }
                                return 'success-partialurl';
                            } else {
                                return 'success-emptyurl';
                            }
                        } else {
                            DB::rollBack();
                            return 'notequal:' . $datacount . ',' . $savecount;
                        }
                    } else {
                        DB::rollBack();
                        return 'notequal:timesheet or timesheet_timerange table save error';
                    }
                } else {//duplicates
                    return 'duplicate';
                }
            } catch(Exception $e){
                //if there is an error/exception in the above code before commit, it'll rollback
                DB::rollBack();
                return 'error:' . $e->getMessage();
            }
        }
    }

    public function edit(Request $request, $id)
    {
        if ($request->isMethod('get')) {
            try{
            $timesheet = DB::table('timesheet')->where('id', $id)->where('approved', 0)->first();
            $timesheet2 = Timesheet::find($id);
            //Log::info("at 309, id: " . $id. ", timesheet: " . json_encode($timesheet2));
            if($timesheet2 && (($timesheet && ($timesheet->userid == UserHelpers::getUID())) || (strtolower(UserHelpers::getUserRoleName()) == strtolower(UserHelpers::getConstants('ADMIN_ROLE_NAME'))) )) {
                $typecategories = Typecategory::where('status', 1)
                    ->orderBy('name', 'asc')
                    ->get();

                $droparea_extra = '';
                $timesheet_timerange = DB::table('timesheet_timerange')->where('timesheetid', $id)->where('userid', $timesheet2->userid)->orderBy('id', 'asc')->get();
                $fordate = "";
				
                if(count($timesheet_timerange)===1){					
                    for($i=0; $i<count($timesheet_timerange); $i++) {
						$fordate = $timesheet_timerange[$i]->fordate;
                        /*$header_extra .= '<div class="row">
                                        <div  class="col-xs-6 col-md-2" ><a href="javascript:void(0);" class="btn btn-primary bold">' . trans("messages.Week") . ' <span class="week_number">'.($i+1).'</span>-' . trans("messages.From") . ':</a></div>
                                        <div  class="col-xs-6 col-md-2" ><input type="text" name="daterange_'.($i+1).'_1" id="daterange_'.($i+1).'_1" class="fromdate datepicker btn btn-default form-control bold" value="' . $timesheet_timerange[$i]->fordate . '" /></div>
                                        <div  class="col-xs-6 col-md-1" ><a href="javascript:void(0);" class="btn btn-primary bold">' . trans("messages.To") . ':</a></div>
                                        <div  class="col-xs-6 col-md-2" ><a name="daterange_'.($i+1).'_2" id="daterange_'.($i+1).'_2" class="btn btn-default form-control todate bold" href="javascript:void(0);">' . $timesheet_timerange[$i]->endtime . '</a></div>

                                        <div  class="col-xs-12 col-md-5">
                                            <a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_'.($i+1).'_1" class="btn btn-default bold weekdays ' . ($i?'':' active ') . '">1</a>
                                            <a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_'.($i+1).'_2" class="btn btn-default bold weekdays">2</a>
                                            <a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_'.($i+1).'_3" class="btn btn-default bold weekdays">3</a> 
                                            <a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_'.($i+1).'_4" class="btn btn-default bold weekdays">4</a>
                                            <a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_'.($i+1).'_5" class="btn btn-default bold weekdays">5</a>
                                            <a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_'.($i+1).'_6" class="btn btn-default bold weekdays">6</a>
                                            <a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_'.($i+1).'_7" class="btn btn-default bold weekdays">7</a>
                                            
                                            <a href="javascript:void(0);" onclick="addNewWeek(this);" class="btn bold week-add"><i class="fa fa-plus-circle font24px" aria-hidden="true" title="Add a new row under the last row"></i></a> 
                                            ' . ($i ? '<a href="javascript:void(0);" onclick="removeThisWeek(this);" class="btn bold week-remove"><i class="fa fa-minus-circle font24px" aria-hidden="true" title="Remove this row"></i></a>' : '')
                                        . '</div>
                                    </div>';*/

                        $fromdate = $timesheet_timerange[$i]->fordate . ' 00:00:00';
                        $nextday = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($timesheet_timerange[$i]->fordate)));
                        //Log::info("at 344, fromdate: " . $fromdate . ", nextday: " . $nextday . ", userid: " . $timesheet2->userid);
                        $tempuserid = $timesheet2->userid;

                        $timesheet_type_user = DB::table('timesheet_type_user')
                                        ->join('type', function ($join) use ($id, $tempuserid, $fromdate, $nextday) {
                                        $join->on('type.id', '=', 'timesheet_type_user.typeid')
                                             ->where('timesheet_type_user.status', '=', 1)
                                             ->where('type.status', '=', 1)
                                             ->where('timesheet_type_user.timesheetid', $id)
                                             ->where('timesheet_type_user.userid', $tempuserid)
                                             ->where('timesheet_type_user.starttime', '>=', $fromdate)
                                             ->where('timesheet_type_user.endtime', '<', $nextday);
                                    })
                            ->select('timesheet_type_user.*', 'type.name')
                            ->orderBy('timesheet_type_user.starttime', 'asc')
                            ->get();

                        $rows = array();
                        if($timesheet_type_user){
                            for($j=0; $j<count($timesheet_type_user); $j++) {
                                $rows[$timesheet_type_user[$j]->starttime] = $timesheet_type_user[$j]->endtime . '|' . 't_' . $timesheet_type_user[$j]->typeid . '|' . $timesheet_type_user[$j]->name;
                            }
                        }
                        ksort($rows);
                        $content = [];
                        $timesheet_drop_counter = 1;
                        //Log::info("at 370, i: " . $i . ", rows: " . json_encode($rows));
                        foreach($rows as $k => $v) {
                            $gap = 'g' . date_diff(date_create($timesheet_timerange[$i]->fordate),date_create($k))->format('%a');
                            if(!array_key_exists($gap,$content)) {$content[$gap] = '';}
                            $temp = explode(' ', $k);
                            $starttime1 = preg_replace('/:00$/', '', $temp[1]);
                            $temp2 = explode('|', $v);
                            $temp3 = $temp2[0];
                            $temp4 = explode(' ', $temp3);
                            $endtime1 = preg_replace('/:00$/', '', $temp4[1]);

                            $content[$gap] .= '<div class="left-item t ' . $temp2[1] . '"><div class="inline scrolloverflow">' . trans("messages.From") . ': <input class="timepicker starttime" value="' . $starttime1 . '" type="text">' .
                                                  trans("messages.To") . ': <input class="timepicker endtime" value="' . $endtime1 . '" type="text"></div><span class="bold left-name cat_' . $temp2[1] . '">' . $temp2[2] . '</span>
                                                  <a class="up-left-item" href="javascript:void(0);" onclick="upParentChangeColor(this);"><i class="fa fa-arrow-up font24px" aria-hidden="true" title="Up"></i></a>
                                                  <a class="down-left-item" href="javascript:void(0);" onclick="downParentChangeColor(this);"><i class="fa fa-arrow-down font24px" aria-hidden="true" title="Down"></i></a>
                                                  <a class="remove-left-item" href="javascript:void(0);" onclick="removeParentChangeColor(this);">
                                                  <i class="fa fa-trash-o font24px" aria-hidden="true" title="Remove"></i></a></div>';
                        }

                        $droparea_extra .= '<div id="ddbox" class="dropweek_box">
                                     <div id="dd" class="droparea ' . ($i?'':' active ') . '"><div id="header" class="dd-left-header"></div><div class="dd-left">' . (array_key_exists('g0',$content) ? $content['g0'] : '' ) . '</div></div>
                                     </div>';
									 
						/*$droparea_extra .= '<div id="ddbox_'.($i+1).'" class="dropweek_box">
                                     <div id="dd_'.($i+1).'_1" class="droparea ' . ($i?'':' active ') . '"><div id="header_'.($i+1).'_1" class="dd-left-header"></div><div class="dd-left">' . (array_key_exists('g0',$content) ? $content['g0'] : '' ) . '</div></div>
                                     <div id="dd_'.($i+1).'_2" class="droparea"><div id="header_'.($i+1).'_2" class="dd-left-header"></div><div class="dd-left">' . (array_key_exists('g1',$content) ? $content['g1'] : '' ) . '</div></div>
                                     <div id="dd_'.($i+1).'_3" class="droparea"><div id="header_'.($i+1).'_3" class="dd-left-header"></div><div class="dd-left">' . (array_key_exists('g2',$content) ? $content['g2'] : '' ) . '</div></div>
                                     <div id="dd_'.($i+1).'_4" class="droparea"><div id="header_'.($i+1).'_4" class="dd-left-header"></div><div class="dd-left">' . (array_key_exists('g3',$content) ? $content['g3'] : '' ) . '</div></div>
                                     <div id="dd_'.($i+1).'_5" class="droparea"><div id="header_'.($i+1).'_5" class="dd-left-header"></div><div class="dd-left">' . (array_key_exists('g4',$content) ? $content['g4'] : '' ) . '</div></div>
                                     <div id="dd_'.($i+1).'_6" class="droparea"><div id="header_'.($i+1).'_6" class="dd-left-header"></div><div class="dd-left">' . (array_key_exists('g5',$content) ? $content['g5'] : '' ) . '</div></div>
                                     <div id="dd_'.($i+1).'_7" class="droparea"><div id="header_'.($i+1).'_7" class="dd-left-header"></div><div class="dd-left">' . (array_key_exists('g6',$content) ? $content['g6'] : '' ) . '</div></div>
                                     </div>';*/
                    }
                }

                $types = DB::table('type AS t')
                    ->leftJoin('typecategory AS c', 'c.id', '=', 't.typecategoryid')
                    ->where('t.status', 1)
                    ->where('c.status', 1)
                    ->select(DB::raw("min(t.typecategoryid) as mykey"), DB::raw("min(c.name) as typecategoryname"), DB::raw("group_concat(concat(t.id,'_',t.name) SEPARATOR '|') as list"))
                    ->groupBy('t.typecategoryid')
                    ->orderBy('mykey', 'asc')
                    ->get();
                //Log::info("at 409, timesheet: " . json_encode($timesheet2) . ", header_extra: " . json_encode($header_extra) . ", droparea_extra: " . json_encode($droparea_extra) . ", timesheet_timerange: " . json_encode($timesheet_timerange) . ", types: " . json_encode($types) . ", typecategories: " . json_encode($typecategories));
                return view('timesheet.edit', ['id' => $id, 'timesheet' => $timesheet2, 'fordate' => $fordate, 'droparea_extra' => $droparea_extra, 'types' => $types, 'typecategories' => $typecategories]);
            } else {
                return redirect('timesheet')->with('status', 'You can not edit this timesheet. Or this item does not exit.');
            }
            } catch( Exception $e) {
                return redirect('timesheet')->with('status', $e->getMessage());
            }
        } else if ($request->isMethod('post')) {
            try{
                $input = $request->all();
                $id = intval($input["id"]);
                //$temp = Timesheet::find($id);
                //if($temp && (($temp->userid == UserHelpers::getUID()) || strtolower(UserHelpers::getUserRoleName()) == strtolower(UserHelpers::getConstants('ADMIN_ROLE_NAME')) )) {
                $timesheet = DB::table('timesheet')->where('id', $id)->where('approved', 0)->first();
                $timesheet2 = Timesheet::find($id);
                if($timesheet2 && (($timesheet && ($timesheet->userid == UserHelpers::getUID())) || (strtolower(UserHelpers::getUserRoleName()) == strtolower(UserHelpers::getConstants('ADMIN_ROLE_NAME'))) )) {
                    DB::beginTransaction();

                    //if timesheet id already in database, remove it from timesheet_timerange, timesheet_type_user
                    //update timesheet table, don't remove it
                    date_default_timezone_set(UserHelpers::getConstants('TIMEZONE'));
                    DB::table('timesheet')
                        ->where('id', $id)
                        ->update(['name' => ($input["name"] ? $input["name"] : ''), 'description'=>($input["desc"] ? $input["desc"] : ''), 'lastupdated'=>date("Y-m-d H:i:s")]);

                    DB::table('timesheet_timerange')->where('timesheetid', '=', $id)->delete();
                    DB::table('timesheet_type_user')->where('timesheetid', '=', $id)->delete();

                    //Log::info("at 438, input: " . json_encode($input));
                    //first, make sure not any time range has also been saved into database - no duplicate!
                    //$sql = 'SELECT id FROM timesheet_timerange where id NOT IN (SELECT id FROM timesheet_timerange WHERE status=1 AND userid = ? AND ( (fordate >= ?) OR (fordate >= ?)) )';

                    $timeranges = trim($input["timeranges"]);
                    $timerangeArr = explode(',', $timeranges);

                    $tcount = 0;

                    foreach($timerangeArr as $v) {
                        $timesheet_timerange = new timesheet_timerange(); 
                        //$temprange = explode('_', $v);
                        $starttime = trim($v);
                        //$endtime = trim($temprange[1]);
                        $timesheet_timerange->userid = $timesheet2->userid;  //UserHelpers::getUID();
                        $timesheet_timerange->timesheetid = $id;
                        $timesheet_timerange->fordate = $starttime;
                        //$timesheet_timerange->endtime = $endtime;
                        $timesheet_timerange->status = 1;
                        date_default_timezone_set(UserHelpers::getConstants('TIMEZONE'));
                        $timesheet_timerange->created = date("Y-m-d H:i:s");
                        if($timesheet_timerange->save()) {$tcount++;}
                    }

                    if($tcount == count($timerangeArr)) {
                        $result = trim($input["result"]);
                        if(!empty($result)){
                            $data = json_decode($result, true);
                            $savecount = $datacount = 0;
                            foreach($data as $key=>$value) {
                                //key - date
                                foreach($value as $v) {
                                    foreach($v as $timerange => $myid) {
                                        $datacount++;
                                        $temp2 = explode('_', $myid);
                                        $theid = $temp2[1];
                                        $timesheet_type_user = new timesheet_type_user();
                                        $timesheet_type_user->userid = $timesheet2->userid;  //UserHelpers::getUID();
                                        $timesheet_type_user->timesheetid = $id;
                                        $timesheet_type_user->typeid = $theid;

                                        $rangeArr = explode('_', $timerange);
                                        $timesheet_type_user->starttime = $key . ' ' . $rangeArr[0] . ':00';
                                        $timesheet_type_user->endtime = $key . ' ' . $rangeArr[1] . ':00';
                                        $timesheet_type_user->date = $key;
                                        $timesheet_type_user->hours = round((strtotime($key . ' ' . $rangeArr[1] . ':00') - strtotime($key . ' ' . $rangeArr[0] . ':00'))/3600, 2);
                                        $timesheet_type_user->status = 1;
                                        if($timesheet_type_user->save()) {
                                            $savecount++;
                                        }
                                    }
                                }
                            }
                        }

                        if($savecount === $datacount) {
                            DB::commit();

                            //email
                            $contacts = UserHelpers::getAutoemailaddresses("edit");
                            foreach($contacts as $contact) {
                                //Mail::to($contact)->send(new TimesheetEdited());	
                            }

                            //use curl to POST to other urls
                            $urls_edit = UserHelpers::getWebhookUrls("edit");
                            $post_url_succes = false;
                            if(!empty($urls_edit)) {
                                $counter = 0;
                                foreach($urls_edit as $url) {
                                    $ch = curl_init();
                                    $token = UserHelpers::getConstants('WEBHOOK_TOKEN');
                                    //userid, username, type=edit
                                    $userid = $timesheet2->userid;  //UserHelpers::getUID();
                                    $username = UserHelpers::getUsername();
                                    $temp3 = 'token='. $token . '&id=' . $userid . '&name=' . $username . '&type=edit';

                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    curl_setopt($ch, CURLOPT_POST, 1);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $temp3);
                                    // receive server response
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    $server_output = curl_exec ($ch);
                                    curl_close ($ch);

                                    if ($server_output == "OK") { $counter++; }
                                }
                                if($counter == count($urls_edit)) {
                                    $post_url_succes = true;
                                }
                                if($post_url_succes) {return 'success-url';}
                                return 'success-partialurl';
                            } else {
                                return 'success-emptyurl';
                            }
                        } else {
                            DB::rollBack();
                            return 'notequal:' . $datacount . ',' . $savecount;
                        }
                    } else {
                        DB::rollBack();
                        return 'notequal:' . count($timerangeArr) . ',' . $tcount;
                    }
                } else {
                    return 'notyours';
                    //return redirect('timesheet')->with('status', 'This timesheet is not yours.');
                }
            } catch(Exception $e) {
                //if there is an error/exception in the above code before commit, it'll rollback
                DB::rollBack();
                return 'error:' . $e->getMessage();
            }
        }
    }

    public function delete(Request $request, $id)
    {
        $timesheet = Timesheet::find($id);
        if(count($timesheet) > 0 && $timesheet->userid == Session::get('UID') && $timesheet->approved === 0) {
            $timesheet->delete();
            Timesheet_timerange::where('timesheetid', $id)->delete();
            Timesheet_type_user::where('timesheetid', $id)->delete();
            return redirect('timesheet');
        } else if(count($timesheet) > 0 && $timesheet->userid == Session::get('UID') && $timesheet->approved === 1){
            return redirect('timesheet')->with('status', 'Approved timesheet can not be deleted.');
        }
        return redirect('timesheet');
    }

    public function switchLang(Request $request)
    {
        if ($request->isMethod('post')) {
            $input = $request->all();
            Session::put('my_locale', $input["lang"]);
        }
        return redirect('timesheet');
    }
        
    /*public function detail(Request $request, $id)
    {
        $timesheet = Timesheet::find($id);
        return view('timesheet.detail', ['timesheet' => $timesheet]);
    }*/
}
