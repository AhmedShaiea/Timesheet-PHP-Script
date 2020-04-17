@extends('layouts.master')

@section('title', trans('messages.Report'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.Reportrules') }}</p>
@endsection

@section('content')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="/js/report.js"></script>
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

    #tabs1, #tabs2 {
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

    .cursor {
      cursor: pointer
    }

    th i {
        cursor: pointer;
    }

    th i.fa-sort {
        color: #ddd;
    }

    th i.active {
        color: #000;
    }

    div.loading .loading {
        display: none;
    }

    .report_create_right_section {
        display: none;
    }

    div#tabs1 {
        border: solid 1px #ddd;
    }
</style>

  <h3>{{ trans('messages.Report') }} ({{ trans('messages.downloadzippedexcelcsvJSONpdffiles') }}): </h3>
  
    <div  class="col-xs-12"  id="tabs">
        <ul>
            <li><a class="w" href="#reportbytypes">{{ trans('messages.ReportbyTypes') }}</a></li>
            <li><a class="m" href="#reportbyusers">{{ trans('messages.ReportbyUsers') }}</a></li>
        </ul>

        <div id="reportbytypes">
              <h4 style="color:#337ab7">{{ trans('messages.ReportbyTypes') }}: </h4>
              <form id="form_reportbytypes" action="{{url()->current()}}/create" method="POST">
                  <div class="container-fluid">

                    <div class="row">
                        <div class="col-xs-12 col-md-8">
                            <div id="left-header">
                                <div class="row">
                                        <div  class="col-xs-4 col-md-4" ><div class="btn btn-primary bold" style="cursor:auto">{{ trans('messages.DateFrom') }}:</div></div>
                                        <div  class="col-xs-3 col-md-3" ><input type="text" name="daterange_1" id="daterange_1" class="fromdate datepicker btn btn-default form-control bold" value="" /></div>
                                        <div  class="col-xs-1 col-md-1" ><div class="btn btn-primary bold" style="cursor:auto">{{ trans('messages.To') }}:</div></div>
                                        <div  class="col-xs-3 col-md-3" ><input type="text" name="daterange_2" id="daterange_2" class="fromdate datepicker btn btn-default form-control bold" value="" /></div>
                                </div>

                            </div>
                            <div id="left-drop">
                                <div id="ddbox" class="dropweek_box">
                                    <div id="dd" class="droparea active">
                                        <div class="dd-left">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div  class="col-xs-12 col-md-4"  id="tabs1">
                            <div>
                                <select class="form-control" name="dropdownlist_typecategory" id="dropdownlist_typecategory" onchange="showSection('dropdownlist_typecategory')">
                                     <option value="0" selected>{{ trans('messages.Chooseone') }}...</option>
                                 @foreach ($typecategories as $typecategory)
                                     <option value="{{ $typecategory->id }}_typecategory">{{ $typecategory->name }}</option>
                                 @endforeach
                                </select>
                            </div>

                            @foreach ($types as $type)
                                @php
                                    $temp1 = empty($type->list) ? array() : explode('|',$type->list);
                                    $myassociatearray = [];
                                    foreach($temp1 as $temp2) {
                                        $temp3 = empty($temp2) ? array('','') : explode('_',$temp2,2);
                                        $myassociatearray[$temp3[0]] = $temp3[1];
                                    }
                                @endphp
                                <div id="{{ $type->mykey }}_typecategory" class="report_create_right_section">
                                    <input type="text" name="search_{{ $type->mykey }}_typecategory" id="search_{{ $type->mykey }}_typecategory" onclick="searchfilter(this, '{{ $type->mykey }}_typecategory');" onkeyup="searchfilter(this, '{{ $type->mykey }}_typecategory');" placeholder="{{ trans('messages.Searchbyname') }}" class="form-control" value="" />
                                    @if (!count($myassociatearray))
                                        <p>No {{ $typename }} types</p>
                                    @else
                                        <div class="dd-right">
                                            @foreach ($myassociatearray as $k => $v)
                                               <div class="item draggable doubleclickable" id="t_{{$k}}" title="Drag and Drop to the Left Box. Or double click.">{{$v}}</div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                         </div>
                      </div>
                      <br/>
                      <div class="row form-inline">
                        <div class="col-xs-12">
                            <div class="form-group loading">
                                <a href="javascript:void(0);" onclick="showReportOnPage('type');" class="btn btn-primary" >{{ trans('messages.Showresultonthepage') }}</a>
                                <select class="form-control" onchange="showReportOnPage('type');" style="display:inline-block;" name="amountperpage" id="amountperpage">
                                     <option value="10" >10</option>
                                     <option value="20" >20</option>
                                     <option value="50" selected>50</option>
                                     <option value="100" >100</option>
                                </select>
                                <label>{{ trans('messages.itemsperpage') }}</label>
                                <img class="loading" src="/images/loading.gif" alt="loading" style="width:20px;height:20px;">
                            </div>
                        </div>
                      </div>
                      <div style="clear:both;"></div><br/>
                      <div class="row pwrapper">
                            <div class="col-xs-6 mypagination" style="display: inline-block;">
                            </div>
                      </div>
                      <div style="clear:both;"></div><br/>
                      <div class="row table-responsive" id="show_result_type">
                      </div>
<style>
  div.row div.mypagination div {
      display:inline;
      width: 10px;
      margin: 2px;
      font: 10px;
      border: solid 1px;
      color: #000;
      background-color: #fff;
      border-color: #2e6da4;
      padding: 6px 12px;
      transition: background-color .1s;
  }

    div.row div.mypagination div.noborder {
        border: none;
    }
  
   div.row div.mypagination div:hover:not(.active):not(.noborder):not(.disabled) {
       cursor:pointer;
       background-color: #007fff;
   }

   div.row div.mypagination div.active {
      background-color: #337ab7;
      color:#fff;
   }

   div.row div.mypagination div.disabled {
      cursor: auto;
      color:#ddd;
   }
</style>
                        <div style="clear:both;"></div>
                        <div class="row pwrapper">
                            <div class="col-xs-6 mypagination" style="display: inline-block;">
                            </div>
                        </div><hr/>
                        <input type="radio" name="file" value="excel"  checked> Excel &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="file" value="csv"> csv &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="file" value="pdf"> pdf &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="file" value="json"> JSON<br><br>

                        <input type="radio" name="separate" value="0"  checked> {{ trans('messages.Allinonefile') }}<br>
                        <input type="radio" name="separate" value="1"> {{ trans('messages.Eachrowinseparatefiles') }}<br><br>

                        <div class="row">
                            <div class="col-xs-2">
                                {{ trans('messages.Filename') }}:
                            </div>
                            <div class="col-xs-10">
                                <input type="text" class="form-control" name="filename" id="filename" value=""/>
                            </div>
                        </div>
                        <a href="javascript:void(0);" onclick="createFile('type');" class="btn btn-primary" >{{ trans('messages.CreateFile') }}</a>
                        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="mytypes" id="mytypes" value=""/>
                        <p id="compose-error"></p>

                  </div>
              </form>
        </div>

        <div id="reportbyusers">
              <h4 style="color:#f0ad4e">{{ trans('messages.ReportbyUsers') }}: </h4> 
              <form id="form_reportbyusers" action="{{url()->current()}}/create" method="POST">
                  <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12 col-md-8">
                            <div>
                                <div class="row">
                                        <div  class="col-xs-4 col-md-4" ><div class="btn btn-warning bold" style="cursor:auto">{{ trans('messages.DateFrom') }}:</div></div>
                                        <div  class="col-xs-3 col-md-3" ><input type="text" name="daterange_3" id="daterange_3" class="fromdate datepicker btn btn-default form-control bold" value="" /></div>
                                        <div  class="col-xs-1 col-md-1" ><div class="btn btn-warning bold" style="cursor:auto">{{ trans('messages.To') }}:</div></div>
                                        <div  class="col-xs-3 col-md-3" ><input type="text" name="daterange_4" id="daterange_4" class="fromdate datepicker btn btn-default form-control bold" value="" /></div>
                                </div>

                            </div>
                            <div id="left-drop2">
                                <div id="ddbox2" class="dropweek_box">
                                    <div id="dd2" class="droparea active">
                                        <div class="dd-left">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div  class="col-xs-12 col-md-4"  id="users">
						    
							<a href="javascript:void(0);" onclick="dragAllUsersToLeft();" class="btn btn-warning" >{{ trans('messages.Choosealltheusers') }}</a>
                            <input type="text" name="search_user" id="search_user" onclick="searchfilter(this, 'users');" onkeyup="searchfilter(this, 'users');" placeholder="{{ trans('messages.Searchuserbyname') }}" class="form-control" value="" />
                            @php
                                $myusers = array();
                                $usersReportToThisUser = UserHelpers::getAssociatesForManager(UserHelpers::getUID());
                                foreach($users as $temp) {
									if(UserHelpers::isAdmin()) {
										$myusers[$temp->id] = $temp;
									} else {
										if(in_array($temp->id, $usersReportToThisUser)) {
											$myusers[$temp->id] = $temp;
										} else if($temp->id === UserHelpers::getUID()){
											$myusers[$temp->id] = $temp;
										}
									}
                                }
                            @endphp
                            @if (!count($myusers))
                                <p>No users</p>
                            @else
                                <div class="dd-right">
                                    @foreach ($myusers as $k=>$p)
                                       <div class="item draggable doubleclickable" id="w_{{$p->id}}" title="Drag and Drop to the Left Box. Or double click.">{{$p->first_name . ' ' . $p->last_name}}</div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    <br />
                    <div class="row form-inline">
                        <div class="col-xs-12">
                            <div class="form-group loading">
                                <a href="javascript:void(0);" onclick="showReportOnPage('user');" class="btn btn-warning" >{{ trans('messages.Showresultonthepage') }}</a>
                                <select class="form-control" onchange="showReportOnPage('user');" style="display:inline-block;" name="amountperpage2" id="amountperpage2">
                                  <option value="10" >10</option>
                                  <option value="20" >20</option>
                                  <option value="50" selected>50</option>
                                  <option value="100" >100</option>
                                </select>
                                <label>{{ trans('messages.itemsperpage') }}</label>
                                <img class="loading" src="/images/loading.gif" alt="loading" style="width:20px;height:20px;">
                            </div>
                        </div>
                    </div>
                    <div style="clear:both;"></div><br/>
                    <div class="row pwrapper2">
                        <div class="col-xs-6 mypagination" style="display: inline-block;">
                        </div>
                    </div><br/>
                    <div class="row table-responsive" id="show_result_user">
                    </div>

                    <div style="clear:both;"></div>
                    <div class="row pwrapper2">
                        <div class="col-xs-6 mypagination" style="display: inline-block;">
                        </div>
                    </div><hr/>

                    <input type="radio" name="file" value="excel"  checked> Excel &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="file" value="csv"> csv &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="file" value="pdf"> pdf &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="file" value="json"> JSON &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="file" value="qbiif"> Quick Books IIF &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="file" value="qbexcel"> Quick Books Excel<br><br>

                    <input type="radio" name="separate" value="0"  checked> {{ trans('messages.Allinonefile') }}<br>
                    <input type="radio" name="separate" value="1"> {{ trans('messages.Eachuserinseparatefiles') }}<br><br>

                    <div class="row">
                        <div class="col-xs-2">
                            {{ trans('messages.Filename') }}:
                        </div>
                        <div class="col-xs-10">
                            <input type="text" class="form-control" name="filename2" id="filename2" value=""/>
                        </div>
                    </div>

                    <a href="javascript:void(0);" onclick="createFile('user');" class="btn btn-warning" >{{ trans('messages.CreateFile') }}</a>
                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                    <p id="compose-error2"></p>
                    <input type="hidden" name="myusers" id="myusers" value=""/>
                  </div>
              </form>
        </div>
    </div>

@endsection

