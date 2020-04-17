/*
  Author: Ming's IT Services Ltd.
  Date: 2018-09-01
  Email: mingtl2010@gmail.com
  Copyright: All rights reserved.
*/
var master_totalpagenumber;

	if(mytemp === "en") {
		mytrans.Myweek = {"Mon":"Mon","Tue":"Tue","Wed":"Wed","Thu":"Thu","Fri":"Fri","Sat":"Sat","Sun":"Sun"};
		mytrans.Week = "Week";
		mytrans.Submit = "Submit";
		mytrans.Cancel = "Cancel";
		mytrans.Submitting = "Submitting";
		mytrans.From = "From";
		mytrans.To = "To";
		mytrans.from = "from";
		mytrans.to = "to";
		mytrans.Up = "Up";
		mytrans.Down = "Down";
		mytrans.Remove = "Remove";
		mytrans.TimesheetsavedPostedtoallwebhooks = "Timesheet saved. Posted to all webhooks.";
		mytrans.TimesheetsavedPostedtosomeofthewebhooks = "Timesheet saved. Posted to some of the webhooks.";
		mytrans.TimesheetsavedNowebhooksavailable = "Timesheet saved. No webhooks available.";
		mytrans.YoualreadysavedsometimeperioddataintodatabaseDuplicatedPleasecreateagain = "You already saved some time period data into database. Duplicated. Please create again.";
		mytrans.NotallthedatacanbesavedintodatabasePleasecreateagain = "Not all the data can be saved into database. Please create again.";
		mytrans.Thisrecordisnotyours = "This record is not yours.";
		mytrans.ErrorPleasecreateagain = "Error. Please create again.";
		mytrans.ErrorPleasetryagain = "Error. Please try again.";
		mytrans.Requestfailed = "Request failed";
		mytrans.Pleaserefreshthepageandtryagain = "Please refresh the page and try again.";
		mytrans.Pleaseenterdatetimeinfo = "Please enter date time info.";
		mytrans.PleaseenterMondaySundayorSaturday = "Please enter Monday, Sunday or Saturday.";
		mytrans.Timeisnotcontinuousatdate = "Time is not continuous at date";
		mytrans.Pleasefillallthedatefirst = "Please fill all the date first!";
		mytrans.Areyousureyouwanttodeletethis = "Are you sure you want to delete this ";
		mytrans.DeleteTarget = {"timesheet":"timesheet","typecategory":"type category","type":"type","user":"user","role":"role","division":"division"};
		mytrans.Areyousureyouwanttoimpersonateasthisuser = "Are you sure you want to impersonate as this user?";
		mytrans.Chooseoneuser = "Choose one user";
		mytrans.Chooseonetype = "Choose one type";
		mytrans.Onlyshowoneusersworkhourschart = "Only show one user's work/hours chart";
		mytrans.Onlyshowonetypesuserhourschart = "Only show one type's user/hours chart";
		mytrans.showchart = "show chart";
		mytrans.removeallchart = "remove all chart";
		mytrans.Fromdate = "From date";
		mytrans.Todate = "To date";
		mytrans.nodata = "no data";
		mytrans.Hours = "Hours";
		mytrans.UserName = "User Name";
	    mytrans.Name = "Name";
	    mytrans.Desc = "Desc";
	    mytrans.Approved = "Approved";
	    mytrans.Approvedby = "Approved by";
		mytrans.ApprovedTime = "Approved Time";
		mytrans.ReviewNotes = "Review Notes";
		mytrans.Status = "Status";
		mytrans.Created = "Created";
		mytrans.ID = "ID";
		mytrans.Review = "Review";
		mytrans.Youdidnotchangeanything = "You did not change anything.";
		mytrans.Youdidnotenteranything = "You did not enter anything.";
		mytrans.Youmustchooseatarget = "You must choose a target.";
		mytrans.duplicate = "duplicate";
		mytrans.Pleasedraganddroptypesfromtherightside = "Please drag and drop types from the right side.";
		mytrans.Pleasedraganddropusersfromtherightside = "Please drag and drop users from the right side.";
		mytrans.Pleaseentertypecategoryandorname = "Please enter type category and/or name.";
	} else if(mytemp === "bp") {
		mytrans.Myweek = {"Mon":"Seg","Tue":"Ter","Wed":"Qua","Thu":"Qui","Fri":"Sex","Sat":"Sab","Sun":"Dom"};
		mytrans.Week = "Semana";
		mytrans.Submit = "Enviar";
		mytrans.Cancel = "Cancelar";
		mytrans.Submitting = "Enviando";
		mytrans.From = "de";
		mytrans.To = "para";
		mytrans.from = "de";
		mytrans.to = "para";
		mytrans.Up = "Acima";
		mytrans.Down = "Baixa";
		mytrans.Remove = "Retirar";
		mytrans.TimesheetsavedPostedtoallwebhooks = "Quadro de horários salvo. Publicado em todos os webhooks.";
		mytrans.TimesheetsavedPostedtosomeofthewebhooks = "Quadro de horários salvo. Publicado em alguns dos webhooks.";
		mytrans.TimesheetsavedNowebhooksavailable = "Quadro de horários salvo. Não há webhooks disponíveis.";
		mytrans.YoualreadysavedsometimeperioddataintodatabaseDuplicatedPleasecreateagain = "Você já salvou alguns dados do período no banco de dados. Duplicado. Por favor, crie novamente.";
		mytrans.NotallthedatacanbesavedintodatabasePleasecreateagain = "Nem todos os dados podem ser salvos no banco de dados. Por favor, crie novamente.";
		mytrans.Thisrecordisnotyours = "Este registro não é seu.";
		mytrans.ErrorPleasecreateagain = "Erro. Por favor, crie novamente.";
		mytrans.ErrorPleasetryagain = "Erro. Por favor, tente novamente.";
		mytrans.Requestfailed = "Falha no pedido";
		mytrans.Pleaserefreshthepageandtryagain = "Atualize a página e tente novamente.";
		mytrans.Pleaseenterdatetimeinfo = "Digite as informações de data e hora.";
		mytrans.PleaseenterMondaySundayorSaturday = "Por favor, insira segunda, domingo ou sábado.";
		mytrans.Timeisnotcontinuousatdate = "O tempo não é contínuo na data";
		mytrans.Pleasefillallthedatefirst = "Por favor preencha toda a data primeiro!";
		mytrans.Areyousureyouwanttodeletethis = "Tem certeza de que deseja excluir esta ";
		mytrans.DeleteTarget = {"timesheet":"planilha de horário","typecategory":"categorias","type":"tipo","user":"usuário","role":"função","division":"divisão"};
		mytrans.Areyousureyouwanttoimpersonateasthisuser = "Tem certeza de que deseja se passar por esse usuário?";
		mytrans.Chooseoneuser = "Escolha um usuário";
		mytrans.Chooseonetype = "Olha um tipo";
		mytrans.Onlyshowoneusersworkhourschart = "Mostrar apenas o gráfico de horas/trabalho de um usuário";
		mytrans.Onlyshowonetypesuserhourschart = "Mostrar apenas o gráfico de horas/usuário de um tipo";
		mytrans.showchart = "mostrar gráfico";
		mytrans.removeallchart = "remover todo o gráfico";
	    mytrans.Fromdate = "Do dia";
		mytrans.Todate = "Até dia";
		mytrans.nodata = "sem dados";
		mytrans.Hours = "Horas";		
		mytrans.UserName = "Nome de Usuário";
	    mytrans.Name = "Nome";
	    mytrans.Desc = "Descrição";
	    mytrans.Approved = "Aprovado";
	    mytrans.Approvedby = "Aprovado por";
		mytrans.ApprovedTime = "Data Aprovação";
		mytrans.ReviewNotes = "Notas de Revisão";
		mytrans.Status = "Status";
		mytrans.Created = "Criado";
		mytrans.ID = "ID";
		mytrans.Review = "Revisão";
		mytrans.Youdidnotchangeanything = "Você não mudou nada.";
		mytrans.Youdidnotenteranything = "Você não inseriu nada.";
		mytrans.Youmustchooseatarget = "Você deve escolher um alvo.";
		mytrans.duplicate = "duplicada";
		mytrans.Pleasedraganddroptypesfromtherightside = "Arraste e solte tipos do lado direito.";
		mytrans.Pleasedraganddropusersfromtherightside = "Arraste e solte os usuários do lado direito.";
		mytrans.Pleaseentertypecategoryandorname = "Digite a categoria e / ou nome do tipo.";
	} else if(mytemp === "zhs") {
		mytrans.Myweek = {"Mon":"周一","Tue":"周二","Wed":"周三","Thu":"周四","Fri":"周五","Sat":"周六","Sun":"周日"};
		mytrans.Week = "周";
		mytrans.Submit = "提交";
		mytrans.Cancel = "取消";
		mytrans.Submitting = "正在提交";
		mytrans.From = "从";
		mytrans.To = "到";
		mytrans.from = "从";
		mytrans.to = "到";
		mytrans.Up = "上移";
		mytrans.Down = "下移";
		mytrans.Remove = "删除";
		mytrans.TimesheetsavedPostedtoallwebhooks = "时间表保存。自动发送了数据到所有的另外的网站。";
		mytrans.TimesheetsavedPostedtosomeofthewebhooks = "时间表已保存。自动发送了数据到部分的另外的网站。";
		mytrans.TimesheetsavedNowebhooksavailable = "时间表已保存。没有另外的网站用来发送数据。";
	    mytrans.YoualreadysavedsometimeperioddataintodatabaseDuplicatedPleasecreateagain = "你以前已经存了某些时间段的数据。数据重复。请重新创建。";
		mytrans.NotallthedatacanbesavedintodatabasePleasecreateagain = "不是所有数据能存入数据库。请重新创建。";
		mytrans.Thisrecordisnotyours = "这记录不是您的。";
		mytrans.ErrorPleasecreateagain = "错误。请重新创建。";
		mytrans.ErrorPleasetryagain = "错误。请重试。";
		mytrans.Requestfailed = "请求失败";
		mytrans.Pleaserefreshthepageandtryagain = "请刷新网页重试。";
		mytrans.Pleaseenterdatetimeinfo = "请输入日期时间信息。";
		mytrans.PleaseenterMondaySundayorSaturday = "请输入周一，周日，或者周六。";
		mytrans.Timeisnotcontinuousatdate = "在这个日期时间不连续";
		mytrans.Pleasefillallthedatefirst = "请先填写所有日期!";
		mytrans.Areyousureyouwanttodeletethis = "你确定你想删除这个";
		mytrans.DeleteTarget = {"timesheet":"时间表","typecategory":"类别","type":"具体类型","user":"用户","role":"角色","division":"部门"};
		mytrans.Areyousureyouwanttoimpersonateasthisuser = "你确定你想用这个用户登录？";
		mytrans.Chooseoneuser = "选一个用户";
		mytrans.Chooseonetype = "选择一个具体类型";
		mytrans.Onlyshowoneusersworkhourschart = "只显示一个用户的图表";
		mytrans.Onlyshowonetypesuserhourschart = "只显示一个具体类型的图表";
		mytrans.showchart = "显示图表";
		mytrans.removeallchart = "删除所有图表";
		mytrans.Fromdate = "起始日期";
		mytrans.Todate = "终止日期";
		mytrans.nodata = "没有数据";
		mytrans.Hours = "小时";
		mytrans.UserName = "用户名";
	    mytrans.Name = "名字";
	    mytrans.Desc = "描述";
	    mytrans.Approved = "通过";
	    mytrans.Approvedby = "审阅人";
		mytrans.ApprovedTime = "审阅时间";
		mytrans.ReviewNotes = "审阅意见";
		mytrans.Status = "状态";
		mytrans.Created = "建立";
		mytrans.ID = "ID";
		mytrans.Review = "审阅";
		mytrans.Youdidnotchangeanything = "您什么都没改。";
		mytrans.Youdidnotenteranything = "您什么都没输入。";
		mytrans.Youmustchooseatarget = "您必须选择一个。";
		mytrans.duplicate = "重复";
		mytrans.Pleasedraganddroptypesfromtherightside = "请从右侧拖放具体类型到左侧。";
		mytrans.Pleasedraganddropusersfromtherightside = "请从右侧拖放用户到左侧。";
		mytrans.Pleaseentertypecategoryandorname = "请输入类别和/或名称。";
	} else if(mytemp === "zht") {
		mytrans.Myweek = {"Mon":"週一","Tue":"週二","Wed":"週三","Thu":"週四","Fri":"週五","Sat":"週六","Sun":"週日"};
		mytrans.Week = "週";
		mytrans.Submit = "提交";
		mytrans.Cancel = "取消";
		mytrans.Submitting = "正在提交";
		mytrans.From = "從";
		mytrans.To = "到";
		mytrans.from = "從";
		mytrans.to = "到";
		mytrans.Up = "上移";
		mytrans.Down = "下移";
		mytrans.Remove = "刪除";
		mytrans.TimesheetsavedPostedtoallwebhooks = "時間表保存。自動發送了數據到所有的另外的網站。";
		mytrans.TimesheetsavedPostedtosomeofthewebhooks = "時間表已保存。自動發送了數據到部分的另外的網站。";
		mytrans.TimesheetsavedNowebhooksavailable = "時間表已保存。沒有另外的網站用來發送數據。";
		mytrans.YoualreadysavedsometimeperioddataintodatabaseDuplicatedPleasecreateagain = "你以前已經存了某些時間段的數據。數據重複。請重新創建。";
		mytrans.NotallthedatacanbesavedintodatabasePleasecreateagain = "不是所有數據能存入數據庫。請重新創建。";
		mytrans.Thisrecordisnotyours = "這記錄不是您的。";
		mytrans.ErrorPleasecreateagain = "錯誤。請重新創建。";
		mytrans.ErrorPleasetryagain = "錯誤。請重試。";
		mytrans.Requestfailed = "請求失敗";
		mytrans.Pleaserefreshthepageandtryagain = "請刷新網頁重試。";
		mytrans.Pleaseenterdatetimeinfo = "請輸入日期時間信息。";
		mytrans.PleaseenterMondaySundayorSaturday = "請輸入週一，週日，或者週六。";
		mytrans.Timeisnotcontinuousatdate = "在這個日期時間不連續";
		mytrans.Pleasefillallthedatefirst = "請先填寫所有日期!";
		mytrans.Areyousureyouwanttodeletethis = "你確定你想刪除這個";
		mytrans.DeleteTarget = {"timesheet":"時間表","typecategory":"類別","type":"具體類型","user":"用戶","role":"角色","division":"部門"};
		mytrans.Areyousureyouwanttoimpersonateasthisuser = "你確定你想用這個用戶登錄？";
		mytrans.Chooseoneuser = "選一個用戶";
		mytrans.Chooseonetype = "選擇一個具體類型";
		mytrans.Onlyshowoneusersworkhourschart = "只顯示一個用戶的圖表";
		mytrans.Onlyshowonetypesuserhourschart = "只顯示一個具體類型的圖表";
		mytrans.showchart = "顯示圖表";
		mytrans.removeallchart = "刪除所有圖表";
		mytrans.Fromdate = "起始日期";
		mytrans.Todate = "終止日期";
		mytrans.nodata = "沒有數據";
		mytrans.Hours = "小時";
		mytrans.UserName = "用戶名";
	    mytrans.Name = "名字";
	    mytrans.Desc = "描述";
	    mytrans.Approved = "通過";
	    mytrans.Approvedby = "審閱人";
		mytrans.ApprovedTime = "審閱時間";
		mytrans.ReviewNotes = "審閱意見";
		mytrans.Status = "狀態";
		mytrans.Created = "建立";
		mytrans.ID = "ID";
		mytrans.Review = "審閱";
		mytrans.Youdidnotchangeanything = "您什麼都沒改。";
		mytrans.Youdidnotenteranything = "您什麼都沒輸入。";
		mytrans.Youmustchooseatarget = "您必須選擇一個。";
		mytrans.duplicate = "重複";
		mytrans.Pleasedraganddroptypesfromtherightside = "請從右側拖放具體類型到左側。";
		mytrans.Pleasedraganddropusersfromtherightside = "請從右側拖放用戶到左側。";
		mytrans.Pleaseentertypecategoryandorname = "請輸入類別和/或名稱。";
	}
	
