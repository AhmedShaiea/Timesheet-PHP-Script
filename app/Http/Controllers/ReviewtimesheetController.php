<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Timesheet;
use App\Models\Typecategory;
use App\Models\User;
use App\Mail\TimesheetReviewed;
use Illuminate\Support\Facades\Mail;
use App\Libraries\Helpers\UserHelpers;
use Config;
use Log;

class ReviewtimesheetController extends Controller
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

        $days = intval(UserHelpers::getConstants('REVIEW_TIMESHEET_DAYS_RANGE_INT'));
        $mydate = date('Y-m-d', strtotime('-' . $days . ' days'));
		$timesheets = array();
		$total = array();
		$isAdmin = strtolower(UserHelpers::getUserRoleName()) === strtolower(UserHelpers::getConstants('ADMIN_ROLE_NAME'));
		if($isAdmin) {
			$timesheets = DB::table('timesheet AS t')
            ->leftJoin('activeuser AS a', 'a.id', '=', 't.userid')
            ->leftJoin('activeuser AS b', 'b.id', '=', 't.approvedby')
            ->select(DB::raw("concat(a.first_name, ' ', a.last_name) as username"), DB::raw("concat(IFNULL(b.first_name,''), ' ', IFNULL(b.last_name,'')) as approvedbyname"), 't.*')
            ->where('t.status', 1)
			->where('t.approved', 0)
            ->where('t.created', '>=', $mydate)
            ->orderBy($mysortby, $myorder)
            ->offset($offset)->limit($amountperpage)
            ->get();
		} else {
            $timesheets = DB::table('timesheet AS t')
            ->leftJoin('activeuser AS a', 'a.id', '=', 't.userid')
            ->leftJoin('activeuser AS b', 'b.id', '=', 't.approvedby')
            ->select(DB::raw("concat(a.first_name, ' ', a.last_name) as username"), DB::raw("concat(IFNULL(b.first_name,''), ' ', IFNULL(b.last_name,'')) as approvedbyname"), 't.*')
            ->where('t.status', 1)
			->where('t.approved', 0)
            ->where('t.created', '>=', $mydate)
            ->whereIn('a.id', UserHelpers::getAssociatesForManager(UserHelpers::getUID()))
            ->orderBy($mysortby, $myorder)
            ->offset($offset)->limit($amountperpage)
            ->get();
		}
		if($isAdmin) {
            $total = Timesheet::where('status', 1)
            ->select(DB::raw('count(*) as total'))
            ->get();
		} else {
			$total = Timesheet::where('status', 1)
		    ->whereIn('id', UserHelpers::getAssociatesForManager(UserHelpers::getUID()))
            ->select(DB::raw('count(*) as total'))
            ->get();
		}
        $showpagination = ($total[0]['total'] > intval(UserHelpers::getConstants('ROW_PER_TABLE_INT'))) ? true : false;
        $totalpagenumber = ceil($total[0]['total'] / (float)$amountperpage);
        return view('reviewtimesheet.index', ['timesheets' => $timesheets, 'showpagination' => $showpagination, 'rowperpage' => $amountperpage, 'totalpagenumber' => $totalpagenumber, 'topagenumber' => $pagenumber, 'sortby' => $mysortby, 'order' => $myorder]);
    }

    public function edit(Request $request, $id)
    {
        if ($request->isMethod('get')) {
            $timesheet = Timesheet::find($id);
            if($timesheet && (in_array($timesheet->userid, UserHelpers::getAssociatesForManager(UserHelpers::getUID())) || UserHelpers::getUserRoleName() === UserHelpers::getConstants('ADMIN_ROLE_NAME'))) {
                return view('reviewtimesheet.edit', ['timesheet' => $timesheet]);
            } else {
                return redirect('reviewtimesheet')->with('status', 'You can not edit this timesheet.');
            }
        } else if ($request->isMethod('post')) {
            $input = $request->all();   
            $timesheet = Timesheet::find($id);
            if($timesheet && (in_array($timesheet->userid, UserHelpers::getAssociatesForManager(UserHelpers::getUID())) || UserHelpers::getUserRoleName() === UserHelpers::getConstants('ADMIN_ROLE_NAME'))) {
                if($input["approved"] === '1') {
                    if ($timesheet->approved === 0) {
                        $timesheet->approvedby = UserHelpers::getUID();
						date_default_timezone_set(UserHelpers::getConstants('TIMEZONE'));
                        $timesheet->approvedtime = date("Y-m-d H:i:s");
                    } else if(empty($timesheet->approvedtime)) {
						date_default_timezone_set(UserHelpers::getConstants('TIMEZONE'));
                        $timesheet->approvedtime = date("Y-m-d H:i:s");
                    } else if(empty($timesheet->approvedby)) {
                        $timesheet->approvedby = UserHelpers::getUID();
                    }
                } else {
                    $timesheet->approvedby = null;
                    $timesheet->approvedtime = null;
                }
                $timesheet->approved = $input["approved"];
                $timesheet->reviewnotes = $input["reviewnotes"];
                $timesheet->save();

                //email
                $contacts = UserHelpers::getAutoemailaddresses("review", $id);
				$timesheetCreator = User::find($timesheet->userid);
                foreach($contacts as $contact) {
					//Mail::to($contact)->send(new TimesheetReviewed($timesheetCreator->first_name . ' ' . $timesheetCreator->last_name));
                }

                //use curl to POST to other urls
                $urls_edit = UserHelpers::getWebhookUrls("review");
                $post_url_succes = false;
                $status = "";
                if(!empty($urls_edit)) {
                    $counter = 0;
                    foreach($urls_edit as $url) {
                        $ch = curl_init();
                        $token = UserHelpers::getConstants('WEBHOOK_TOKEN');
                        //userid, username, type=review
                        $userid = UserHelpers::getUID();
                        $username = UserHelpers::getUsername();
                        $temp = 'token='. $token . '&id=' . $userid . '&name=' . $username . '&type=review';

                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $temp);
                        // receive server response
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $server_output = curl_exec ($ch);
                        curl_close ($ch);

                        if ($server_output == "OK") { $counter++; }
                    }
                    if($counter == count($urls_edit)) {
                        $post_url_succes = true;
                    }
                    if($post_url_succes) {$status = 'Review saved. All webhooks got the message.';}
                    $status = 'Review saved. Some webhooks got the message.';
                } else {
                    $status = 'Review saved. No webhooks were set.';
                }

                return redirect('reviewtimesheet')->with('status', $status);
            } else {
                return redirect('reviewtimesheet')->with('status', 'You can not edit this timesheet.');
            }
        }
    }

    public function getdetail(Request $request, $id) 
    {
        if ($request->isMethod('post')) {
            $timesheet = DB::table('timesheet')->where('id', $id)->where('status', 1)->first();
            //Log::info("at 155, id: " . $id. ", timesheet: " . json_encode($timesheet) . ", count: " . count($timesheet));
            if (!empty($timesheet) && in_array($timesheet->userid, UserHelpers::getAssociatesForManager(UserHelpers::getUID())) ) {
                $typecategories = Typecategory::where('status', 1)
                    ->orderBy('name', 'asc')
                    ->get();

                $droparea_extra = '';
			
                $timesheet_timerange = DB::table('timesheet_timerange')->where('timesheetid', $id)->where('status', 1)->orderBy('id', 'asc')->get();
                //Log::info("at 164, uid: " . UserHelpers::getUID() .", timesheet_timerange: " . json_encode($timesheet_timerange));
                if(count($timesheet_timerange)){
                    for($i=0; $i<count($timesheet_timerange); $i++) {
                        if(in_array($timesheet_timerange[$i]->userid, UserHelpers::getAssociatesForManager(UserHelpers::getUID()))) {
                            /*$header_extra .= '<div class="row">
                                            <div  class="col-xs-2 col-md-2" ><a href="javascript:void(0);" class="btn btn-primary bold">' . trans("messages.Week") . ' <span class="week_number">'.($i+1).'</span>-' . trans("messages.From") . ':</a></div>
                                            <div  class="col-xs-2 col-md-2" ><input type="text" name="daterange_'.($i+1).'_1" id="daterange_'.($i+1).'_1" class="fromdate datepicker btn btn-default form-control bold" value="' . $timesheet_timerange[$i]->starttime . '" /></div>
                                            <div  class="col-xs-1 col-md-1" ><a href="javascript:void(0);" class="btn btn-primary bold">' . trans("messages.To") . ':</a></div>
                                            <div  class="col-xs-1 col-md-2" ><a name="daterange_'.($i+1).'_2" id="daterange_'.($i+1).'_2" class="btn btn-default form-control todate bold" href="javascript:void(0);">' . $timesheet_timerange[$i]->endtime . '</a></div>

                                            <div  class="col-xs-5 col-md-5">
                                                <a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_'.($i+1).'_1" class="btn btn-default bold weekdays ' . ($i ? '' : ' active ') . '">1</a>
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
                            $todate_nextday = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($timesheet_timerange[$i]->fordate)));
                            //Log::info("at 190, todate nextday: " . $todate_nextday);

                            $timesheet_type_user = DB::table('timesheet_type_user')
                                            ->join('type', function ($join) use ($id, $fromdate, $todate_nextday) {
                                            $join->on('type.id', '=', 'timesheet_type_user.typeid')
                                                 ->where('timesheet_type_user.status', '=', 1)
                                                 ->where('type.status', '=', 1)
                                                 ->where('timesheet_type_user.timesheetid', $id)
                                                 ->whereIn('timesheet_type_user.userid', UserHelpers::getAssociatesForManager(UserHelpers::getUID()))
                                                 ->where('timesheet_type_user.starttime', '>=', $fromdate)
                                                 ->where('timesheet_type_user.endtime', '<', $todate_nextday);
                                        })
                                ->select('timesheet_type_user.*', 'type.name')
                                ->orderBy('timesheet_type_user.starttime', 'asc')
                                ->get();
                            //Log::info("at 205, timesheet_type_user: " . json_encode($timesheet_type_user));
                            $rows = array();
                            if($timesheet_type_user){
                                for($j=0; $j<count($timesheet_type_user); $j++) {
                                    $rows[$timesheet_type_user[$j]->starttime] = $timesheet_type_user[$j]->endtime . '|' . 't_' . $timesheet_type_user[$j]->typeid . '|' . $timesheet_type_user[$j]->name;
                                }
                            }
                            ksort($rows);
                            $content = [];
                            $mydate = array();
                            $mydate['g0'] = $this->replaceWeekDayTranslation(date('l, Y-m-d', strtotime($timesheet_timerange[$i]->fordate)));
                            //$mydate['g1'] = $this->replaceWeekDayTranslation(date('l, Y-m-d', strtotime('+1 day', strtotime($timesheet_timerange[$i]->starttime))));
                            //$mydate['g2'] = $this->replaceWeekDayTranslation(date('l, Y-m-d', strtotime('+2 day', strtotime($timesheet_timerange[$i]->starttime))));
                            //$mydate['g3'] = $this->replaceWeekDayTranslation(date('l, Y-m-d', strtotime('+3 day', strtotime($timesheet_timerange[$i]->starttime))));
                            //$mydate['g4'] = $this->replaceWeekDayTranslation(date('l, Y-m-d', strtotime('+4 day', strtotime($timesheet_timerange[$i]->starttime))));
                            //$mydate['g5'] = $this->replaceWeekDayTranslation(date('l, Y-m-d', strtotime('+5 day', strtotime($timesheet_timerange[$i]->starttime))));
                            //$mydate['g6'] = $this->replaceWeekDayTranslation(date('l, Y-m-d', strtotime('+6 day', strtotime($timesheet_timerange[$i]->starttime))));
                            //Log::info("at 222, i: " . $i . ", rows: " . json_encode($rows));
                            foreach($rows as $k => $v) {
                                $gap = 'g' . date_diff(date_create($timesheet_timerange[$i]->fordate),date_create($k))->format('%a');
                                if(!array_key_exists($gap,$content)) {$content[$gap] = '';}
                                $temp = explode(' ', $k);
                                $starttime1 = preg_replace('/:00$/', '', $temp[1]);
                                $temp2 = explode('|', $v);
                                $temp3 = $temp2[0];
                                $temp4 = explode(' ', $temp3);
                                $endtime1 = preg_replace('/:00$/', '', $temp4[1]);

                                $content[$gap] .= '<div class="left-item t ' . $temp2[1] . '"><div class="inline scrolloverflow">' . trans("messages.From") . ': <input class="timepicker starttime" value="' . $starttime1 . '" type="text">'
                                                  . trans("messages.To") . ': <input class="timepicker endtime" value="' . $endtime1 . '" type="text"></div><span class="bold left-name cat_' . $temp2[1] . '">' . $temp2[2] . '</span></div>';
                            }

                            $droparea_extra .= '<div id="ddbox" class="dropweek_box">
                                         <div id="dd" class="droparea ' . ($i?'':' active ') . '"><div id="header" class="dd-left-header">' . (array_key_exists('g0',$mydate) ? $mydate['g0'] : '' ) . '</div><div class="dd-left">' . (array_key_exists('g0',$content) ? $content['g0'] : '' ) . '</div></div>
                                         </div>';
										 
							/*$droparea_extra .= '<div id="ddbox_'.($i+1).'" class="dropweek_box">
                                         <div id="dd_'.($i+1).'_1" class="droparea ' . ($i?'':' active ') . '"><div id="header_'.($i+1).'_1" class="dd-left-header">' . (array_key_exists('g0',$mydate) ? $mydate['g0'] : '' ) . '</div><div class="dd-left">' . (array_key_exists('g0',$content) ? $content['g0'] : '' ) . '</div></div>
                                         <div id="dd_'.($i+1).'_2" class="droparea"><div id="header_'.($i+1).'_2" class="dd-left-header">' . (array_key_exists('g1',$mydate) ? $mydate['g1'] : '' ) . '</div><div class="dd-left">' . (array_key_exists('g1',$content) ? $content['g1'] : '' ) . '</div></div>
                                         <div id="dd_'.($i+1).'_3" class="droparea"><div id="header_'.($i+1).'_3" class="dd-left-header">' . (array_key_exists('g2',$mydate) ? $mydate['g2'] : '' ) . '</div><div class="dd-left">' . (array_key_exists('g2',$content) ? $content['g2'] : '' ) . '</div></div>
                                         <div id="dd_'.($i+1).'_4" class="droparea"><div id="header_'.($i+1).'_4" class="dd-left-header">' . (array_key_exists('g3',$mydate) ? $mydate['g3'] : '' ) . '</div><div class="dd-left">' . (array_key_exists('g3',$content) ? $content['g3'] : '' ) . '</div></div>
                                         <div id="dd_'.($i+1).'_5" class="droparea"><div id="header_'.($i+1).'_5" class="dd-left-header">' . (array_key_exists('g4',$mydate) ? $mydate['g4'] : '' ) . '</div><div class="dd-left">' . (array_key_exists('g4',$content) ? $content['g4'] : '' ) . '</div></div>
                                         <div id="dd_'.($i+1).'_6" class="droparea"><div id="header_'.($i+1).'_6" class="dd-left-header">' . (array_key_exists('g5',$mydate) ? $mydate['g5'] : '' ) . '</div><div class="dd-left">' . (array_key_exists('g5',$content) ? $content['g5'] : '' ) . '</div></div>
                                         <div id="dd_'.($i+1).'_7" class="droparea"><div id="header_'.($i+1).'_7" class="dd-left-header">' . (array_key_exists('g6',$mydate) ? $mydate['g6'] : '' ) . '</div><div class="dd-left">' . (array_key_exists('g6',$content) ? $content['g6'] : '' ) . '</div></div>
                                         </div>';*/
                        }
                    }
                }

                $types = DB::table('type AS t')
                ->leftJoin('typecategory AS c', 'c.id', '=', 't.typecategoryid')
                ->where('t.status', 1)
                ->where('c.status', 1)
                ->select(DB::raw("min(t.typecategoryid) as mykey"), DB::raw("group_concat(concat(t.id,'_',t.name) SEPARATOR '|') as list"))
                ->groupBy('t.typecategoryid')
                ->orderBy('mykey', 'asc')
                ->get();
                //Log::info("at 262, timesheet: " . json_encode($timesheet) . ", header_extra: " . json_encode($header_extra) . ", droparea_extra: " . json_encode($droparea_extra) . ", timesheet_timerange: " . json_encode($timesheet_timerange) . ", types: " . json_encode($types) . ", typecategories: " . json_encode($typecategories));
                return json_encode(array('id' => $id, 'timesheet' => $timesheet, 'droparea_extra' => $droparea_extra, 'types' => $types, 'typecategories' => $typecategories));
            } else {
                return '';
            }
        } else {
            return '';
        }
    }
	
	private function replaceWeekDayTranslation(string $s) {
		$s = str_replace("Sunday", trans('messages.Sunday'), $s);
		$s = str_replace("Monday", trans('messages.Monday'), $s);
		$s = str_replace("Tuesday", trans('messages.Tuesday'), $s);
		$s = str_replace("Wednesday", trans('messages.Wednesday'), $s);
		$s = str_replace("Thursday", trans('messages.Thursday'), $s);
		$s = str_replace("Friday", trans('messages.Friday'), $s);
		$s = str_replace("Saturday", trans('messages.Saturday'), $s);
		return $s;
	}

    /*public function detail(Request $request, $id)
    {
        $timesheet = Timesheet::find($id);
        return view('reviewtimesheet.detail', ['timesheet' => $timesheet]);
    }*/

    /*public function delete(Request $request, $id)
    {
        $timesheet = Timesheet::find($id);
        $timesheet->status = 0;
        $timesheet->save();
        return redirect('reviewtimesheet');
    }*/
}
