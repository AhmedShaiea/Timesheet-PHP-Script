@extends('layouts.master')

@section('title', trans('messages.ReviewOthersTimesheet'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.ReviewTimesheetrules') }}</p>
@endsection

@section('javascript')
<script>
  master_totalpagenumber = {{$totalpagenumber}};
  $(document).ready(function(){
      master_createPagination({{ $totalpagenumber }}, {{ $topagenumber }});
  });
</script>
@endsection

@section('content')
    <script src="/js/reviewtimesheet.js"></script>
    <style>
    /* modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 2000;
      padding-top: 0px;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: black;
    }

    /* Modal Content */
    .modalContent {
      position: relative;
      background-color: #fefefe;
      margin: auto;
      padding: 0;
      width: 90%;
      max-width: 1200px;
      height: 100%;
    }

    /* The Close Button */
    .closebutton {
      color: #fff;
      position: absolute;
      top: 10px;
      right: 25px;
      font-size: 35px;
      font-weight: bold;
      font-family: Verdana, sans-serif;
    }

    .closebutton:hover,
    .closebutton:focus {
      color: #999;
      text-decoration: none;
      cursor: pointer;
    }

    div.inline {
      display: inline-block;
    }

    div.dd-left-header {
      border-width:1px;
      border-style:dashed dashed none dashed;
      height: 20px;
      text-align: center;
      background-color: #e7e7e7;
    }
    </style>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

        <div class="row form-inline">
        <div class="col-xs-12">
            <div class="form-group loading">
                <span>{{ trans('messages.ReviewTimesheetsListcreatedwithin30days') }}: </span>
                <select class="form-control" onchange="master_showResultOnReloadedPage('reviewtimesheet');" style="display:inline-block;" name="master_amountperpage" id="master_amountperpage">
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
        <tr>  <!-- [''=>'', 'ID'=>'id', 'UserName'=>'username', 'Name'=>'name', 'Desc'=>'description', 'Approved'=>'approved', 'Approvedby'=>'approvedby', 'Review Notes'=>'reviewnotes', 'Status'=>'status', 'Created'=>'created']  -->
            @foreach (json_decode(UserHelpers::getConstants('HEADER_REVIEWTIMESHEET_ARRAY'), true) as $key => $val )
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
			@if(in_array($p->userid, UserHelpers::getAssociatesForManager(UserHelpers::getUID())))
			<a href="javascript:void(0);" onclick='previewOthersTimesheet({"ID":{{ $p->id }},"UserName": "{{$p->username}}","Name": "{{$p->name}}","Desc": "{{$p->description}}","Approved": "{{ empty($p->approved) ? trans('messages.No') : trans('messages.Yes')}}","Approvedby": "{{ $p->approvedbyname}}","ApprovedTime": "{{ $p->approvedtime}}" ,"ReviewNotes": "{{$p->reviewnotes}}","Status": "{{ empty($p->status) ? trans('messages.NotActive') : trans('messages.Active')}}","Created": "{{$p->created}}"});' class="btn btn-primary">{{ trans('messages.Detail') }}</a>
			@endif
			</th>
            <th>{{$p->id}}</th><td>{{$p->username}}</td><td>{{$p->name}}</td><td>{{$p->description}}</td><td>{{ empty($p->approved) ? trans('messages.No') : trans('messages.Yes')}}</td><td>{{ $p->approvedbyname}}</td><td>{{ $p->approvedtime}}</td><td>{{$p->reviewnotes}}</td><td>{{ empty($p->status) ? trans('messages.NotActive') : trans('messages.Active')}}</td><td>{{$p->created}}</td>
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

@section('othercontent')
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
    <div id="myModal" class="modal bold">
      <span class="closebutton cursor" onclick="closeModal()">&times;</span>
      <div class="modalContent">

      </div>
    </div>
@endsection