$(document).ready(function(){
    $('input.datetimepicker').each(function(){
        $(this).datetimepicker({
            format:'Y-m-d H:i'
        });
    });
    var myurl = window.location.href, result;
	if(myurl.indexOf("://") !== -1) {
		myurl = myurl.split("://", 2)[1];
	}
	
	if(myurl.indexOf("/index.php") !== -1) {
        result = myurl.split("/index.php").slice(-1)[0];
	} else {
		result = myurl.split("/", 2)[1];
	}
    result = result.replace("/", "");

    if(result.indexOf('/') !== -1) {
        result = result.split("/")[0];
    } else if(result.indexOf('?') !== -1) {
        result = result.split("?")[0];
    }

    $('div#navbarCollapse li').removeClass('active');
    $('div#homeicon').removeClass('active');

    if(result === '') {
        $('div#homeicon').addClass('active');
    } else {
        if(result !== 'access' && result !== 'user' && result !== 'role' && result !== 'division'){
            $('li#' + result).addClass('active');
        } else {
            $('li#setting').addClass('active');
            $('li#' + result).addClass('active');
        }
    }

    if($('div.master_pwrapper').length > 0) {
        $('div.master_pwrapper').on( 'click', 'div.master_mypagination > ul.pagination > li:not(.noborder):not(.disabled)', function () {
            var topagenumber = isNaN(parseInt($(this).text())) ? 0 : parseInt($(this).text());
            var currentpage;
            if($(this).hasClass('prev')){
                currentpage = isNaN(parseInt($('div.master_pwrapper > div.master_mypagination > ul.pagination > li.active > a').first().text())) ? 0 : parseInt($('div.master_pwrapper > div.master_mypagination > ul.pagination > li.active > a').first().text());
                topagenumber = Math.max(1, (currentpage-1));
            } else if ($(this).hasClass('next')){ 
                currentpage = isNaN(parseInt($('div.master_pwrapper > div.master_mypagination > ul.pagination > li.active > a').first().text())) ? 0 : parseInt($('div.master_pwrapper > div.master_mypagination > ul.pagination > li.active > a').first().text());
                topagenumber = Math.min(master_totalpagenumber, (currentpage+1));
            }
            master_showResultOnReloadedPage('', topagenumber);
        });
    }

    if($('div#master_show_result').length > 0) {
        $('div#master_show_result').on( 'click', 'table th i', function () {
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
            master_showResultOnReloadedPage('', 1, mysortby, myorder);
        });
    }
	
	$("ul.navbar-right > li.dropdown > ul.dropdown-menu > li > select#master_lang").click(function(event){
		// Avoid following the href location when clicking
        event.preventDefault(); 
        // Avoid having the menu to close when clicking
        event.stopPropagation(); 
		$('ul.navbar-right > li.dropdown > ul.dropdown-menu').show();
    });
	
	$("ul.navbar-right > li.dropdown > ul.dropdown-menu > li > select#master_lang").keyup(function(event){
		// Avoid following the href location when clicking
        event.preventDefault(); 
        // Avoid having the menu to close when clicking
        event.stopPropagation(); 
		$('ul.navbar-right > li.dropdown > ul.dropdown-menu').show();
    });

});

