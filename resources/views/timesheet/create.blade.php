@extends('layouts.master')

@section('title', trans('messages.TIMESHEET'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.TimesheetRules') }}</p>
@endsection

@section('content')
  <script>var timesheet_week_amount = {{ UserHelpers::getConstants('TIMESHEET_WEEK_AMOUNT') === '' ? 2 : UserHelpers::getConstants('TIMESHEET_WEEK_AMOUNT') }};</script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="/js/timesheet.js"></script>
<style>
  div.dd-left {
    border-width:1px;
    border-style:dashed;
    min-height: 200px;
  }

  div.dd-left-preview {
    border-width:1px;
    border-style:dashed;
  }

  div.dd-left-header {
    border-width:1px;
    border-style:dashed dashed none dashed;
    height: 20px;
    text-align: center;
    background-color: #e7e7e7;
  }

  #tabs {
    height: 400px;
  }

  div.dd-right {
    border-width:1px;
    border-style:solid;
    max-height: 350px;
    overflow-y: scroll;
  }

  div.dd-left div.left-item:nth-child(even) {
    background-color: #e7e7e7;
  }

  div.dd-left div.left-item:hover {
    background-color: #FFFACD;
    cursor:grab;
  }

  div.dd-right div.item:nth-child(even) {
    background-color: #e7e7e7;
  }

  div.dd-right div.item:hover {
    background-color: #87CEFA;
    cursor:grab;
  }

  .bold {
      font-weight: bold;
  }

  div.left-item {
      padding: 2px 2px 2px 2px;
      width: 100%;
      position: relative;
  }

  a.up-left-item {
      position: absolute;
      right: 50px;
  }
  
  a.down-left-item {
      position: absolute;
      right: 25px;
  }
  
  a.remove-left-item {
      position: absolute;
      right: 5px;
  }

  span.left-name {
      padding: 0px 2px 0px 2px;
  }

  span.w, li>a.w, .redtext {
      color: red !important;
  }

  span.m, li>a.m {
      color: orange !important;
  }

  span.b, li>a.b {
      color: black !important;
  }

  span.t, li>a.t {
      color: blue !important;
  }

  span.v, li>a.v {
      color: green !important;
  }

  span.h, li>a.h {
      color: purple !important;
  }

  span.o, li>a.o {
      color: cyan !important;
  }

  div.inline {
      display: inline-block;
  }

  div.droparea {
      overflow-x: auto;
  }

  .ui-tabs .ui-tabs-nav .ui-tabs-anchor {
      padding-left: 3px;
      padding-right: 3px;
  }

  .datepicker {
    padding: 0 0 0 0px;
  }

    .outer {
        display: table;
        position: absolute;
        height: 100%;
        width: 100%;
    }

    .middle {
        display: table-cell;
        vertical-align: middle;
    }

    .inner {
        margin-left: auto;
        margin-right: auto; 
        width: 10px;
    }

    .font24px {
        font-size:24px;
    }

    a.week-add, a.week-remove {
        padding-left: 0px;
        padding-right: 0px;

    }

    div#left-drop div.droparea {
        display:none;
    }

    div#left-drop div.active {
        display:block;
    }

    div#left-header div.row div a.active {
        background-color: red;
    }

    a.filled {
        background-color: #337ab7;
        border-color: #2e6da4;
    }

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
	
	.modalContent div.dd-left-preview div.left-item { 
	  background-color:#fefefe !important; 
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

    .mySlides {
      display: none;
    }

    .cursor {
      cursor: pointer
    }

    .timesheet_create_right_section {
        display: none;
    }

    div#tabs {
        border: solid 1px #ddd;
    }
