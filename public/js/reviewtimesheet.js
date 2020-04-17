/*
  Author: Ming's IT Services Ltd.
  Date: 2018-09-01
  Email: mingtl2010@gmail.com
  Copyright: All rights reserved.
*/
  var allCorrect = true;

  function previewOthersTimesheet(myobj) {
    $('div#myModal').show();
    var mytemp = window.location.href.replace('/create','');
    var mytemp2 = mytemp.replace(/\/edit\/.*$/,'');
	var mytemp3Arr = mytemp2.split("?");
	//console.log("at 14, mytemp3Arr[0]: " + mytemp3Arr[0]);
    var mydata = {};
	var myurl = mytemp3Arr[0] + '/getdetail/' + myobj.ID + ((mytemp3Arr[1] === '' || mytemp3Arr[1] == null ) ? '' : ("?" + mytemp3Arr[1]));
    mydata['_token'] = $('input#_token').val();
    //console.log("at 18, href: " + window.location.href + ", mytemp: " + mytemp + ", mytemp2: " + mytemp2 + ", url: " + myurl);
    $.ajax({
      method: "POST",
      url: myurl,
      data: mydata
    })
    .done(function( msg ) {
        if(msg === '') {
            $('div.modalContent').html('');
        } else {
            //console.log("at 28, msg: " + msg);
            var header = ['User Name','From','To','Hours','Type Name'];
            var mytable = '<table class="table table-bordered table-striped table-hover"><tr>';

            for (var key in myobj) {
               if (myobj.hasOwnProperty(key)) {
                  mytable += '<th>' + mytrans[key] + '&nbsp;&nbsp;&nbsp;</th>';
               }
            }

            mytable += '</tr>';
            mytable += '<tr>';
            for (var key in myobj) {
               if (myobj.hasOwnProperty(key)) {
                  mytable += '<td>' + myobj[key] + '</td>';
               }
            }
            mytable += '</tr>';

            var objArr = JSON.parse(msg);
            $('div.modalContent').html('');
            $('div.modalContent').append(mytable + '</br>');
            $('div.modalContent').append(objArr['droparea_extra']);
            
			var prefix = '';
			if(window.location.href.indexOf("/index.php") !== -1) {
				prefix = "/index.php";
			} else {
				prefix = "";
			}
		
            var t = '<a href="' + prefix + '/reviewtimesheet/edit/' + myobj.ID + '" class="btn btn-success">' + mytrans.Review + '</a>';
            $('div.modalContent').append(t);
            $('div#myModal a.remove-left-item').remove();
            $("div#myModal input").prop('disabled', true);
            $("div#myModal div.droparea div.dd-left").each(function(){
                var temp = $(this).text();
                if($(this).text().trim() === '') {
                    $(this).hide();
                    $(this).prev().css("font-weight", "normal");
                }
            });
            $("div#myModal div.droparea div.dd-left").addClass('dd-left-preview').removeClass('dd-left');
            $("div#myModal div").each(function(){
              if($(this).attr('id')){
                  $(this).attr('id', 'm' + $(this).attr('id'))
              }
            });
        }
    })
    .fail(function( jqXHR, textStatus ) {
        alert( mytrans.Requestfailed + ": " + textStatus );
    });
  }

  function closeModal() {
      $('#myModal').hide();
  }

  function submitTimesheetData() {
      var result = {};
      var tname = $.trim($("input#name").val());
      var tdesc = $.trim($("textarea#desc").val());
      var finaldata = {};
      $("div#myModal div.modalContent div.droparea").each(function(){
          var myelement = $(this);
          if(myelement.find('div.dd-left-preview').text().trim() !== '') {
              var temp = myelement.find('.dd-left-header').text().trim();
              var m = temp.split(', ');
              var mydate = m[1].trim();
              result[mydate] = [];
              myelement.find('div.left-item').each(function(){
                  var starttime = $(this).find('input.starttime').val();
                  var endtime = $(this).find('input.endtime').val();
                  var classes = $(this).find('span.left-name').attr('class').split(' ');
                  classes.forEach(function(element) {
                    if(element.substring(0,4) === 'cat_') {
                       var t = element.split('_');
                       var myrow = {};
                       myrow[starttime + '_' + endtime] = t[1] + '_' + t[2];
                       result[mydate].push(myrow);
                    }
                  });
                  
              });
          }
      });
      //console.log('at 115, ' + JSON.stringify(result));
      finaldata["id"] = $('input#id').val();
      finaldata["result"] = JSON.stringify(result);
      finaldata["name"] = tname;
      finaldata["desc"] = tdesc;

      var tempdaterange = [];
      $('div#left-header div.row').each(function(){
          var fromdate = $(this).find('input.fromdate').val().trim();
          var todate = $(this).find('a.todate').text().trim();
          tempdaterange.push(fromdate + '_' + todate);
      });
      finaldata["timeranges"] = tempdaterange.toString();

      finaldata['_token'] = $('input#_token').val();

      //console.log('at 131, finaldata: ' + JSON.stringify(finaldata));

      if(Object.getOwnPropertyNames(result).length > 0) {
          //use ajax to post to /create
          $.ajax({
              method: "POST",
              url: window.location.href,
              data: finaldata
            })
            .done(function( msg ) {
                //console.log( "at 141, msg: " + msg );
                if(msg === 'success') {
                    var mytemp = window.location.href.replace('/create','');
                    var mytemp2 = mytemp.replace(/\/edit\/.*$/,'');
                    window.location.href = mytemp2;
                }else if(msg === 'duplicate') {
                    alert(mytrans.YoualreadysavedsometimeperioddataintodatabaseDuplicatedPleasecreateagain);
                    location.reload();
                }else if(msg.indexOf('notequal:') !== -1) {
                    alert(mytrans.NotallthedatacanbesavedintodatabasePleasecreateagain + msg);
                    location.reload();
                }else if(msg.indexOf('error:') !== -1) {
                    alert(mytrans.ErrorPleasecreateagain + msg);
                    location.reload();
                }
            })
            .fail(function( jqXHR, textStatus ) {
                alert( mytrans.Requestfailed + ": " + textStatus );
            });
      } else {
          alert(mytrans.Pleaseenterdatetimeinfo);
      }
  }

    function showSection(myid) {
        var temp = $('select#' + myid).find(":selected").val();
        if(temp === '0') {
            $('div.timesheet_create_right_section').hide();
        } else if($('div#' + temp).length > 0) {
            $('div.timesheet_create_right_section').hide();
            $('div#' + temp).show();
        } else if($('div#' + temp).length == 0) {
            $('div.timesheet_create_right_section').hide();
        }
    }