// a - amount per page; o - order (asc, desc); p - page number; s - sortby
function master_createPagination(totalpagenumber, topagenumber) {
      if(totalpagenumber !== parseInt(totalpagenumber, 10) || topagenumber !== parseInt(topagenumber, 10) || totalpagenumber <= 1) {$('div.master_pwrapper > div.master_mypagination').html('');return;}
      var temp = '<ul class="pagination">',i;
      if(topagenumber > totalpagenumber) {topagenumber = totalpagenumber;}
      if(totalpagenumber < 7) {
         temp += '<li class="' + (topagenumber===1 ? 'disabled' : 'prev') + '"><a>&lt;</a></li>';
         for(i=1;i<=totalpagenumber;i++) {
             temp += '<li' + (topagenumber===i ? ' class="active" ' : '') + '><a>' + i + '</a></li>';
         }
         temp += '<li class="' + (topagenumber===totalpagenumber ? 'disabled' : 'next') + '"><a>&gt;</a></li>';
      } else if(topagenumber < 4) {
         temp += '<li class="' + (topagenumber===1 ? 'disabled' : 'prev') + '"><a>&lt;</a></li>';
         for(i=1;i<=5;i++) {
             temp += '<li' + (topagenumber===i ? ' class="active" ' : '') + '><a>' + i + '</a></li>';
         }
         temp += ((totalpagenumber - topagenumber) > 3) ? '<li class="noborder"><a>...</a></li>' : '';
         temp += '<li' + (topagenumber===totalpagenumber ? ' class="active" ' : '') + '><a>' + totalpagenumber + '</a></li><li class="' + (topagenumber===totalpagenumber ? 'disabled' : 'next') + '"><a>&gt;</a></li>';
      } else if(topagenumber < (totalpagenumber - 2)) {
         temp += '<li class="' + (topagenumber===1 ? 'disabled' : 'prev') + '"><a>&lt;</a></li><li' + (topagenumber===1 ? ' class="active" ' : '') + '><a>1</a></li>';
         temp += (topagenumber > 4) ? '<li class="noborder"><a>...</a></li>' : '';
         for(i=(topagenumber-2);i<=((topagenumber+2)>(totalpagenumber-1) ? (totalpagenumber-1) : (topagenumber+2));i++) {
             temp += '<li' + (topagenumber===i ? ' class="active" ' : '') + '><a>' + i + '</a></li>';
         }
         temp += ((totalpagenumber - topagenumber) > 3) ? '<li class="noborder"><a>...</a></li>' : '';
         temp += '<li' + (topagenumber===totalpagenumber ? ' class="active" ' : '') + '><a>' + totalpagenumber + '</a></li><li class="' + (topagenumber===totalpagenumber ? 'disabled' : 'next') + '"><a>&gt;</a></li>';
      } else if(topagenumber >= (totalpagenumber - 2)) {
         temp += '<li class="' + (topagenumber===1 ? 'disabled' : 'prev') + '"><a>&lt;</a></li>';
         temp += '<li' + (topagenumber===1 ? ' class="active" ' : '') + '><a>1</a></li>';
         temp += ((topagenumber - 1) > 3) ? '<li class="noborder"><a>...</a></li>' : '';
         for(i=(totalpagenumber-4);i<=totalpagenumber;i++) {
             temp += '<li' + (topagenumber===i ? ' class="active" ' : '') + '><a>' + i + '</a></li>';
         }
         temp += '<li class="' + (topagenumber===totalpagenumber ? 'disabled' : 'next') + '"><a>&gt;</a></li>';
      }
	  temp += '</ul>';

      if($('div.master_pwrapper > div.master_mypagination').length > 0) {
          $('div.master_pwrapper > div.master_mypagination').html(temp);
      }
}

