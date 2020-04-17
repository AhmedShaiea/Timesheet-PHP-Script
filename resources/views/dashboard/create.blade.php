@extends('layouts.master')

@section('title', trans('messages.Dashboard'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.Dashboardrules') }}</p>
@endsection

@section('content')
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
</style>
  <h3>New Timesheet:</h3>

  <form id="" action="" method="POST">
      <div class="container-fluid">
          <div class="row">
            <div  class="col-xs-12 col-md-6">Name: <input type="text" name="name" id="name" class="form-control" value="" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Desc: <textarea name="desc" id="desc" class="form-control" rows="3"></textarea></div>
          </div>

        <div class="row">
            <div  class="col-xs-12 col-md-8 bold">Compose Area (Automatically saved every 2 minutes): <br/>1. Only fill the "From" time, then click "Auto Fill Time" to fill the "To" time automatically<br/>2. If there is time gap, create a break type or other type for it
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-8">
                <div id="left-header">
                    <div class="row">
                            <div  class="col-xs-2 col-md-2" ><a href="javascript:void(0);" class="btn btn-primary bold">Week <span class="week_number">1</span>-From:</a></div>
                            <div  class="col-xs-2 col-md-2" ><input type="text" name="fordate" id="fordate" class="fromdate datepicker btn btn-default form-control bold" value="" /></div>
                            <div  class="col-xs-1 col-md-1" ><a href="javascript:void(0);" class="btn btn-primary bold">To:</a></div>
                            <div  class="col-xs-1 col-md-2" ><a name="daterange_1_2" id="daterange_1_2" class="btn btn-default form-control todate bold" href="javascript:void(0);"></a></div>

                            <div  class="col-xs-5 col-md-5">
                                <a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_1_1" class="btn btn-default bold weekdays active">1</a>
                                <a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_1_2" class="btn btn-default bold weekdays">2</a>
                                <a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_1_3" class="btn btn-default bold weekdays">3</a> 
                                <a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_1_4" class="btn btn-default bold weekdays">4</a>
                                <a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_1_5" class="btn btn-default bold weekdays">5</a>
                                <a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_1_6" class="btn btn-default bold weekdays">6</a>
                                <a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_1_7" class="btn btn-default bold weekdays">7</a>

                                <a href="javascript:void(0);" onclick="addNewWeek(this);" class="btn bold week-add"><i class="fa fa-plus-circle font24px" aria-hidden="true" title="Add a new row under the last row"></i></a>
                            </div>
                    </div>
                </div>
                <div id="left-drop">
                    <div id="ddbox_1" class="dropweek_box">
                        <div id="dd_1_1" class="droparea active">
                            <div id="header_1_1" class="dd-left-header"></div>
                            <div class="dd-left">
                            </div>
                        </div>
                        <div id="dd_1_2" class="droparea">
                            <div id="header_1_2" class="dd-left-header"></div>
                            <div class="dd-left">
                            </div>
                        </div>
                        <div id="dd_1_3" class="droparea">
                            <div id="header_1_3" class="dd-left-header"></div>
                            <div class="dd-left">
                            </div>
                        </div>
                        <div id="dd_1_4" class="droparea">
                            <div id="header_1_4" class="dd-left-header"></div>
                            <div class="dd-left">
                            </div>
                        </div>
                        <div id="dd_1_5" class="droparea">
                            <div id="header_1_5" class="dd-left-header"></div>
                            <div class="dd-left">
                            </div>
                        </div>
                        <div id="dd_1_6" class="droparea">
                            <div id="header_1_6" class="dd-left-header"></div>
                            <div class="dd-left">
                            </div>
                        </div>
                        <div id="dd_1_7" class="droparea">
                            <div id="header_1_7" class="dd-left-header"></div>
                            <div class="dd-left">
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <a class="btn btn-primary bold" onclick="autoFillTime();" href="javascript:void(0);" >Auto Fill Time</a>
                <a class="btn btn-primary bold" onclick="previewTimesheet();" href="javascript:void(0);" >Preview</a>
                <p id="compose-error"></p>
            </div>
            <div  class="col-xs-12 col-md-4"  id="tabs">
                <ul>
                    <li><a class="w" href="#work">Work</a></li>
                    <li><a class="m" href="#meeting">Meeting</a></li>
                    <li><a class="b" href="#break">Break</a></li>
                    <li><a class="t" href="#travel">Travel</a></li>
                    <li><a class="v" href="#vacation">Vacation</a></li>
                    <li><a class="h" href="#holiday">Holiday</a></li>
                    <li><a class="o" href="#other">Other</a></li>
                </ul>

                <div id="work">
                    <input type="text" name="search_work" id="search_work" onclick="searchfilter(this, 'work');" onkeyup="searchfilter(this, 'work');" placeholder="Search work by name" class="form-control" value="" />
                    @if (!count($works))
                        <p>No work types</p>
                    @else
                        <div class="dd-right">
                            @foreach ($works as $p)    
                               <div class="item draggable" id="w_{{$p->id}}" title="Drag and Drop to the Left Box">{{$p->name}}</div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div id="meeting">
                    <input type="text" name="search_meeting" id="search_meeting" onclick="searchfilter(this, 'meeting');" onkeyup="searchfilter(this, 'meeting');" placeholder="Search meeting by name" class="form-control" value="" />
                    @if (!count($meetings))
                        <p>No meeting types</p>
                    @else
                        <div class="dd-right">
                            @foreach ($meetings as $p)
                               <div class="item draggable" id="m_{{$p->id}}" title="Drag and Drop to the Left Box">{{$p->name}}</div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div id="break">
                    <input type="text" name="search_break" id="search_break" onclick="searchfilter(this, 'break');" onkeyup="searchfilter(this, 'break');" placeholder="Search break by name" class="form-control" value="" />
                    @if (!count($breaks))
                        <p>No break types</p>
                    @else
                        <div class="dd-right">
                            @foreach ($breaks as $p)
                               <div class="item draggable" id="b_{{$p->id}}" title="Drag and Drop to the Left Box">{{$p->name}}</div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div id="travel">
                    <input type="text" name="search_travel" id="search_travel" onclick="searchfilter(this, 'travel');" onkeyup="searchfilter(this, 'travel');" placeholder="Search travel by name" class="form-control" value="" />
                    @if (!count($travel))
                        <p>No travel types</p>
                    @else
                        <div class="dd-right">
                            @foreach ($travel as $p)
                               <div class="item draggable" id="t_{{$p->id}}" title="Drag and Drop to the Left Box">{{$p->name}}</div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div id="vacation">
                    <input type="text" name="search_vacation" id="search_vacation" onclick="searchfilter(this, 'vacation');" onkeyup="searchfilter(this, 'vacation');" placeholder="Search vacation by name" class="form-control" value="" />
                    @if (!count($vacation))
                        <p>No vacation types</p>
                    @else
                        <div class="dd-right">
                            @foreach ($vacation as $p)
                               <div class="item draggable" id="v_{{$p->id}}" title="Drag and Drop to the Left Box">{{$p->name}}</div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div id="holiday">
                    <input type="text" name="search_holiday" id="search_holiday" onclick="searchfilter(this, 'holiday');" onkeyup="searchfilter(this, 'holiday');" placeholder="Search holiday by name" class="form-control" value="" />
                    @if (!count($holidays))
                        <p>No holidays types</p>
                    @else
                        <div class="dd-right">
                            @foreach ($holidays as $p)
                               <div class="item draggable" id="h_{{$p->id}}" title="Drag and Drop to the Left Box">{{$p->name}}</div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div id="other">
                <input type="text" name="search_others" id="search_other" onclick="searchfilter(this, 'other');" onkeyup="searchfilter(this, 'other');" placeholder="Search other by name" class="form-control" value="" />
                    @if (!count($others))
                        <p>No other types</p>
                    @else
                        <div class="dd-right">
                            @foreach ($others as $p)
                               <div class="item draggable" id="o_{{$p->id}}" title="Drag and Drop to the Left Box">{{$p->name}}</div>
                            @endforeach
                        </div>
                    @endif
                </div>
             </div>
          </div>

          <br/>
          <div class="row">
            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
            <input type="submit" name="draft" class="" value="Save Draft" />
            <a href="{{ url('/timesheet') }}" class="" target="_self">Cancel</a>
          </div>

        <div id="myModal" class="modal bold">
          <span class="closebutton cursor" onclick="closeModal()">&times;</span>
          <div class="modalContent">

          </div>
        </div>

      </div>
  </form>
@endsection

