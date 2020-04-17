/*
  Author: Ming's IT Services Ltd.
  Date: 2018-09-01
  Email: mingtl2010@gmail.com
  Copyright: All rights reserved.
*/
function impersonate(element, id, name) {
  if(confirm(mytrans.Areyousureyouwanttoimpersonateasthisuser)) {
        var mytemp = window.location.href.replace(/\/create/,'');
        var mytemp2 = mytemp.replace(/\/edit\/.*$/,'');
        window.location.href = mytemp2 + '/impersonate/' + id; 
  }
}

$(document).ready(function() {
  $('form').attr('autocomplete', 'off');
  $(document).on('focus', ':input', function() {
    $(this).attr('autocomplete', 'off');
  });
  $('input, :input').attr('autocomplete', 'off');
  $('input#username').val('');
  $('input#password').val('');  
});