// a - amount per page; o - order (asc, desc); p - page number; s - sortby
function master_createPagination2(totalpagenumber, topagenumber) {
      if(totalpagenumber !== parseInt(totalpagenumber, 10) || topagenumber !== parseInt(topagenumber, 10) || totalpagenumber <= 1) {$('div.master_pwrapper > div.master_mypagination').html('');return;}
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

      if($('div.master_pwrapper > div.master_mypagination').length > 0) {
          $('div.master_pwrapper > div.master_mypagination').html(temp);
      }
}

function master_showResultOnReloadedPage(type, pagenumber, mysortby, myorder) {
      var mydata = {}, temp1 = '';
      mydata['p'] = pagenumber ? pagenumber : 1;
      mydata['s'] = (mysortby === undefined) ? '' : mysortby;
      mydata['o'] = (myorder === undefined) ? '' : myorder;
      mydata['a'] = $('select#master_amountperpage').find(":selected").val();

      if (mysortby === undefined && myorder === undefined) {
          $('div#master_show_result table th i').each(function () {
                if($(this).hasClass('fa-sort-asc')){
                    mydata['o'] = 'asc';
                    mydata['s'] = $(this).parent().attr('class');
                } else if($(this).hasClass('fa-sort-desc')){
                    mydata['o'] = 'desc';
                    mydata['s'] = $(this).parent().attr('class');
                }
          });
      }
      var str = $.param(mydata);
      //console.log('at 181, str: ' + str);
      var mytemp = window.location.href.split('?');
      window.location.href = mytemp[0] + '?' + str;
}

