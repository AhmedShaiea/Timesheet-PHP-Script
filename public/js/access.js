/*
  Author: Ming's IT Services Ltd.
  Date: 2018-09-01
  Email: mingtl2010@gmail.com
  Copyright: All rights reserved.
*/
  var counter = 0, allCorrect = true, changedFieds = [];

  $( function() {
    $('select#typecategory').val('0');

    $(".draggable").draggable({
        helper: "clone"
    });

    enableDroppable();

    $('input.tabs_division_access:checkbox').change(function () {
        addToChangedFields($(this).attr('id'));
        //console.log("at 20, changedFieds: " + changedFieds);
    });

	enableDoubleClickDropAccess('div#role div.dd-right > div.item.doubleclickable', 'div#tabs0-2 div.dd-left:visible');
	enableDoubleClickDropAccess('div#user div.dd-right > div.item.doubleclickable', 'div#tabs_employee_access div.row div.dd-left:visible');
 	enableDoubleClickDropAccess('div#user2 div.dd-right > div.item.doubleclickable', 'div#tabs_exceptionemployee_access div.row div.dd-left:visible');
       
	
  } );

  function enableDroppable() {
    $("div.dd-left").droppable({
      accept: ":not(.ui-sortable-helper)",
      drop: function( event, ui ) {
        var temp = $(ui.draggable).attr('id').split("_");
        var id = (temp.length == 2) ? temp[1] : '';
        var duplicate = false;
        //no duplicate
        //get all existing role ids
        $("div#" + $(this).attr('id') + " .left-item").each(function(){
			if(typeof $(this).attr('id') !== "undefined") {
				var thistemp = $(this).attr('id').split('_');
				var mytempid = thistemp.length > 0 ? (thistemp[thistemp.length - 1]) : 0;
				if(mytempid === id){ duplicate = true;alert(mytrans.duplicate);return false; }
			}
        });
        if(duplicate) {return;}
        var tempid = $(this).attr('id').split('_');
        var myid = tempid.length > 0 ? (tempid[tempid.length - 1]) : 0;
        var mytabcategory = tempid.length > 0 ? ((tempid[1] === 'role' || tempid[1] === 'user') ? tempid[1] : "z") : "z";
        var temp2 = '<span class="left-name ' + mytabcategory + '_name_' + id + '">' + $(ui.draggable).text() + '</span>';
		//temp2 += '<a class="up-left-item" href="javascript:void(0);" onclick="upParent(this);"><i class="fa fa-arrow-up font24px" aria-hidden="true" title="Up this row"></i></a>';
		//temp2 += '<a class="down-left-item" href="javascript:void(0);" onclick="downParent(this);"><i class="fa fa-arrow-down font24px" aria-hidden="true" title="Down this row"></i></a>';	
        temp2 += '<a class="remove-left-item" href="javascript:void(0);" onclick="removeParent(this);"><i class="fa fa-trash-o font24px" aria-hidden="true" title="Remove this row"></i></a>';
        //after sortable, the id value will also change, why? how to prevent?
        var newDiv = '<div class="left-item " id="' + mytabcategory + '_' + myid + '_' + id + '">' + temp2 + '</div>';
        $(this).append(newDiv);
        addToChangedFields($(this).attr('id'));
        //console.log("at 58, changedFieds: " + changedFieds);
      }
    });
  }

  function removeParent(element) {
      addToChangedFields($(element).parent().parent().attr('id'));
      //console.log("at 65, changedFieds: " + changedFieds);
      $(element).parent().remove();
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

    function showSection(myid) {
        var temp = $('select#' + myid).find(":selected").val();
        if(temp === '0') {
            $('div.timesheet_create_right_section').hide();
        } else if($('div#' + temp).length > 0) {
            $('div.timesheet_create_right_section').hide();
            $('div#' + temp).show();
        }
    }

    function addToChangedFields(fieldid) {
        if(jQuery.inArray( fieldid, changedFieds ) == -1) {
            changedFieds.push(fieldid);
        }
    }

    function submitAccessTabs() {
        var access = {};

        $("input.tabs_division_access").each(function(){
            if(jQuery.inArray($(this).attr('id'), changedFieds ) !== -1) {
                var accessidtemp = $(this).attr('id').split('|');
                var accessid = accessidtemp.length > 0 ? parseInt(accessidtemp[0]) : 0;

                var accesscategory = accessidtemp.length > 0 ? accessidtemp[2] : '';
                //division access tab:
                //input - id:  1|1|read|10, 1|8|read|10
                //$p->id . '|' . $divisions[$i]['id'] . '|read|' . $p->read
                //accessid(in the access table, primary key) | division id (tab index) | access read/create/edit/delete/search | access read/create/edit/delete/search value   
                //get access id, read/create/edit/delete/search
                var divisionid = accessidtemp.length > 0 ? parseInt(accessidtemp[1]) : 0;
                var accesscategory_oldvalue = accessidtemp.length > 0 ? parseInt(accessidtemp[3]) : 0;
                var accesscategory_newvalue = accesscategory_oldvalue + "";
                if($(this).prop('checked')) {
                    //add this to old value
					if(divisionid !== 0 && ((accesscategory_oldvalue & divisionid) !==  divisionid) && ((accesscategory_oldvalue & divisionid) ==  0)) {
                    //if(accesscategory_oldvalue !== 0 && divisionid !== 0 && ((accesscategory_oldvalue & divisionid) !==  divisionid) && ((accesscategory_oldvalue & divisionid) ==  0)) {
                        accesscategory_newvalue = (accesscategory_oldvalue + divisionid) > 0 ? ("+" + divisionid) : '0';
                    }
                } else {
                    //remove this from old value
                    if(accesscategory_oldvalue !== 0 && divisionid !== 0 && ((accesscategory_oldvalue & divisionid) ===  divisionid) && ((accesscategory_oldvalue & divisionid) !==  0)) {
                        accesscategory_newvalue = (accesscategory_oldvalue - divisionid) > 0 ? ("-" + divisionid) : '0';
                    }
                }
                if(accessid > 0 && accesscategory !== '') {
                    if (typeof access[accessid + ''] === 'undefined') {
                        access[accessid + ''] = {};
                    }
                    access[accessid + ''][accesscategory] = ((typeof access[accessid + ''][accesscategory] === 'undefined') ? "" : access[accessid + ''][accesscategory]) + accesscategory_newvalue + "|";
                }
            }
        });

        $("div.dd-left").each(function(){
            if(jQuery.inArray($(this).attr('id'), changedFieds ) !== -1) {
                var accessidtemp = $(this).attr('id').split('_');
                var accessid = accessidtemp.length > 0 ? parseInt(accessidtemp[accessidtemp.length - 1]) : 0;
                var accesscategory = accessidtemp.length > 0 ? accessidtemp[1] : '';
                //div - id:  tabs_role_access_11
                //get access id, role
                //div - id:  tabs_user_access_1_2, tabs_user_access_2_2
                //get access id, employee/exceptionemployee
                var mydata = '';
                $(this).children().each(function(){
                    var categoryidtemp = $(this).attr('id').split('_');
                    var categoryid = categoryidtemp.length > 0 ? parseInt(categoryidtemp[categoryidtemp.length - 1]) : 0;
                    mydata += ',' + categoryid;
                });
                var mydata = mydata.substring(1);
                if(accessid > 0 && accesscategory !== '') {
                    if (typeof access[accessid + ''] === 'undefined') {
                        access[accessid + ''] = {};
                    }
                    access[accessid + ''][accesscategory] = mydata;
                }
            }
        });
        //console.log("at 158, access: " + obj2string(access));
        if(Object.getOwnPropertyNames(access).length > 0) {
            $('input#mydata').val(obj2string(access));
            $('form#accessupdate').submit();
      } else {
          alert(mytrans.Youdidnotchangeanything);
      }
    }

    function submitCreateAccessTabs() {
        var access = {};
        $("input.tabs_division_access").each(function(){
            var accessidtemp = $(this).attr('id').split('|');
            var accesscategory = accessidtemp.length > 0 ? accessidtemp[1] : '';
            //division access tab:
            //input - id:  1|read, 8|read
            //$p->id . '|' . $divisions[$i]['id'] . '|read|' . $p->read
            //accessid(in the access table, primary key) | division id (tab index) | access read/create/edit/delete/search | access read/create/edit/delete/search value
            //get access id, read/create/edit/delete/search
            var divisionid = accessidtemp.length > 0 ? parseInt(accessidtemp[0]) : 0;
            var accesscategory_newvalue = "";
            if($(this).prop('checked')) {
                //add this to old value
                accesscategory_newvalue = divisionid > 0 ? divisionid : '';
            }
            access[accesscategory] = ((typeof access[accesscategory] === 'undefined') ? "" : access[accesscategory]) + (accesscategory_newvalue === '' ? '' : (accesscategory_newvalue + "|"));
        });

        $("div.dd-left").each(function(){
            var accessidtemp = $(this).attr('id').split('_');
            var accesscategory = accessidtemp.length > 0 ? accessidtemp[1] : '';
            //div - id:  role_1_100
            //get access id, role
            //div - id:  user_1_5, user_2_5
            //get access id, employee/exceptionemployee
            var mydata = '';
            $(this).children().each(function(){
                var categoryidtemp = $(this).attr('id').split('_');
                var categoryid = categoryidtemp.length > 0 ? parseInt(categoryidtemp[categoryidtemp.length - 1]) : 0;
                mydata += ',' + categoryid;
            });
            var mydata = mydata.substring(1);
            access[accesscategory] = mydata;
        });
        access['target'] = $('select#target').find(":selected").val();
        if($('select#target').find(":selected").val() === '0') {
            alert(mytrans.Youmustchooseatarget);return;
        }
        //console.log("at 206, access: " + obj2string(access));
        if(Object.getOwnPropertyNames(access).length > 0) {
            $('input#mydata').val(obj2string(access));
            $('form#accessupdate').submit();
      } else {
          alert(mytrans.Youdidnotenteranything);
      }
    }
	
	function enableDoubleClickDropAccess(sourceElement, targetElement) {
		var touchtime = 0;
		$(sourceElement).on("click", function() {
			if (touchtime == 0) {
				// set first click
				touchtime = new Date().getTime();
			} else {
				if (((new Date().getTime()) - touchtime) < 800) {
					var temp = $(this).attr('id').split("_");
					var id = (temp.length == 2) ? temp[1] : '';
					var duplicate = false;
					// no duplicate
					//get all existing role ids
					$("div#" + $(targetElement).attr('id') + " .left-item").each(function(){
						if(typeof $(this).attr('id') !== "undefined") {
							var thistemp = $(this).attr('id').split('_');
							var mytempid = thistemp.length > 0 ? parseInt(thistemp[thistemp.length - 1]) : 0;
							if(mytempid === parseInt(id)){ duplicate = true;alert(mytrans.duplicate);return false; }
						}
					});
					if(duplicate) {return;}
					var tempid = $(targetElement).attr('id').split('_');
					var myid = tempid.length > 0 ? (tempid[tempid.length - 1]) : 0;
					var mytabcategory = tempid.length > 0 ? ((tempid[1] === 'role' || tempid[1] === 'user') ? tempid[1] : "z") : "z";
					var temp2 = '<span class="left-name ' + mytabcategory + '_name_' + id + '">' + $(this).text() + '</span>';
					//temp2 += '<a class="up-left-item" href="javascript:void(0);" onclick="upParent(this);"><i class="fa fa-arrow-up font24px" aria-hidden="true" title="Up this row"></i></a>';
					//temp2 += '<a class="down-left-item" href="javascript:void(0);" onclick="downParent(this);"><i class="fa fa-arrow-down font24px" aria-hidden="true" title="Down this row"></i></a>';	
					temp2 += '<a class="remove-left-item" href="javascript:void(0);" onclick="removeParent(this);"><i class="fa fa-trash-o font24px" aria-hidden="true" title="Remove this row"></i></a>';
					var newDiv = '<div class="left-item " id="' + mytabcategory + '_' + myid + '_' + id + '">' + temp2 + '</div>';
					$(targetElement).append(newDiv);
					addToChangedFields($(targetElement).attr('id'));
				} else {
					// not a double click so set as a new first click
					touchtime = new Date().getTime();
				}
			}
		});
	}