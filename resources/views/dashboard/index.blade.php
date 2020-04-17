@extends('layouts.master')

@section('title', trans('messages.Dashboard'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.Dashboardrules') }}</p>
@endsection

@section('content')
   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   <script src="/js/dashboard.js"></script>
   <script type="text/javascript">
    var myusers = {!! json_encode($users)  !!};
    var mytypes = {!! json_encode($types)  !!};
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    var data, temp, chartColorArr = ["blue", "#c8001d", "green", "purple", "blue"];
    function drawChart() {
@foreach ($items as $t => $item)
        @php
            $temp0 = empty($t) ? array('','') : explode('|', $t, 2);
            $typename = $temp0[1];
            $mydata = array();
            foreach($item as $k => $v) {
                $temp2 = empty($k) ? array('','') : explode('|', $k, 2);
                //no duplicate type and typecategory name
                $mydata[$temp2[1]] = $v;
            }
        @endphp

      //chart 1 - work hours
      var workhours = {!! json_encode($mydata) !!};
      var index = 0, temp1 = [["Workhours", "Hours", { role: "style" } ]];
      for (var k in workhours) {
        if (workhours.hasOwnProperty(k)) {
           temp = [k, workhours[k], chartColorArr[index%chartColorArr.length]];
           temp1.push(temp);
        }
        index++;
      }
      //console.log("at 43, " + JSON.stringify(temp1));
      data = google.visualization.arrayToDataTable(temp1);
      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        titlePosition: 'none',
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: 'none' },
        hAxis: {
          title: '{{ ucfirst($typename) }}'
        },
        vAxis: {
          title: '{{ trans('messages.Hours') }}'
        }
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_{{ $typename }}hours"));
      chart.draw(view, options);  
@endforeach
  }
  </script>
    @foreach ($items as $t => $item)
        @php
            $temp0 = empty($t) ? array('','') : explode('|',$t,2); $typename2=$temp0[1];
        @endphp
        <div class="col-xs-12 col-md-4 col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading" style="text-align:center;">
                {{ ucfirst($typename2) }} {{ trans('messages.Time') }}  <a href="javascript:void(0);" class="no_text_decoration" onclick="openModalChartHours({{ $temp0[0] }});">&nbsp;&nbsp;&nbsp;&nbsp;...{{ trans('messages.More') }}</a>
                </div>
                <div id="columnchart_{{ $typename2 }}hours" style="height: 400px;">
                </div>
            </div>
        </div>
    @endforeach

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

    <style>

  div.panel-default:hover {
    border-color:#000;
  }

  a.no_text_decoration {
    text-decoration: none;
  }

  a.no_text_decoration:hover {
    text-decoration: none;
    color: red;
  }

    /* Modal style  */
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
@endsection

@section('othercontent')
    <div id="myModal" class="modal bold">
      <span class="closebutton cursor" onclick="closeModal()">&times;</span>
      <div class="modalContent">
      </div>
    </div>
@endsection