function master_switchLang() {
	var mylang = $('select#master_lang').find(":selected").val(), myurl = window.location.href, result, myurl2, myheader2 = "", result2;
	if(myurl.indexOf("://") !== -1) {
		myurl = myurl.split("://", 2)[1];
	}
	
	if(myurl.indexOf("/index.php") !== -1) {
        result = myurl.split("/index.php").slice(-1)[0];
	} else {
		result = myurl.split("/", 2)[1];
	}
    result = result.replace("/", "");

    if(result.indexOf('/') !== -1) {
        result = result.split("/")[0];
    } else if(result.indexOf('?') !== -1) {
        result = result.split("?")[0];
    }

    $('div#navbarCollapse li').removeClass('active');
    $('div#homeicon').removeClass('active');

    if(result === '') {
        result = 'timesheet';
    }
	
	myurl2 = window.location.href;
	
	if(myurl2.indexOf("://") !== -1) {
		myheader2 = myurl2.split("://", 2)[0] + "://";
		myurl2 = myurl2.split("://", 2)[1];
	} else {
		myheader2 = "http://";
	}
	
	if(myurl2.indexOf("/index.php") !== -1) {
        result2 = myurl2.split("/index.php", 2)[0] + "/index.php";
	} else {
		result2 = myurl2.split("/", 2)[0];
	}	

	$('form#form_switchlang').attr('action', myheader2 + result2 + '/timesheet/switchLang?lang=' + mylang);
	$('form#form_switchlang').submit();	
}

