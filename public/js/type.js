/*
  Author: Ming's IT Services Ltd.
  Date: 2018-09-01
  Email: mingtl2010@gmail.com
  Copyright: All rights reserved.
*/
  var counter = 0, allCorrect = true, changedFieds = [];
  
  $(function() {
    $('select#typecategory').val('0');

    $(".draggable").draggable({
        helper: "clone"
    });

    enableDroppable();

    $('input.tabs_division_type:checkbox').change(function () {
        addToChangedFields($(this).attr('id'));
        //console.log("at 20, changedFieds: " + changedFieds);
    });

  });

  function enableDroppable() {
    $("div.dd-left").droppable({
      accept: ":not(.ui-sortable-helper)",
      drop: function( event, ui ) {
        var temp = $(ui.draggable).attr('id').split("_");
        var id = (temp.length == 2) ? temp[1] : '';
        var duplicate = false;
        // no duplicate
        //get all existing role ids
        $("div#" + $(this).attr('id') + " .left-item").each(function(){
            var thistemp = $(this).attr('id').split('_');
            var mytempid = thistemp.length > 0 ? (thistemp[thistemp.length - 1]) : 0;
            if(mytempid === id){ duplicate = true;alert(mytrans.duplicate);return false; }
        });
        if(duplicate) {return;}
        var tempid = $(this).attr('id').split('_');
        var myid = tempid.length > 0 ? (tempid[tempid.length - 1]) : 0;
        var mytabcategory = tempid.length > 0 ? ((tempid[1] === 'role' || tempid[1] === 'user') ? tempid[1] : "z") : "z";
        var temp2 = '<span class="left-name ' + mytabcategory + '_name_' + id + '">' + $(ui.draggable).text() + '</span>';
        temp2 += '<a class="remove-left-item" href="javascript:void(0);" onclick="removeParent(this);"><i class="fa fa-trash-o font24px" aria-hidden="true" title="Remove this row"></i></a>';
        //after sortable, the id value will also change, why? how to prevent?
        var newDiv = '<div class="left-item " id="' + mytabcategory + '_' + myid + '_' + id + '">' + temp2 + '</div>';
        $(this).append(newDiv);
        addToChangedFields($(this).attr('id'));
        //console.log("at 49, changedFieds: " + changedFieds);
      }
    });
  }

  function removeParent(element) {
      addToChangedFields($(element).parent().parent().attr('id'));
      //console.log("at 56, changedFieds: " + changedFieds);
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

    function addToChangedFields(fieldid) {
        if(jQuery.inArray( fieldid, changedFieds ) == -1) {
            changedFieds.push(fieldid);
        }
    }

    function submitCreateTypeTabs() {
        if ($('select#dropdownlist_typecategory').find(":selected").val() === '0' || $('input#name').val().trim() === '') {
            alert(mytrans.Pleaseentertypecategoryandorname);return;
        }
        var type = {};

        $("input.tabs_division_type").each(function(){
            var typeidtemp = $(this).attr('id').split('|');

            var typecategory = typeidtemp.length > 0 ? typeidtemp[1] : '';
            //division access tab:
            //input - id:  1|read, 8|read
            //$p->id . '|' . $divisions[$i]['id'] . '|read|' . $p->read
            //accessid(in the type table, primary key) | division id (tab index) | type read/create/edit/delete/search | type read/create/edit/delete/search value
            //get type id, read/create/edit/delete/search
            var divisionid = typeidtemp.length > 0 ? parseInt(typeidtemp[0]) : 0;
            var typecategory_newvalue = "";
            if($(this).prop('checked')) {
                typecategory_newvalue = divisionid > 0 ? divisionid : '';
            }
            type[typecategory] = ((typeof type[typecategory] === 'undefined') ? "" : type[typecategory]) + (typecategory_newvalue === '' ? '' : (typecategory_newvalue + "|"));
        });

        $("div.dd-left").each(function(){
            var typeidtemp = $(this).attr('id').split('_');
            var typecategory = typeidtemp.length > 0 ? typeidtemp[1] : '';
            //get type id, employee/exceptionemployee
            var mydata = '';
            $(this).children().each(function(){
                var categoryidtemp = $(this).attr('id').split('_');
                var categoryid = categoryidtemp.length > 0 ? parseInt(categoryidtemp[categoryidtemp.length - 1]) : 0;
                mydata += ',' + categoryid;
            });
            var mydata = mydata.substring(1);
            if(typecategory !== '') {
                type[typecategory] = mydata;
            }
        });

        var result = {'type' : type};
        result['typecategory'] = $('select#dropdownlist_typecategory').find(":selected").val();
        result['name'] = $('input#name').val();
        result['desc'] = $('textarea#desc').val();
        result['billable'] = $('select#billable').find(":selected").val();
        //console.log("at 121, result: " + obj2string(result));
        if(Object.getOwnPropertyNames(type).length > 0) {
            $('input#mydata').val(obj2string(result));
            $('form#typecreate').submit();
        } else {
            alert(mytrans.Youdidnotenteranything);
        }
    }

    function submitEditTypeTabs() {
        if ($('select#dropdownlist_typecategory').find(":selected").val() === '0' || $('input#name').val().trim() === '') {
            alert(mytrans.Pleaseentertypecategoryandorname);return;
        }
        var type = {};
        $("input.tabs_division_type").each(function(){
            if(jQuery.inArray($(this).attr('id'), changedFieds ) !== -1) {
                var typeidtemp = $(this).attr('id').split('|');
                var typecategory = typeidtemp.length > 0 ? typeidtemp[1] : '';
                //division access tab:
                //input - id:  1|read|10, 8|read|10
                //$divisions[$i]['id'] . '|read|' . $p->read
                //division id (tab index) | type read/edit/delete/search | type read/edit/delete/search value   
                //get type id, read/edit/delete/search
                var divisionid = typeidtemp.length > 0 ? parseInt(typeidtemp[0]) : 0;
                var typecategory_oldvalue = typeidtemp.length > 0 ? parseInt(typeidtemp[2]) : 0;
                var typecategory_newvalue = typecategory_oldvalue + "";
                if($(this).prop('checked')) {
                    //add this to old value
					if(divisionid !== 0 && ((typecategory_oldvalue & divisionid) !==  divisionid) && ((typecategory_oldvalue & divisionid) ==  0)) {
                    //if(typecategory_oldvalue !== 0 && divisionid !== 0 && ((typecategory_oldvalue & divisionid) !==  divisionid) && ((typecategory_oldvalue & divisionid) ==  0)) {
                        typecategory_newvalue = (typecategory_oldvalue + divisionid) > 0 ? ("+" + divisionid) : '0';
                    }
                } else {
                    //remove this from old value
                    if(typecategory_oldvalue !== 0 && divisionid !== 0 && ((typecategory_oldvalue & divisionid) ===  divisionid) && ((typecategory_oldvalue & divisionid) !==  0)) {
                        typecategory_newvalue = (typecategory_oldvalue - divisionid) > 0 ? ("-" + divisionid) : '0';
                    }
                }
                if(typecategory !== '') {
                    type[typecategory] = ((typeof type[typecategory] === 'undefined') ? "" : type[typecategory]) + typecategory_newvalue + "|";
                }
            }
        });

        $("div.dd-left").each(function(){
            if(jQuery.inArray($(this).attr('id'), changedFieds ) !== -1) {
                var typeidtemp = $(this).attr('id').split('_');
                var typecategory = typeidtemp.length > 0 ? typeidtemp[1] : '';
                //get type id, employee/exceptionemployee
                var mydata = '';
                $(this).children().each(function(){
                    var categoryidtemp = $(this).attr('id').split('_');
                    var categoryid = categoryidtemp.length > 0 ? parseInt(categoryidtemp[categoryidtemp.length - 1]) : 0;
                    mydata += ',' + categoryid;
                });
                var mydata = mydata.substring(1);
                if(typecategory !== '') {
                    type[typecategory] = mydata;
                }
            }
        });

        var result = {'type' : type};
        result['typecategory'] = $('select#dropdownlist_typecategory').find(":selected").val();
        result['name'] = $('input#name').val();
        result['desc'] = $('textarea#desc').val();
        result['billable'] = $('select#billable').find(":selected").val();
        //console.log("at 188, type: " + obj2string(type));
        if(Object.getOwnPropertyNames(type).length > 0) {
            $('input#mydata').val(obj2string(result));
            $('form#typeupdate').submit();
        } else {
          alert(mytrans.Youdidnotchangeanything);
        }
    }