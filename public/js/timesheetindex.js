/*
  Author: Ming's IT Services Ltd.
  Date: 2018-09-01
  Email: mingtl2010@gmail.com
  Copyright: All rights reserved.
*/

function getTimesheetDetailHours(element) {
	if($(element).val() === 'hours') {
	$('div#master_show_result > table td > a.detaildatehours').hide();
	$('div#master_show_result > table td > span.detailhours').show();
	} else if($(element).val() === 'datehours') {
	$('div#master_show_result > table td > a.detaildatehours').show();
	$('div#master_show_result > table td > span.detailhours').hide();
	}
	//$('div#master_show_result > table td > a.detaildatehours > span.date').hide();
	//$('div#master_show_result > table td > a.detaildatehours > span.badge').show();
}