function obj2string(obj) {
    var temp = '{', temp0 = '';
    for(var v in obj) {
        if (obj.hasOwnProperty(v)) {
            if(obj[v] !== null && typeof obj[v] !== 'string') {
                temp0 += '"' + v + '":' + obj2string(obj[v]) + ',';
            } else {
                temp0 += '"' + v + '":"' + obj[v] + '",';
            }
        }
    }
    temp0 = temp0.substring(0, temp0.length - 1);
    temp += temp0 + '}';
    return temp;
}

function obj2stringFull(obj) {
    var temp = '{', temp0 = '';
    for(var v in obj) {
        if (obj.hasOwnProperty(v)) {
            if(obj[v] !== null && typeof obj[v] === 'object') {
                temp0 += '"' + v + '":' + obj2string(obj[v]) + ',';
            } else {
                temp0 += '"' + v + '":"' + obj[v] + '",';
            }
        }
    }
    temp0 = temp0.substring(0, temp0.length - 1);
    temp += temp0 + '}';
    return temp;
}

function master_delete(element, id, name) {
  var mytempname = typeof mytrans.DeleteTarget[name] === "undefined" ? "" : (mytrans.DeleteTarget[name] === null ? "" : mytrans.DeleteTarget[name]);
  if(confirm(mytrans.Areyousureyouwanttodeletethis + mytempname + "?")) {
        var mytemp = window.location.href.replace(/\/create/,'');
        var mytemp2 = mytemp.replace(/\/edit\/.*$/,'');
        window.location.href = mytemp2 + '/delete/' + id; 
  }
}

