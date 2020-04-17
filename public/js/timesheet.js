/*
  Author: Ming's IT Services Ltd.
  Date: 2018-09-01
  Email: mingtl2010@gmail.com
  Copyright: All rights reserved.
*/

  var timesheet_drop_counter = 0, allCorrect = true; 

  $( function() {
	$('select#dropdownlist_typecategory').val(0);
    $('select#typecategory').val('0');

    $(".draggable").draggable({
        helper: "clone"
    });

    $('input.datepicker').each(function(){
        $(this).datetimepicker({
            timepicker:false,
            format:'Y-m-d',
            onChangeDateTime:onFromDateChangeDateTime
        });
    });

    $('input.timepicker').each(function(){
        $(this).datetimepicker({
            format:'H:i',
            datepicker:false,
        });
    });

    if($('input#fordate').val().trim() !== '') {
      //var d = new Date($('input#fordate').val().trim() + "T00:00:00Z");
      //d.setDate(d.getDate() + 6);
      updateComposeAreaHeaderDate();
    }

    enableDroppableSortable();
    //update1to7Backgroundcolor();
    //$('a.weekdays').each(function(){
        //var t = $(this).attr('id');
        updateComposeAreaHeaderDate();
    //});
	
	enableDoubleClickDropChangeColor('div.item.doubleclickable', 'div#left-drop div.dropweek_box div.droparea.active div.dd-left');
  } );

  function enableDoubleClickDropChangeColor(sourceElement, targetElement) {
	var touchtime = 0;
	$(sourceElement).on("click", function() {
		if (touchtime == 0) {
			// set first click
			touchtime = new Date().getTime();
		} else {
			if (((new Date().getTime()) - touchtime) < 800) {
				var temp = $(this).attr('id').split("_");
				var category = (temp.length == 2) ? temp[0] : '';
				var id = (temp.length == 2) ? temp[1] : '';
				var temp2 = '<div class="inline scrolloverflow">' + mytrans.From + ': <input type="text" class="timepicker starttime" value="" />';
				temp2 += '  ' + mytrans.To + ': <input type="text" class="timepicker endtime" value="" /></div>';
				temp2 += '<span class="bold left-name ' + 'cat_' + category + '_' + id + '">' + $(this).text() + '</span>';
				temp2 += '<a class="up-left-item" href="javascript:void(0);" onclick="upParentChangeColor(this);"><i class="fa fa-arrow-up font24px" aria-hidden="true" title="' + mytrans.Up + '"></i></a>';
				temp2 += '<a class="down-left-item" href="javascript:void(0);" onclick="downParentChangeColor(this);"><i class="fa fa-arrow-down font24px" aria-hidden="true" title="' + mytrans.Down + '"></i></a>';						
				temp2 += '<a class="remove-left-item" href="javascript:void(0);" onclick="removeParentChangeColor(this);"><i class="fa fa-trash-o font24px" aria-hidden="true" title="' + mytrans.Remove + '"></i></a>';
				var newDiv = '<div class="left-item ' + category + ' ' + ((temp.length == 2) ? $(this).attr('id') : '') +'">' + temp2 + '</div>';
				$(targetElement).append(newDiv);
				$('input.timepicker').each(function(){
					$(this).datetimepicker({
						format:'H:i',
						datepicker:false,
					});
				});
			} else {
				// not a double click so set as a new first click
				touchtime = new Date().getTime();
			}
		}
    });
}

  function getDateFromUTCString(d, getDate) {
      //Sat, 12 Aug 2017 00:00:00 GMT
      var n = d.toUTCString();
      var re = / (20[0-9][0-9]) /;
      var r = n.split(re);
      var myYear = r[1];
      var rex = /, /;
      var r1 = r[0].split(rex);
      var s = r1[1].split(" ");
      var h = '';
      if (getDate === undefined) {
        getDate = 0;
      }
      if(getDate === 1) {//return Mon, ... Sat
        return mytrans.Myweek[r1[0]];
      } else {
          var myDay = s[0];
          var mon = s[1].toLowerCase();
          var days = {'jan':'01', 'feb':'02', 'mar':'03', 'apr':'04', 'may':'05', 'jun':'06', 'jul':'07', 'aug':'08', 'sep':'09', 'oct':'10', 'nov':'11', 'dec':'12'};
          var myMonth = days[mon];    
          h = myYear + '-' + myMonth + '-' + myDay;
          if(getDate === 0) {
            return h;
          } else if(getDate === 2) {//return Sat, 2017-08-06
            return mytrans.Myweek[r1[0]] + ', ' + h;
          }
      }
  }

  function enableDroppableSortable() {
    $( "div.dd-left" ).droppable({
      accept: ":not(.ui-sortable-helper)",
      drop: function( event, ui ) {
        var temp = $(ui.draggable).attr('id').split("_");
        var category = (temp.length == 2) ? temp[0] : '';
        var id = (temp.length == 2) ? temp[1] : '';
        var temp2 = '<div class="inline scrolloverflow">' + mytrans.From + ': <input type="text" class="timepicker starttime" value="" />';
        temp2 += '  ' + mytrans.To + ': <input type="text" class="timepicker endtime" value="" /></div>';
        temp2 += '<span class="bold left-name ' + 'cat_' + category + '_' + id + '">' + $(ui.draggable).text() + '</span>';
 		temp2 += '<a class="up-left-item" href="javascript:void(0);" onclick="upParentChangeColor(this);"><i class="fa fa-arrow-up font24px" aria-hidden="true" title="' + mytrans.Up + '"></i></a>';
		temp2 += '<a class="down-left-item" href="javascript:void(0);" onclick="downParentChangeColor(this);"><i class="fa fa-arrow-down font24px" aria-hidden="true" title="' + mytrans.Down + '"></i></a>';	
        temp2 += '<a class="remove-left-item" href="javascript:void(0);" onclick="removeParentChangeColor(this);"><i class="fa fa-trash-o font24px" aria-hidden="true" title="' + mytrans.Remove + '"></i></a>';
        var newDiv = '<div class="left-item ' + category + ' ' + ((temp.length == 2) ? $(ui.draggable).attr('id') : '') +'">' + temp2 + '</div>';
        $(this).append(newDiv);
        $('input.timepicker').each(function(){
            $(this).datetimepicker({
                format:'H:i',
                datepicker:false,
            });
        });
      }
    });

    $( "div.dd-left" ).sortable({
        stop : function (e,ui) {
            ui.item.first().removeAttr('style');
        }
    });
    $("div.dd-left").disableSelection();
  }

  function upParentChangeColor(element) {
      $(element).parent().insertBefore($(element).parent().prev());
      update1to7Backgroundcolor();
  }
  
  function downParentChangeColor(element) {
      $(element).parent().insertAfter($(element).parent().next());
      update1to7Backgroundcolor();
  }
  
  function removeParentChangeColor(element) {
      $(element).parent().remove();
      update1to7Backgroundcolor();
  }

  function searchfilter(elem, category) {
    var temp = $(elem).val().toLowerCase();
    $('div#' + category + ' div.item').each(function(index){
        if($(this).text().toLowerCase().indexOf(temp) !== -1) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
  }

  function autoFillTime() {
	  var temp_fromdate_all_filled = true;
	  $('input.fromdate').each(function(){
	      if($(this).val().trim() === ''){
			  temp_fromdate_all_filled = false;
		  }
	  });
	  if(!temp_fromdate_all_filled) {alert(mytrans.Pleasefillallthedatefirst);return 'nodate';}
	  
      $('div#left-drop .endtime').each(function(){
          if($(this).val().trim() === ''){
              var temp = $(this).parent().parent().next('.left-item').find('.starttime:first');
              var myvalue = temp ? temp.val() : '';
              $(this).val(myvalue);
          }
      });
      allCorrect = true;
      var wrongDates = [];
      $('div#left-drop .endtime').each(function(){
          //check if the above "end time" field is empty
          if($(this).val().trim() !== ''){
              var temp0 = $(this).parent().parent().next('.left-item');
              var temp_correct = true;
              if(temp0.length > 0 && temp0.find('.starttime:first').length > 0){
                var temp = temp0.find('.starttime:first');
                if($(this).val().trim() !== temp.val()){
                    allCorrect = temp_correct = false;
                    temp_wrongdates = false;
                    var s = $(this).parent().parent().parent().prev('.dd-left-header');
                    if(s){
						if($.inArray(('"' + s.html() + '"'), wrongDates) === -1) wrongDates.push(('"' + s.html() + '"'));
					}
                }
                if(temp_correct) {
                    //check if the beging time is earlier than end time for each row in the drop box
                    var temp1 = parseFloat($(this).parent().find('.starttime:first').val().trim().replace(':', '.'));
                    var temp2 = parseFloat($(this).val().trim().replace(':', '.'));
                    //check if the above "start time" field is empty
                    if($(this).parent().find('.starttime:first').val().trim() === '' || temp1 >= temp2){
                        allCorrect = false;
                        var s = $(this).parent().parent().parent().prev('.dd-left-header');
                        if(s){
							if($.inArray(('"' + s.html() + '"'), wrongDates) === -1) wrongDates.push(('"' + s.html() + '"'));
						}
                    }
                }
              }
          } else {
            allCorrect = false;
            var s = $(this).parent().parent().parent().prev('.dd-left-header');
            if(s){
				if($.inArray(('"' + s.html() + '"'), wrongDates) === -1) wrongDates.push(('"' + s.html() + '"'));
			} 
          }
      });

      if(!allCorrect){
          alert(mytrans.Timeisnotcontinuousatdate + ': ' + wrongDates.join(' ; '));
      }
  }

  function setDateForComposeArea(element) {
      $('div#left-header div.row div a').removeClass('active');
      $(element).addClass('active');
      updateComposeAreaHeaderDate();
      $('div#left-drop div.droparea').removeClass('active');
      var temp=element.id.split('week')[1];
      if(temp !== '') {
          $('div#dd' + temp).addClass('active');
      }
      update1to7Backgroundcolor();
  }

  /*function addNewWeek(element) {
    //get last week's id's middle number
    var maxRowNumber = parseInt($('#left-header>div.row').last().find('.fromdate').attr('id').split('_')[1]) + 1;
    if (typeof timesheet_week_amount === 'undefined' || (typeof timesheet_week_amount !== 'undefined' && $('#left-header>div.row').length < timesheet_week_amount)) {   
        var temp = '<div class="row">';
            temp += '<div  class="col-xs-6 col-md-2"><a href="javascript:void(0);" class="btn btn-primary bold">' + mytrans.Week + ' <span class="week_number"></span>-' + mytrans.From + ':</a></div>';
            temp += '<div  class="col-xs-6 col-md-2"><input type="text" name="daterange_' + maxRowNumber + '_1" id="daterange_' + maxRowNumber + '_1" class="fromdate datepicker btn btn-default form-control bold" value="" /></div>';
            temp += '<div  class="col-xs-6 col-md-1"><a href="javascript:void(0);" class="btn btn-primary bold">' + mytrans.To + ':</a></div>';
            temp += '<div  class="col-xs-6 col-md-2"><a name="daterange_' + maxRowNumber + '_2" id="daterange_' + maxRowNumber + '_2" class="btn btn-default form-control bold todate" href="javascript:void(0);"></a></div>';

            temp += '<div  class="col-xs-12 col-md-5">';
            temp += '<a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_' + maxRowNumber + '_1" class="btn btn-default bold weekdays">1</a> ';
            temp += '<a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_' + maxRowNumber + '_2" class="btn btn-default bold weekdays">2</a> ';
            temp += '<a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_' + maxRowNumber + '_3" class="btn btn-default bold weekdays">3</a> ';
            temp += '<a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_' + maxRowNumber + '_4" class="btn btn-default bold weekdays">4</a> ';
            temp += '<a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_' + maxRowNumber + '_5" class="btn btn-default bold weekdays">5</a> ';
            temp += '<a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_' + maxRowNumber + '_6" class="btn btn-default bold weekdays">6</a> ';
            temp += '<a href="javascript:void(0);" onclick="setDateForComposeArea(this);" id="week_' + maxRowNumber + '_7" class="btn btn-default bold weekdays">7</a> ';

            temp += '<a href="javascript:void(0);" onclick="addNewWeek(this);" class="btn bold week-add"><i class="fa fa-plus-circle font24px" aria-hidden="true" title="Add a new row under the last row"></i></a> ';
            temp += '<a href="javascript:void(0);" onclick="removeThisWeek(this);" class="btn bold week-remove"><i class="fa fa-minus-circle font24px" aria-hidden="true" title="Remove this row"></i></a>';

            temp += '</div></div>';
        $('#left-header').append(temp);

        var dd = '<div id="ddbox_' + maxRowNumber + '" class="dropweek_box">';
            dd += '<div id="dd_' + maxRowNumber + '_1" class="droparea"><div id="header_' + maxRowNumber + '_1" class="dd-left-header"></div><div class="dd-left"></div></div>';
            dd += '<div id="dd_' + maxRowNumber + '_2" class="droparea"><div id="header_' + maxRowNumber + '_2" class="dd-left-header"></div><div class="dd-left"></div></div>';
            dd += '<div id="dd_' + maxRowNumber + '_3" class="droparea"><div id="header_' + maxRowNumber + '_3" class="dd-left-header"></div><div class="dd-left"></div></div>';
            dd += '<div id="dd_' + maxRowNumber + '_4" class="droparea"><div id="header_' + maxRowNumber + '_4" class="dd-left-header"></div><div class="dd-left"></div></div>';
            dd += '<div id="dd_' + maxRowNumber + '_5" class="droparea"><div id="header_' + maxRowNumber + '_5" class="dd-left-header"></div><div class="dd-left"></div></div>';
            dd += '<div id="dd_' + maxRowNumber + '_6" class="droparea"><div id="header_' + maxRowNumber + '_6" class="dd-left-header"></div><div class="dd-left"></div></div>';
            dd += '<div id="dd_' + maxRowNumber + '_7" class="droparea"><div id="header_' + maxRowNumber + '_7" class="dd-left-header"></div><div class="dd-left"></div></div>';
            dd += '</div>';
        $('div#left-drop').append(dd);

        updateWeekNumbers();
        $('input.datepicker').each(function(){
            $(this).datetimepicker({
                timepicker:false,
                format:'Y-m-d',
                onChangeDateTime:onFromDateChangeDateTime,
				lang: mylang
            });
        });
        enableDroppableSortable();
    }
  }

  function removeThisWeek(element) {
     var weekRowNumber = $(element).parent().parent().find('.fromdate').attr('id').split('_')[1];
     $(element).parent().parent().remove();
     //also remove the droppable area related to this row of week
     $('div#ddbox_' + weekRowNumber).remove();
     //if active tab was in this row, reset the active tab to 1_1
     var temp = $('div#left-header a.active').length;
     if($('div#left-header a.active').length == 0) {
         $('a#week_1_1').addClass('active');
         $('div#dd_1_1').addClass('active');
     }
     updateWeekNumbers();
  }*/

  function getWeekdayFromDate(mydate, gap) {
      if(mydate !== '') {
        var d = new Date(mydate);
        var y = mydate.split('-')[0];
        d.setDate(d.getDate() + gap);
        return getDateFromUTCString(d, 2);
      }
      return '';
  }

  function updateWeekNumbers() {
    var weekCounter = 1;
    $('span.week_number').each(function(){
        $(this).text(weekCounter);
        weekCounter++;
    });
  }

  function updateComposeAreaHeaderDate() {
      //var temp = myid.split('week')[1];
      //var headerid = 'header' + temp;
      var fromdate = $("#fordate").val();
      fromdate = fromdate ? fromdate.trim() : '';
      //var gapFromDate = parseInt(myid.split('_')[2]) - 1;
      //var headerContent = (fromdate && (gapFromDate >= 0)) ? getWeekdayFromDate(fromdate, gapFromDate) : '';
	  var headerContent = (fromdate) ? getWeekdayFromDate(fromdate, 0) : '';
      $('div#header').text(headerContent);
  }

  function onFromDateChangeDateTime(mydate, element) {
      //var redID = $('div#left-header div.row div a.active') ? $('div#left-header div.row div a.active').attr('id') : 0;
      var timepickerID = $(element).attr('id');
      if(jQuery(element).val() !== '') {
          //if(timepickerID === 'fordate') {
              /*var d = getDayBeginning(mydate);
              if(d.getDay() !== 6 && d.getDay() !== 0 && d.getDay() !== 1) { 
                  $(element).val('');
                  //$('#' + timepickerID.split('_')[0] + '_' + timepickerID.split('_')[1] + '_2').text('');
                  alert(mytrans.PleaseenterMondaySundayorSaturday);
                  return;
              } else {
                  //fill the todate
                  d.setDate(d.getDate() + 6);
                  var m = (d.getMonth() + 1).toString();
                  if(m.length == 1){
                      m = '0' + m;
                  } else if(m.length != 2 && m.length != 0){
                      return;
                  }
                  var myday = d.getDate().toString();
                  if(myday.length == 1){
                      myday = '0' + myday;
                  }
                  //var temp = '' + d.getFullYear() + '-' + m + '-' + myday;
                  //$('#' + timepickerID.split('_')[0] + '_' + timepickerID.split('_')[1] + '_2').text(temp);
              }*/
          //}
          //if(redID && timepickerID && (redID.split('_')[1] === timepickerID.split('_')[1])) {
          //    updateComposeAreaHeaderDate(redID);
          //}
          //$('a.weekdays').each(function(){
              //var t = $(this).attr('id');
              updateComposeAreaHeaderDate();
          //});
      }
  }

  function getDayBeginning(mydate) {
      var d = new Date(mydate);
      d.setHours(0);
      d.setMinutes(0);
      d.setSeconds(0);
      return d;
  }

  function getGMTTime(mydate) {
      var d = new Date(mydate);
      d.setMinutes( d.getMinutes() - d.getTimezoneOffset() );
      return d;
  }

  function update1to7Backgroundcolor() {
      $('a.weekdays').each(function(){
          var temp = $(this).attr('id').split('week')[1];
          if($('div#dd' + temp + '>.dd-left') && $('div#dd' + temp + '>.dd-left').html().trim() !== '') {
              $(this).addClass('filled');
              if($(this).hasClass('active')){
                $(this).removeClass('active');
                $(this).addClass('active');
              }
          } else {
              $(this).removeClass('filled');
          }
      });
  }

  function previewTimesheet() {
      if(autoFillTime() === 'nodate') {return};
	  
      if(allCorrect){
          $("div#left-drop input").each(function(){
              $(this).attr("value", $(this).val());
          });
          $('div#myModal').show();
          $('div.modalContent').html($('div#left-drop').html());
          var t = '<a name="submit" class="btn btn-primary bold" onclick="submitTimesheetData(this);">' + mytrans.Submit + '</a>';
		  t += '<a name="submit" class="btn bold" onclick="closeModal();">' + mytrans.Cancel + '</a>';
          $('div.modalContent').append(t);
		  $('div#myModal a.up-left-item').remove();
		  $('div#myModal a.down-left-item').remove();
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
  }

  function closeModal() {
      $('#myModal').hide();
  }

  function submitTimesheetData(element) {
      var result = {};
      var tname = $.trim($("input#name").val());
      var tdesc = $.trim($("textarea#desc").val());
      var finaldata = {};
	  var elementOrigionalText = $(element).text();
	  $(element).replaceWith(function(){
        return $('<a name="submit" class="btn btn-primary bold" >' + mytrans.Submitting + '...</a>');
      });
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

      finaldata["id"] = $('input#id').val();
      finaldata["result"] = JSON.stringify(result);
      finaldata["name"] = tname;
      finaldata["desc"] = tdesc;

      var tempdaterange = [];
      //$('div#left-header div.row').each(function(){
          var fromdate = $('input#fordate').val().trim();
          //var todate = $(this).find('a.todate').text().trim();
          tempdaterange.push(fromdate);
      //});
      finaldata["timeranges"] = tempdaterange.toString();

      finaldata['_token'] = $('input#_token').val();
      
      //console.log('at 563, finaldata: ' + JSON.stringify(finaldata));

      if(Object.getOwnPropertyNames(result).length > 0) {
          //use ajax to post to /create
          $.ajax({
              method: "POST",
              url: window.location.href,
              data: finaldata
            })
            .done(function( msg ) {
                //console.log( "at 573, msg: " + msg );
                //console.log('at 574, finaldata: ' + JSON.stringify(finaldata));
                if(msg === 'success-url') {
                    var mytemp = window.location.href.replace('/create','');
                    var mytemp2 = mytemp.replace(/\/edit\/.*$/,'');
                    alert(mytrans.TimesheetsavedPostedtoallwebhooks);
                    window.location.href = mytemp2;
                }else if (msg === 'success-partialurl') {
                    var mytemp = window.location.href.replace('/create','');
                    var mytemp2 = mytemp.replace(/\/edit\/.*$/,'');
                    alert(mytrans.TimesheetsavedPostedtosomeofthewebhooks);
                    window.location.href = mytemp2;
                }else if (msg === 'success-emptyurl') {
                    var mytemp = window.location.href.replace('/create','');
                    var mytemp2 = mytemp.replace(/\/edit\/.*$/,'');
                    alert(mytrans.TimesheetsavedNowebhooksavailable);
                    window.location.href = mytemp2;
                }else if(msg === 'duplicate') {
                    alert(mytrans.YoualreadysavedsometimeperioddataintodatabaseDuplicatedPleasecreateagain);
                    location.reload();
                }else if(msg.indexOf('notequal:') !== -1) {
                    alert(mytrans.NotallthedatacanbesavedintodatabasePleasecreateagain + msg);
                    location.reload();
                }else if(msg.indexOf('notyours') !== -1) {
					var mytemp = window.location.href.replace('/create','');
                    var mytemp2 = mytemp.replace(/\/edit\/.*$/,'');
                    alert(mytrans.Thisrecordisnotyours);					
                    window.location.href = mytemp2;
                }else if(msg.indexOf('error:') !== -1) {
                    alert(mytrans.ErrorPleasecreateagain + msg);
                    location.reload();
                }
            })
            .fail(function( jqXHR, textStatus ) {
                alert( mytrans.Requestfailed + ": " + textStatus + ". " + mytrans.Pleaserefreshthepageandtryagain);
            });
      } else {
          alert(mytrans.Pleaseenterdatetimeinfo);
      }
	  $(element).replaceWith(function(){
        return $('<a name="submit" class="btn btn-primary bold" onclick="submitTimesheetData(this);">' + mytrans.Submit + '</a>');
      });
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