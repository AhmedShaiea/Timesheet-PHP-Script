/*
  Author: Ming's IT Services Ltd.
  Date: 2018-09-01
  Email: mingtl2010@gmail.com
  Copyright: All rights reserved.
*/
  $( function() {
    $('input.modal_dateinput').each(function(){
        $(this).datetimepicker({
            timepicker:false,
            format:'Y-m-d',
			lang: mylang
        });
    });
  } );

  function openModalChartHours(typecategoryid) {
      var temp = '<ul class="nav nav-tabs"><li class="active"><a data-toggle="tab" href="#byuser">' + mytrans.Onlyshowoneusersworkhourschart + '</a></li><li><a data-toggle="tab" href="#bytype">' + mytrans.Onlyshowonetypesuserhourschart + '</a></li></ul>';

      temp += '<div class="tab-content">';
      temp += '<div id="byuser" class="tab-pane fade in active">';
      temp += '<div class="container" style="background-color:white;"><div class="row form-inline"><div class="col-xs-12"><div class="form-group"><span style="color:red;">' + mytrans.Onlyshowoneusersworkhourschart + '&nbsp;&nbsp;&nbsp;&nbsp;</span>';

      temp += mytrans.From + ': <input type="text" placeholder="' + mytrans.Fromdate + '" class="modal_dateinput" id="from" name="from" value="" />';
      temp += mytrans.To + ': <input type="text" placeholder="' + mytrans.Todate + '" class="modal_dateinput" id="to" name="to" value="" />';

      temp += '<select class="form-control" style="display:inline-block;" name="modal_' + typecategoryid + '_user" id="modal_' + typecategoryid + '_user">';
      temp += '<option value="0">' + mytrans.Chooseoneuser + '...</option>';
      for (var prop in myusers) {
        if(!myusers.hasOwnProperty(prop)) continue;
        temp += '<option value="' + prop + '">' + myusers[prop] + '</option>';
      }
      temp += '</select>';
      temp += '<a href="javascript:void(0);" class="btn btn-danger" onclick="showDataInModalChart(' + typecategoryid + ')" >' + mytrans.showchart + '</a>';
      temp += '<a href="javascript:void(0);" class="btn btn-default" onclick="clearDataInModalChart(' + typecategoryid + ')" >' + mytrans.removeallchart + '</a>';
      temp += '</div></div></div><div id="modal_' + typecategoryid + '_user_charts"></div></div></div>';

      temp += '<div id="bytype" class="tab-pane fade">';
      temp += '<div class="container" style="background-color:white;"><div class="row form-inline"><div class="col-xs-12"><div class="form-group"><span style="color:blue;">' + mytrans.Onlyshowonetypesuserhourschart + '&nbsp;&nbsp;&nbsp;&nbsp;</span>';

      temp += mytrans.From + ': <input type="text" placeholder="' + mytrans.Fromdate + '" class="modal_dateinput" id="from2" name="from2" value="" />';
      temp += mytrans.To + ': <input type="text" placeholder="' + mytrans.Todate + '" class="modal_dateinput" id="to2" name="to2" value="" />';

      temp += '<select class="form-control" style="display:inline-block;" name="modal_' + typecategoryid + '_type" id="modal_' + typecategoryid + '_type">';
      temp += '<option value="0">' + mytrans.Chooseonetype + '...</option>';

      var res = mytypes[typecategoryid.toString()].split('|');
      for (i = 0; i < res.length; i++) {
          var mytemp5 = res[i].split('_');
          temp += '<option value="' + mytemp5[0] + '">' + mytemp5[1] + '</option>';
      }
      temp += '</select>';
      temp += '<a href="javascript:void(0);" class="btn btn-primary" onclick="showDataInOneTypeModalChart(' + typecategoryid + ')" >' + mytrans.showchart + '</a>';
      temp += '<a href="javascript:void(0);" class="btn btn-default" onclick="clearDataInOneTypeModalChart(' + typecategoryid + ')" >' + mytrans.removeallchart + '</a>';
      temp += '</div></div></div><div id="modal_' + typecategoryid + '_type_charts"></div></div></div>';

      $('div#myModal > div.modalContent').html(temp);
      $('input.modal_dateinput').each(function(){
          $(this).datetimepicker({
              timepicker:false,
              format:'Y-m-d',
			  lang: mylang
          });
      });
      $('div#myModal').show();
  }

  function showDataInModalChart(typecategoryid) {
          var username = $("select#modal_" + typecategoryid + "_user option:selected").text();
          var userid = $("select#modal_" + typecategoryid + "_user").val();
          var from = $("input#from").val();
          var to = $("input#to").val();
          //use ajax to post to /create
          var myurl = window.location.href.split("#");
          myurl = myurl[0];
          if(myurl[myurl.length -1] === '/') {
              myurl = myurl.substring(0, myurl.length - 1);
          }
          var finaldata = {};
          finaldata['userid'] = userid;
          finaldata['from'] = from;
          finaldata['to'] = to;
          finaldata['typecategoryid'] = typecategoryid;
          finaldata['_token'] = $('input#_token').val();

          $.ajax({
              method: "POST",
              url: myurl + '/getHoursByEachUser',
              data: finaldata
            })
            .done(function( msg ) {
                //console.log( "at 92, msg: " + msg );
                if(msg != '') {
                    var mytemp = JSON.parse(msg);
                    if(Object.keys(mytemp.mydata).length > 0 && mytemp.typecategoryname !== '') {
                        google.charts.load("current", {packages:['corechart']});
                        google.charts.setOnLoadCallback(function() {drawModalChart(mytemp.mydata, typecategoryid, mytemp.typecategoryname, userid, username, from, to)});
                    } else {
                        alert(mytrans.nodata);
                    }
                } else {
                    alert(mytrans.ErrorPleasetryagain);
                    location.reload();
                }
            })
            .fail(function( jqXHR, textStatus ) {
                alert(mytrans.Requestfailed + ": " + textStatus);
            });
  }

  function clearDataInModalChart(typecategoryid) {
      $('div#modal_' + typecategoryid + '_user_charts').html('');
  }

  //2nd tab
  function showDataInOneTypeModalChart(typecategoryid) {
          var typename = $("select#modal_" + typecategoryid + "_type option:selected").text();
          var typeid = $("select#modal_" + typecategoryid + "_type").val();
          var from = $("input#from2").val();
          var to = $("input#to2").val();
            //use ajax to post to /create
          var myurl = window.location.href.split("#");
          myurl = myurl[0];
          if(myurl[myurl.length -1] === '/') {
              myurl = myurl.substring(0, myurl.length - 1);
          }
          var finaldata = {};
          finaldata['typeid'] = typeid;
          finaldata['from'] = from;
          finaldata['to'] = to;
          finaldata['typecategoryid'] = typecategoryid;
          finaldata['_token'] = $('input#_token').val();

          $.ajax({
              method: "POST",
              url: myurl + '/getHoursByEachType',
              data: finaldata
            })
            .done(function( msg ) {
                //console.log( "at 140, msg: " + msg );
                if(msg != '') {
                    var mytemp = JSON.parse(msg);
                    if(Object.keys(mytemp.mydata).length > 0 && mytemp.typecategoryname !== '') {
                        google.charts.load("current", {packages:['corechart']});
                        google.charts.setOnLoadCallback(function() {drawOneTypeModalChart(mytemp.mydata, typecategoryid, mytemp.typecategoryname, typeid, typename, from, to)});
                    } else {
                        alert(mytrans.nodata);
                    }
                } else {
                    alert(mytrans.ErrorPleasetryagain);
                    location.reload();
                }
            })
            .fail(function( jqXHR, textStatus ) {
                alert(mytrans.Requestfailed + ": " + textStatus);
            });
  }

  function clearDataInOneTypeModalChart(typecategoryid) {
      $('div#modal_' + typecategoryid + '_type_charts').html('');
  }

  function drawModalChart(myhours, typecategoryid, typecategoryname, userid, username, from, to) {
      var data, temp, chartColorArr = ["blue", "#c8001d", "green", "purple", "blue"];
      var index = 0, temp1 = [["Workhours", "Hours", { role: "style" } ]];
      for (var k in myhours) {
        if (myhours.hasOwnProperty(k)) {
           temp = [k, myhours[k], chartColorArr[index%chartColorArr.length]];
           temp1.push(temp);
        }
        index++;
      }
      //console.log("at 173, " + JSON.stringify(temp));
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
          title: typecategoryname + '-' + username + '-' + mytrans.from + ':' + from + '-' + mytrans.to + ':' + to
        },
        vAxis: {
          title: mytrans.Hours
        }
      };
      if($("div#modal_columnchart_" + typecategoryname + '_' + userid + "_hours_" + from + "_" + to).length == 0) {
          $('div#modal_' + typecategoryid + '_user_charts').append('<div class="col-xs-12 col-md-4 col-lg-4" id="modal_columnchart_' + typecategoryname + '_' + userid + '_hours_' + from + '_' + to + '"></div>');
          var chart = new google.visualization.ColumnChart(document.getElementById("modal_columnchart_" + typecategoryname + '_' + userid + "_hours_" + from + "_" + to));
          chart.draw(view, options);  
      }
  }

    //2nd tab
    function drawOneTypeModalChart(myhours, typecategoryid, typecategoryname, typeid, typename, from, to) {
      var data, temp, chartColorArr = ["blue", "#c8001d", "green", "purple", "blue"];
      var index = 0, temp1 = [["Workhours", "Hours", { role: "style" } ]];
      for (var k in myhours) {
        if (myhours.hasOwnProperty(k)) {
           temp = [k, myhours[k], chartColorArr[index%chartColorArr.length]];
           temp1.push(temp);
        }
        index++;
      }
      //console.log("at 213, " + JSON.stringify(temp));
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
          title: typecategoryname + '-' + typename + '-' + mytrans.from + ':' + from + '-' + mytrans.to + ':' + to
        },
        vAxis: {
          title: mytrans.Hours
        }
      };
      if($("div#modal_columnchart_" + typecategoryname + '_' + typeid + "_hours_" + from + "_" + to).length == 0) {
          $('div#modal_' + typecategoryid + '_type_charts').append('<div class="col-xs-12 col-md-4 col-lg-4" id="modal_columnchart_' + typecategoryname + '_' + typeid + '_hours_' + from + '_' + to + '"></div>');
          var chart = new google.visualization.ColumnChart(document.getElementById("modal_columnchart_" + typecategoryname + '_' + typeid + "_hours_" + from + "_" + to));
          chart.draw(view, options);  
      }
  }

  function closeModal() {
      $('#myModal').hide();
  }