/*
  Author: Ming's IT Services Ltd.
  Date: 2018-09-01
  Email: mingtl2010@gmail.com
  Copyright: All rights reserved.
*/
  var counter = 0, myclass = ['name', 'starttime', 'endtime', 'hours', 'typename'];
  var topagenumber_type = topagenumber_user = totalpagenumber_type = totalpagenumber_user = 1;

  $( function() {
	$('select#dropdownlist_typecategory').val(0);
    $( "#tabs,#tabs1,#tabs2" ).tabs();

    $( ".draggable" ).draggable({
        helper: "clone"
    });

    $('input.datepicker').each(function(){
        $(this).datetimepicker({
            timepicker:false,
            format:'Y-m-d',
            onChangeDateTime:onFromDateChangeDateTime
        });
    });

    enableDroppableSortable();

    $( 'div.pwrapper' ).on( 'click', 'div.mypagination > div:not(.noborder):not(.disabled)', function () {
        var topagenumber = isNaN(parseInt($(this).text())) ? 0 : parseInt($(this).text());
        var currentpage;
        if($(this).hasClass('prev')){
            currentpage = isNaN(parseInt($('div.pwrapper > div.mypagination > div.active').first().text())) ? 0 : parseInt($('div.pwrapper > div.mypagination > div.active').first().text());
            topagenumber = Math.max(1, (currentpage-1));
        } else if ($(this).hasClass('next')){
            currentpage = isNaN(parseInt($('div.pwrapper > div.mypagination > div.active').first().text())) ? 0 : parseInt($('div.pwrapper > div.mypagination > div.active').first().text());
            topagenumber = Math.min(totalpagenumber_type, (currentpage+1));
        }
        showReportOnPage('type', topagenumber);
    });

    $( 'div.pwrapper2' ).on( 'click', 'div.mypagination > div:not(.noborder):not(.disabled)', function () {
        var topagenumber2 = isNaN(parseInt($(this).text())) ? 0 : parseInt($(this).text());
        var currentpage;
        if($(this).hasClass('prev')){
            currentpage = isNaN(parseInt($('div.pwrapper2 > div.mypagination > div.active').first().text())) ? 0 : parseInt($('div.pwrapper2 > div.mypagination > div.active').first().text());
            topagenumber2 = Math.max(1, (currentpage-1));
        } else if ($(this).hasClass('next')){
            currentpage = isNaN(parseInt($('div.pwrapper2 > div.mypagination > div.active').first().text())) ? 0 : parseInt($('div.pwrapper2 > div.mypagination > div.active').first().text());
            topagenumber2 = Math.min(totalpagenumber_user, (currentpage+1));
        }
        showReportOnPage('user', topagenumber2);
    });

    $('div#show_result_type').on( 'click', 'table th i', function () {
        var class0 = 'fa fa-sort';
        var mysortby = $(this).parent().attr('class');
        var myorder = '';
        if($(this).hasClass('fa-sort')){
            myorder = 'asc';
        } else if($(this).hasClass('fa-sort-asc')){
            myorder = 'desc';
        } else if($(this).hasClass('fa-sort-desc')){
            myorder = 'asc';
        }
        showReportOnPage('type', 1, mysortby, myorder);
    });

    $('div#show_result_user').on( 'click', 'table th i', function () {
        var class0 = 'fa fa-sort';
        var mysortby = $(this).parent().attr('class');
        var myorder = '';
        if($(this).hasClass('fa-sort')){
            myorder = 'asc';
        } else if($(this).hasClass('fa-sort-asc')){
            myorder = 'desc';
        } else if($(this).hasClass('fa-sort-desc')){
            myorder = 'asc';
        }
        showReportOnPage('user', 1, mysortby, myorder);
    });
	
	enableDoubleClickDropReport('div#tabs1 div.dd-right > div.item.doubleclickable', 'form#form_reportbytypes div.droparea div.dd-left');
	enableDoubleClickDropReport('div#users div.dd-right > div.item.doubleclickable', 'form#form_reportbyusers div.droparea div.dd-left');
    
  } );

  function createFile(type) {
      var temp1 = '';
      if(type=='type') {
          if($('div#ddbox div#dd div.dd-left div.left-item').length == 0) {
              return alert(mytrans.Pleasedraganddroptypesfromtherightside);
          }
          $('div#ddbox div#dd div.dd-left div.left-item').each(function(){
              temp1 += $(this).attr('id') + ',';
          });
          if(temp1.substring(temp1.length - 1) === ',') {
              temp1 = temp1.substring(0, temp1.length - 1);
          }
          $('input#mytypes').val(temp1); 
          $('form#form_reportbytypes').submit();
      }else if(type==='user'){
          if($('div#ddbox2 div#dd2 div.dd-left div.left-item').length == 0) {
              return alert(mytrans.Pleasedraganddropusersfromtherightside);
          }
          $('div#ddbox2 div#dd2 div.dd-left div.left-item').each(function(){
              temp1 += $(this).attr('id') + ',';
          });
          if(temp1.substring(temp1.length - 1) === ',') {
              temp1 = temp1.substring(0, temp1.length - 1);
          }
          $('input#myusers').val(temp1);
          $('form#form_reportbyusers').submit();
      }
  }

  function enableDoubleClickDropReport(sourceElement, targetElement) {
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
				var temp2 = '<span class="bold left-name ' + 'cat_' + category + '_' + id + '">' + $(this).text() + '</span>';
				temp2 += '<a class="up-left-item" href="javascript:void(0);" onclick="upParent(this);"><i class="fa fa-arrow-up font24px" aria-hidden="true" title="Up this row"></i></a>';
				temp2 += '<a class="down-left-item" href="javascript:void(0);" onclick="downParent(this);"><i class="fa fa-arrow-down font24px" aria-hidden="true" title="Down this row"></i></a>';	 
				temp2 += '<a class="remove-left-item" href="javascript:void(0);" onclick="removeParent(this);"><i class="fa fa-trash-o font24px" aria-hidden="true" title="Remove this row"></i></a>';
				//no duplicate
				var temp3 = $(this).attr('id');
				var duplicate = false;
				if($(targetElement).parent().attr('id') === 'dd'){
					$('div#ddbox div#dd div.dd-left div.left-item').each(function(){
						if($(this).attr('id') === ('u' + temp3)) {
							duplicate = true;
						}
					});
				} else if($(targetElement).parent().attr('id') === 'dd2'){
					$('div#ddbox2 div#dd2 div.dd-left div.left-item').each(function(){
						if($(this).attr('id') === ('u' + temp3)) {
							duplicate = true;
						}
					});
				}
				if(!duplicate) {
					var newDiv = '<div class="left-item ' + category + '" id="u' + ((temp.length == 2) ? $(this).attr('id') : '') + '">' + temp2 + '</div>';
					$(targetElement).append(newDiv);
				} else {
					alert(mytrans.duplicate);
				}
			} else {
				// not a double click so set as a new first click
				touchtime = new Date().getTime();
			}
		}
	});
  }
  
    function enableDroppableSortable() {
        $( "div.dd-left" ).droppable({
          accept: ":not(.ui-sortable-helper)",
          drop: function( event, ui ) {
            var temp = $(ui.draggable).attr('id').split("_");
            var category = (temp.length == 2) ? temp[0] : '';
            var id = (temp.length == 2) ? temp[1] : '';
            var temp2 = '<span class="bold left-name ' + 'cat_' + category + '_' + id + '">' + $(ui.draggable).text() + '</span>';
		    temp2 += '<a class="up-left-item" href="javascript:void(0);" onclick="upParent(this);"><i class="fa fa-arrow-up font24px" aria-hidden="true" title="Up this row"></i></a>';
		    temp2 += '<a class="down-left-item" href="javascript:void(0);" onclick="downParent(this);"><i class="fa fa-arrow-down font24px" aria-hidden="true" title="Down this row"></i></a>';	 
            temp2 += '<a class="remove-left-item" href="javascript:void(0);" onclick="removeParent(this);"><i class="fa fa-trash-o font24px" aria-hidden="true" title="Remove this row"></i></a>';
            //no duplicate
            var temp3 = $(ui.draggable).attr('id');
            var duplicate = false;
            if($(this).parent().attr('id') === 'dd'){
                $('div#ddbox div#dd div.dd-left div.left-item').each(function(){
                    if($(this).attr('id') === ('u' + temp3)) {
                        duplicate = true;
                    }
                });
            } else if($(this).parent().attr('id') === 'dd2'){
                $('div#ddbox2 div#dd2 div.dd-left div.left-item').each(function(){
                    if($(this).attr('id') === ('u' + temp3)) {
                        duplicate = true;
                    }
                });
            }
            if(!duplicate) {
                var newDiv = '<div class="left-item ' + category + '" id="u' + ((temp.length == 2) ? $(ui.draggable).attr('id') : '') + '">' + temp2 + '</div>';
                $(this).append(newDiv);
            } else {
                alert(mytrans.duplicate);
            }
          }
        });

        $( "div.dd-left" ).sortable({
            stop : function (e,ui) {
                ui.item.first().removeAttr('style');
            }
        });
        $("div.dd-left").disableSelection();
    }

  function showReportOnPage(type, pagenumber, mysortby, myorder) {
      var mydata = {}, temp1 = '';
      mydata['_token'] = $('input#_token').val();
      mydata['pagenumber'] = pagenumber ? pagenumber : 1;
      mydata['mysortby'] = (mysortby === undefined) ? '' : mysortby;
      mydata['myorder'] = (myorder === undefined) ? '' : myorder;

      if (mysortby === undefined && myorder === undefined) {
          $('div#show_result_type table th i').each(function () {
                if($(this).hasClass('fa-sort-asc')){
                    mydata['myorder'] = 'asc';
                    mydata['mysortby'] = $(this).parent().attr('class');
                } else if($(this).hasClass('fa-sort-desc')){
                    mydata['myorder'] = 'desc';
                    mydata['mysortby'] = $(this).parent().attr('class');
                }
          });
      }

      if(type==='type') {
          mydata['daterange_1'] = $('input#daterange_1').val();
          mydata['daterange_2'] = $('input#daterange_2').val();
          mydata['amountperpage'] = $('#amountperpage').find(":selected").val();
          if($('div#ddbox div#dd div.dd-left div.left-item').length == 0) {
              return alert(mytrans.Pleasedraganddroptypesfromtherightside);
          }
          $('div#ddbox div#dd div.dd-left div.left-item').each(function(){
              temp1 += $(this).attr('id') + ',';
          });
          if(temp1.substring(temp1.length - 1) === ',') {
              temp1 = temp1.substring(0, temp1.length - 1);
          }
          mydata['mytypes'] = temp1;
      } else if(type==='user'){
          mydata['daterange_1'] = $('input#daterange_3').val();
          mydata['daterange_2'] = $('input#daterange_4').val();
          mydata['amountperpage'] = $('#amountperpage2').find(":selected").val();
          if($('div#ddbox2 div#dd2 div.dd-left div.left-item').length == 0) {
              return alert(mytrans.Pleasedraganddropusersfromtherightside);
          }
          $('div#ddbox2 div#dd2 div.dd-left div.left-item').each(function(){
              temp1 += $(this).attr('id') + ',';
          });
          if(temp1.substring(temp1.length - 1) === ',') {
              temp1 = temp1.substring(0, temp1.length - 1);
          }
          mydata['myusers'] = temp1;
      }

      //console.log('at 254, mydata: ' + JSON.stringify(mydata));
      $.ajax({
          method: "POST",
          url: window.location.href + '/getdata',
          beforeSend: function( xhr ) {
            $('div.loading .loading').show();
          },
          data: mydata
        })
        .done(function( msg ) {
            //console.log( "at 264, Data got: " + msg );
            var header = ['User Name','From','To','Hours','Type Name'];
            var mytable = '<table class="table table-bordered table-striped table-hover"><tr>';
            for(var i=0; i<header.length;i++) {
                var myicon = 'fa fa-sort';
                if(mydata['mysortby'] === myclass[i]) {
                    switch(mydata['myorder']) {
                        case 'asc' : myicon = 'fa fa-sort-asc';break;
                        case 'desc' : myicon = 'fa fa-sort-desc';break;
                    }
                }
                mytable += '<th class="' + myclass[i] + '">' + header[i] + '&nbsp;&nbsp;&nbsp;<i class="' + myicon + '"></i></th>';
            }
            mytable += '</tr>';
            var objArr = JSON.parse(msg);
            for (var k=0, t=objArr.length; k<t; k++) {
                var obj = objArr[k];
                if (obj.hasOwnProperty('totalpage')) {
                    if(type==='type') {
                        totalpagenumber_type=obj['totalpage'];
                    } else if(type==='user') {
                        totalpagenumber_user=obj['totalpage'];
                    }
                    continue;
                }
                mytable += '<tr>';
                for(var v in obj) {
                    if (obj.hasOwnProperty(v)) {
                        mytable += '<td>' + obj[v] + '</td>';
                    }
                }
                mytable += '</tr>';
            }
            mytable += '</table>';
            $('div#show_result_' + type).html(mytable);

            //refresh pagination row
            var temp = pagenumber ? pagenumber : 1;
            if(type==='type') {
                createPagination(totalpagenumber_type, temp, 'type');
            } else if(type==='user') {
                createPagination(totalpagenumber_user, temp, 'user');
            }
        })
        .fail(function( jqXHR, textStatus ) {
            alert( mytrans.Requestfailed + ": " + textStatus );
        })
        .always(function() {
           $('div.loading .loading').hide();
        });
  }

  function createPagination(totalpagenumber, topagenumber, type) {
      if(totalpagenumber !== parseInt(totalpagenumber, 10) || topagenumber !== parseInt(topagenumber, 10) || totalpagenumber == 1) {$('div.pwrapper > div.mypagination').html('');return;}
      var temp = '',i;
      if(topagenumber > totalpagenumber) {topagenumber = totalpagenumber;}
      if(totalpagenumber < 7) {
         temp = '<div class="' + (topagenumber===1 ? 'disabled' : 'prev') + '">&lt;</div>';
         for(i=1;i<=totalpagenumber;i++) {
             temp += '<div' + (topagenumber===i ? ' class="active" ' : '') + '>' + i + '</div>';
         }
         temp += '<div class="' + (topagenumber===totalpagenumber ? 'disabled' : 'next') + '">&gt;</div>';
      } else if(topagenumber < 4) {
         temp = '<div class="' + (topagenumber===1 ? 'disabled' : 'prev') + '">&lt;</div>';
         for(i=1;i<=5;i++) {
             temp += '<div' + (topagenumber===i ? ' class="active" ' : '') + '>' + i + '</div>';
         }
         temp += ((totalpagenumber - topagenumber) > 3) ? '<div class="noborder">...</div>' : '';
         temp += '<div' + (topagenumber===totalpagenumber ? ' class="active" ' : '') + '>' + totalpagenumber + '</div><div class="' + (topagenumber===totalpagenumber ? 'disabled' : 'next') + '">&gt;</div>';
      } else if(topagenumber < (totalpagenumber - 2)) {
         temp = '<div class="' + (topagenumber===1 ? 'disabled' : 'prev') + '">&lt;</div><div' + (topagenumber===1 ? ' class="active" ' : '') + '>1</div>';
         temp += (topagenumber > 4) ? '<div class="noborder">...</div>' : '';
         for(i=(topagenumber-2);i<=((topagenumber+2)>(totalpagenumber-1) ? (totalpagenumber-1) : (topagenumber+2));i++) {
             temp += '<div' + (topagenumber===i ? ' class="active" ' : '') + '>' + i + '</div>';
         }
         temp += ((totalpagenumber - topagenumber) > 3) ? '<div class="noborder">...</div>' : '';
         temp += '<div' + (topagenumber===totalpagenumber ? ' class="active" ' : '') + '>' + totalpagenumber + '</div><div class="' + (topagenumber===totalpagenumber ? 'disabled' : 'next') + '">&gt;</div>';
      } else if(topagenumber >= (totalpagenumber - 2)) {
         temp = '<div class="' + (topagenumber===1 ? 'disabled' : 'prev') + '">&lt;</div>';
         temp += '<div' + (topagenumber===1 ? ' class="active" ' : '') + '>1</div>';
         temp += ((topagenumber - 1) > 3) ? '<div class="noborder">...</div>' : '';
         for(i=(totalpagenumber-4);i<=totalpagenumber;i++) {
             temp += '<div' + (topagenumber===i ? ' class="active" ' : '') + '>' + i + '</div>';
         }
         temp += '<div class="' + (topagenumber===totalpagenumber ? 'disabled' : 'next') + '">&gt;</div>';
      }
	  if(type === 'type') {
          $('div.pwrapper > div.mypagination').html(temp);
	  } else if(type === 'user') {
          $('div.pwrapper2 > div.mypagination').html(temp);
	  }
  }

  function onFromDateChangeDateTime(mydate, element) {
      var redID = $('div#left-header div.row div a.active') ? $('div#left-header div.row div a.active').attr('id') : 0;
      var timepickerID = $(element).attr('id');
      if(jQuery(element).val() !== '') {
          if(timepickerID.split('_')[0] === 'daterange' && timepickerID.split('_')[2] === '1') {
              var d = getDayBeginning(mydate);
              if(d.getDay() !== 6 && d.getDay() !== 0 && d.getDay() !== 1) {
                  $(element).val('');
                  $('#' + timepickerID.split('_')[0] + '_' + timepickerID.split('_')[1] + '_2').text('');
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
                  var temp = '' + d.getFullYear() + '-' + m + '-' + myday;
                  $('#' + timepickerID.split('_')[0] + '_' + timepickerID.split('_')[1] + '_2').text(temp);
              }
          }
          if(redID && timepickerID && (redID.split('_')[1] === timepickerID.split('_')[1])) {
              updateComposeAreaHeaderDate(redID);
          }
      }
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
        return r1[0];
      } else {
          var myDay = s[0];
          var mon = s[1].toLowerCase();
          var days = {'jan':'01', 'feb':'02', 'mar':'03', 'apr':'04', 'may':'05', 'jun':'06', 'jul':'07', 'aug':'08', 'sep':'09', 'oct':'10', 'nov':'11', 'dec':'12'};
          var myMonth = days[mon];
          h = myYear + '-' + myMonth + '-' + myDay;
          if(getDate === 0) {
            return h;
          } else if(getDate === 2) {//return Sat, 2017-08-06
            return r1[0] + ', ' + h;
          }
      }
  }

  function removeParent(element) {
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

  function setDateForComposeArea(element) {
      $('div#left-header div.row div a').removeClass('active');
      $(element).addClass('active');

      updateComposeAreaHeaderDate(element.id);

      $('div#left-drop div.droparea').removeClass('active');
      var temp=element.id.split('week')[1];
      if(temp !== '') {
          $('div#dd' + temp).addClass('active');
      }
      update1to7Backgroundcolor();
  }

  function addNewWeek(element) {
    //get last week's id's middle number
    var maxRowNumber = parseInt($('#left-header>div.row').last().find('.fromdate').attr('id').split('_')[1]) + 1;

    var temp = '<div class="row">';
        temp += '<div  class="col-xs-2 col-md-2"><a href="javascript:void(0);" class="btn btn-primary bold">Week <span class="week_number"></span>-From:</a></div>';
        temp += '<div  class="col-xs-2 col-md-2"><input type="text" name="daterange_' + maxRowNumber + '_1" id="daterange_' + maxRowNumber + '_1" class="fromdate datepicker btn btn-default form-control bold" value="" /></div>';
        temp += '<div  class="col-xs-1 col-md-1"><a href="javascript:void(0);" class="btn btn-primary bold">To:</a></div>';
        temp += '<div  class="col-xs-1 col-md-2"><a name="daterange_' + maxRowNumber + '_2" id="daterange_' + maxRowNumber + '_2" class="btn btn-default form-control bold todate" href="javascript:void(0);"></a></div>';

        temp += '<div  class="col-xs-5 col-md-5">';
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
            onChangeDateTime:onFromDateChangeDateTime
        });
    });
    enableDroppableSortable();
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
  }

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

  //when user changes fromdate, update current dragdrop area's header date:
  function updateComposeAreaHeaderDate(myid) {
      var temp = myid.split('week')[1];
      var headerid = 'header' + temp;
      var fromdate = $('#' + myid).parent().parent().find(".fromdate").val();
      fromdate = fromdate ? fromdate.trim() : '';
      var gapFromDate = parseInt(myid.split('_')[2]) - 1;
      var headerContent = (fromdate && (gapFromDate >= 0)) ? getWeekdayFromDate(fromdate, gapFromDate) : '';
      $('div#header' + temp).text(headerContent);
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
      autoFillTime();
      if(allCorrect){
          $("div#left-drop input").each(function(){
              $(this).attr("value", $(this).val());
          });
          $('div#myModal').show();
          $('div.modalContent').html($('div#left-drop').html());
          var t = '<a name="submit" class="btn btn-primary bold" onclick="submitTimesheetData();">Submit</a>';
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
      //console.log('at 613, result: ' + JSON.stringify(result));
      //save result to database
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

      console.log('at 630, finaldata: ' + JSON.stringify(finaldata));

      if(Object.getOwnPropertyNames(result).length > 0) {
          //use ajax to post to /create
          $.ajax({
              method: "POST",
              url: window.location.href,
              data: finaldata
            })
            .done(function( msg ) {
                //console.log( "at 640, data Saved: " + msg );
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
            $('div.report_create_right_section').hide();
        } else if($('div#' + temp).length > 0) {
            $('div.report_create_right_section').hide();
            $('div#' + temp).show();
        } else if($('div#' + temp).length == 0) {
            $('div.report_create_right_section').hide();
        }
    }

    function dragAllUsersToLeft() {
        $("div#ddbox2 div.dd-left").html('');
        $("div#users div.dd-right div.item").each(function(){
            var temp = $(this).attr('id').split("_");
            var category = (temp.length == 2) ? temp[0] : '';
            var id = (temp.length == 2) ? temp[1] : '';
            var temp2 = '<span class="bold left-name ' + 'cat_' + category + '_' + id + '">' + $(this).text() + '</span>';
            temp2 += '<a class="remove-left-item" href="javascript:void(0);" onclick="removeParent(this);"><i class="fa fa-trash-o font24px" aria-hidden="true" title="Remove this row"></i></a>';
            var newDiv = '<div class="left-item ' + category + '" id="u' + ((temp.length == 2) ? $(this).attr('id') : '') + '">' + temp2 + '</div>';
            $("div#ddbox2 div.dd-left").append(newDiv);
        });

        $("div#ddbox2 div.dd-left").sortable({
            stop : function (e,ui) {
                ui.item.first().removeAttr('style');
            }
        });
        $("div.dd-left").disableSelection();
    }