function master_impersonate(element, id, name) {
  if(confirm(mytrans.Areyousureyouwanttoimpersonateasthisuser)) {
        var mytemp = window.location.href.replace(/\/create/,'');
        var mytemp2 = mytemp.replace(/\/edit\/.*$/,'');
        window.location.href = mytemp2 + '/impersonate/' + id; 
  }
}

function enableDoubleClickDrop(sourceElement, targetElement) {
	$(sourceElement).dblclick(function(){
		var temp = $(this).attr('id').split("_");
		var category = (temp.length == 2) ? temp[0] : '';
		var id = (temp.length == 2) ? temp[1] : '';
		var temp2 = '<div class="inline scrolloverflow">' + mytrans.From + ': <input type="text" class="timepicker starttime" value="" />';
		temp2 += '  ' + mytrans.To + ': <input type="text" class="timepicker endtime" value="" /></div>';
		temp2 += '<span class="bold left-name ' + 'cat_' + category + '_' + id + '">' + $(this).text() + '</span>';
		temp2 += '<a class="up-left-item" href="javascript:void(0);" onclick="upParent(this);"><i class="fa fa-arrow-up font24px" aria-hidden="true" title="' + mytrans.Up + '"></i></a>';
		temp2 += '<a class="down-left-item" href="javascript:void(0);" onclick="downParent(this);"><i class="fa fa-arrow-down font24px" aria-hidden="true" title="' + mytrans.Down + '"></i></a>';						
		temp2 += '<a class="remove-left-item" href="javascript:void(0);" onclick="removeParent(this);"><i class="fa fa-trash-o font24px" aria-hidden="true" title="' + mytrans.Remove + '"></i></a>';
		var newDiv = '<div class="left-item ' + category + ' ' + ((temp.length == 2) ? $(this).attr('id') : '') +'">' + temp2 + '</div>';
		$(targetElement).append(newDiv);
		$('input.timepicker').each(function(){
			$(this).datetimepicker({
				format:'H:i',
				datepicker:false,
			});
		});
	}); 
}

  function upParent(element) {
      $(element).parent().insertBefore($(element).parent().prev());
  }
  
  function downParent(element) {
      $(element).parent().insertAfter($(element).parent().next());
  }
  
  function removeParent(element) {
      $(element).parent().remove();
  }