</style>
  <h3>{{ trans('messages.NewTimesheet') }}:</h3>

  <form id="" action="" method="POST">
      <div class="container-fluid">
	  
		  <div class="row">
				<div  class="col-xs-12 col-md-6 bold" >{{ trans('messages.Date') }}*:
					<input type="text" name="fordate" id="fordate" class="fromdate datepicker btn btn-default form-control bold" value="" />
				</div>
		  </div>
		
          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Name') }}: <input type="text" name="name" id="name" class="form-control" value="" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Desc') }}: <textarea name="desc" id="desc" class="form-control" rows="3"></textarea></div>
          </div>

        <div class="row">
            <div  class="col-xs-12 col-md-8 bold">{{ trans('messages.ComposeArea') }}: <br/>1. {{ trans('messages.ComposeArea1') }}<br/>2. {{ trans('messages.ComposeArea2') }}<br/>3. {{ trans('messages.ComposeArea3') }}</div>
        </div>		

        <div class="row">
            <div class="col-xs-12 col-md-8">
                <div id="left-header">
                    <!-- <div class="row">
                            <div  class="col-xs-6 col-md-2" ><div class="bold">{{ trans('messages.Date') }}:</div></div>
							<div  class="col-xs-6 col-md-2" ><input type="text" name="fordate" id="fordate" class="fromdate datepicker btn btn-default form-control bold" value="" /></div>
                            <div  class="col-xs-6 col-md-2" ><input type="text" name="fordate" id="fordate" class="fromdate datepicker btn btn-default form-control bold" value="" /></div>
                            <div  class="col-xs-6 col-md-1" ><a href="javascript:void(0);" class="btn btn-primary bold">{{ trans('messages.To') }}:</a></div>
                            <div  class="col-xs-6 col-md-2" ><a name="daterange_1_2" id="daterange_1_2" class="btn btn-default form-control todate bold" href="javascript:void(0);"></a></div>

                            <div  class="col-xs-12 col-md-5">
                                <a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_1_1" class="btn btn-default bold weekdays active">1</a>
                                <a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_1_2" class="btn btn-default bold weekdays">2</a>
                                <a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_1_3" class="btn btn-default bold weekdays">3</a> 
                                <a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_1_4" class="btn btn-default bold weekdays">4</a>
                                <a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_1_5" class="btn btn-default bold weekdays">5</a>
                                <a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_1_6" class="btn btn-default bold weekdays">6</a>
                                <a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_1_7" class="btn btn-default bold weekdays">7</a>

                                <a href="javascript:void(0);" onclick="addNewWeek(this);" class="btn bold week-add"><i class="fa fa-plus-circle font24px" aria-hidden="true" title="Add a new row under the last row"></i></a>
                            </div>
                    </div>  -->
                </div> 
                <div id="left-drop">
                    <div id="ddbox" class="dropweek_box">
                        <div id="dd" class="droparea active">
                            <div id="header" class="dd-left-header"></div>
                            <div class="dd-left">
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <a class="btn btn-primary bold" onclick="autoFillTime();" href="javascript:void(0);" >{{ trans('messages.AutoFillTime') }}</a>
                <a class="btn btn-primary bold" onclick="previewTimesheet();" href="javascript:void(0);" >{{ trans('messages.Preview') }}</a>
                <br/><br/><a class="btn btn-default bold" href="{{ url('/timesheet') }}" class="" target="_self">{{ trans('messages.Cancel') }}</a>
                <p id="compose-error"></p>
            </div>
            <div  class="col-xs-12 col-md-4"  id="tabs">
                <div>
                    <select class="form-control" name="dropdownlist_typecategory" id="dropdownlist_typecategory" onchange="showSection('dropdownlist_typecategory')">
                         <option value="0" selected>{{ trans('messages.Chooseone') }}...</option>
				     @if(!empty($typecategories))
                     @foreach ($typecategories as $typecategory)
                         <option value="{{ $typecategory->id }}_typecategory">{{ $typecategory->name }}</option>
                     @endforeach
					 @endif
                    </select>
                </div>
				@if(!empty($types))
                @foreach ($types as $type)
                    @php
                        $temp1 = empty($type->list) ? array() : explode('|',$type->list);
                        $myassociatearray = [];
                        $typesforme = UserHelpers::getTypesForUserRCUS(UserHelpers::getUID());
                        foreach($temp1 as $temp2) {
                            $temp3 = empty($temp2) ? array('','') : explode('_',$temp2,2);
                            if(in_array($temp3[0], array_keys($typesforme['read']))) {
                                $myassociatearray[$temp3[0]] = $temp3[1];
                            }
                        }
                    @endphp
                    <div id="{{ $type->mykey }}_typecategory" class="timesheet_create_right_section">
                        <input type="text" name="search_{{ $type->mykey }}_typecategory" id="search_{{ $type->mykey }}_typecategory" onclick="searchfilter(this, '{{ $type->mykey }}_typecategory');" onkeyup="searchfilter(this, '{{ $type->mykey }}_typecategory');" placeholder="{{ trans('messages.Searchbyname') }}" class="form-control" value="" />
                        @if (!count($myassociatearray))
                            <p>No types</p>
                        @else
                            <div class="dd-right">
                                @foreach ($myassociatearray as $k => $v)
                                   <div class="item draggable doubleclickable" id="t_{{$k}}" title="Drag and Drop to the Left Box. Or double click.">{{$v}}</div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
				@endif
             </div>
          </div>

          <br/>
          <div class="row">
            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
          </div>

        <div id="myModal" class="modal bold">
          <span class="closebutton cursor" onclick="closeModal()">&times;</span>
          <div class="modalContent">

          </div>
        </div>

      </div>
  </form>
@endsection

