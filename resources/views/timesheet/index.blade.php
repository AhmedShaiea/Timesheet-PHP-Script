@extends('layouts.master')

@section('title', trans('messages.TIMESHEET'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.TimesheetRules') }}</p>
@endsection

@section('javascript')
<script>
  master_totalpagenumber = {{$totalpagenumber}};
  $(document).ready(function(){
      master_createPagination({{ $totalpagenumber }}, {{ $topagenumber }});
  });
</script>
<script src="/js/timesheetindex.js"></script>
@endsection

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="row form-inline">
        <div class="col-xs-12">
            <div class="form-group loading">
                <span>{{ trans('messages.TimesheetList') }}: <a href="{{ url('/timesheet/create') }}" class="btn btn-success">{{ trans('messages.Create') }}</a></span>
                <select class="form-control" onchange="master_showResultOnReloadedPage('timesheet');" style="display:inline-block;" name="master_amountperpage" id="master_amountperpage">
                  @foreach (json_decode(UserHelpers::getConstants('ITEMS_PER_PAGE_ARRAY'), true) as $key => $val )
                  <option value="{{$key}}" @if($rowperpage == $val) selected @endif>{{$val}}</option>
                  @endforeach
                </select>
                <label>{{ trans('messages.itemsperpage') }}</label>
                <img class="loading" src="/images/loading.gif" alt="loading" style="width:20px;height:20px;">
            </div>
        </div>
    </div>
    <div style="clear:both;"></div><br/>
    <div class="row master_pwrapper">
        <div class="col-xs-6 master_mypagination" style="display: inline-block;">
        </div>
    </div><br/>
    <div style="clear:both;"></div>
  @if (!count($timesheets))
      <p>{{ trans('messages.Notimesheets') }}</p>
  @else
      <div class="table-responsive" id="master_show_result">
        <table class="table table-bordered table-hover table-striped">
            <tr>  <!-- [''=>'', 'ID'=>'id', 'Name'=>'name', 'Desc'=>'description', 'Date Range'=>'daterange', 'Total Hours'=>'totalhours', 'Detail <input type=\"radio\" name=\"detail1\" value=\"detail1\" onclick=\"\" />date - hours&nbsp;&nbsp;&nbsp;<input type=\"radio\" onclick=\"\" />hours'=>'', 'Approved'=>'approved', 'Review Notes'=>'reviewnotes', 'Status'=>'status']  -->
                @foreach (json_decode(UserHelpers::getConstants('HEADER_TIMESHEET_ARRAY'), true) as $key => $val )
                    <th class="{{ $val }}">
					    @if(!empty($key))
							{!! trans("messages.$key") !!}&nbsp;&nbsp;&nbsp;
					    @endif
                        @if($val === $sortby && $order === 'asc')
                            <i class="fa fa-sort-asc"></i>
                        @elseif($val === $sortby && $order === 'desc')
                            <i class="fa fa-sort-desc"></i>
                        @elseif($val !== '')
                            <i class="fa fa-sort"></i>
                        @endif
                    </th>
                @endforeach
            </tr>

            @foreach ($timesheets as $p)
            <tr>
				<th>
				@if (strtolower(UserHelpers::getUserRoleName()) === strtolower(UserHelpers::getConstants('ADMIN_ROLE_NAME')))
                    <a href="{{ url('/timesheet/edit/' . $p['id']) }}" class="btn btn-primary">{{ trans('messages.Edit') }}</a>
                    <a href="javascript:void(0);" onclick="master_delete(this, {{$p['id']}}, 'timesheet');" class="btn btn-danger">{{ trans('messages.Delete') }}</a>	
                @elseif ($p['approved'])
                @elseif (intval($p['userid']) === intval(Session::get('UID')) && intval($p['approved']) === 0)
                    <a href="{{ url('/timesheet/edit/' . $p['id']) }}" class="btn btn-primary">{{ trans('messages.Edit') }}</a>
                    <a href="javascript:void(0);" onclick="master_delete(this, {{$p['id']}}, 'timesheet');" class="btn btn-danger">{{ trans('messages.Delete') }}</a>			
				@endif
				</th>
				<th>{{$p['id']}}</th><td>{{$p['name']}}</td><td>{{$p['description']}}</td><td>{{$p['daterange']}}</td><td>{{$p['totalhours']}}</td>
                <!-- <td>
                    @php
                    $temp = empty($p['detail']) ? array() : explode('|',$p['detail']);
                    $amount = count($temp);
                    $temp1 = array('date');
                    $temp2 = array('hours');
                    $days = array('Sun', 'Mon', 'Tue', 'Wed','Thu','Fri', 'Sat');
					$counter = 0;
					$colorArr = json_decode(UserHelpers::getConstants('TIMESHEET_HOURS_COLORS_ARRAY'), true);
                    foreach($temp as $item) {
                        $temp3 = empty($item) ? array('','') : explode('_', $item);
						if(isset($colorArr[strval($counter % 7)])) {
						        echo '<span class="label label-' . $colorArr[strval($counter % 7)] . ' detailhours" >' . $temp3[1] . '</span>';
                                echo '<a class="btn btn-' . $colorArr[strval($counter % 7)] . ' detaildatehours" style="display:none;"><span class="date">' . date('m-d', strtotime($temp3[0])) . ',' . $days[date('w', strtotime($temp3[0]))] . '</span><span class="badge badge-' . $colorArr[strval($counter % 7)] . '">' . $temp3[1] . '</span></a>';
						}
                        $counter++;
					}
                @endphp 
                </td>-->
                <td>{{$p['approved'] ? trans('messages.Yes') : trans('messages.No')}}</td><td>{{$p['approvedbyname']}}</td><td>{{$p['approvedtime']}}</td><td>{{$p['reviewnotes']}}</td><td>{{empty($p['status']) ? trans('messages.NotActive') : trans('messages.Active') }}</td>
            </tr>
            @endforeach

        </table>
</div>

  @endif
    <div style="clear:both;"></div>
    <div class="row master_pwrapper">
        <div class="col-xs-6 master_mypagination" style="display: inline-block;">
        </div>
    </div>
    <div style="clear:both;"></div>

@endsection

