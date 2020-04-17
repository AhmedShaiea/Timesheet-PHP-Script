<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Chinese Traditional Translations
    |--------------------------------------------------------------------------
    |
    |
    */

    //'previous' => '&laquo; Previous',
    //'next' => 'Next &raquo;',
"Language" => "語言",
"timesheet" => "時間表",
"Timesheet" => "時間表",
"NewTimesheet" => "新時間表",
"TIMESHEET" => "時間表",
"Notimesheets" => "沒有時間表",
"Nousers" => "沒有用戶",
"Notypecategories" => "沒有類別",
"Notypes" => "沒有具體類型",
"Noroles" => "沒有角色",
"Noconstants" => "沒有常量",
"Employee" => "僱員",
"ExceptionEmployee" => "例外僱員",
"EditTimesheet" => "編輯時間表",
"ComposeArea" => "創建區",
"ComposeArea1" => "從右邊拖動物品抓取或雙擊一個選項，放到左邊的虛線框裡。",
"ComposeArea2" => "只需要填寫起始時間，然後點擊“自動填充時間”來自動填充終止時間。",
"ComposeArea3" => "如果有時間間隔，為時間間隔創建一個具體類型。",
"Week" => "週",
"AutoFillTime" => "自動填時間",
"Preview" => "預覽",
"DragandDroptotheLeftBox" => "拖放到左框",
"Up" => "上移",
"Down" => "下移",
"Remove" => "刪除",
"TimesheetsavedPostedtoallwebhooks" => "時間表已保存。自動發送了數據到所有的另外的網站。",
"TimesheetsavedPostedtosomeofthewebhooks" => "時間表已保存。自動發送了數據到部分的另外的網站。",
"TimesheetsavedNowebhooksavailable" => "時間表已保存。沒有另外的網站用來發送數據。",
"YoualreadysavedsometimeperioddataintodatabaseDuplicatedPleasecreateagain" => "你以前已經存了某些時間段的數據。數據重複。請重新創建。",
"NotallthedatacanbesavedintodatabasePleasecreateagain" => "不是所有數據能存入數據庫。請重新創建。",
"Thisrecordisnotyours" => "這記錄不是您的。",
"ErrorPleasecreateagain" => "錯誤。請重新創建。",
"Requestfailed" => "請求失敗",
"Pleaserefreshthepageandtryagain" => "請刷新網頁重試。",
"Pleaseenterdatetimeinfo" => "請輸入日期時間信息。",
"PleaseenterMondaySundayorSaturday" => "請輸入周一，週日，或者周六。",
"Timeisnotcontinuousatdate" => "在這個日期時間不連續",
"Pleasefillallthedatefirst" => "請先填寫所有日期!",
"Notypesfor" => "沒有具體類型屬於",
"dashboard" => "圖表",
"Dashboard" => "圖表",
"report" => "報告",
"Report" => "報告",
"reviewotherstimesheet" => "審閱下屬時間表",
"ReviewOthersTimesheet" => "審閱下屬時間表",
"typecategory" => "類別",
"Typecategory" => "類別",
"TypeCategory" => "類別",
"type" => "具體類型",
"Type" => "具體類型",
"setting" => "設置",
"Setting" => "設置",
"signin" => "登錄",
"Signin" => "登錄",
"signout" => "退出",
"Signout" => "退出",
"TimesheetRules" => "時間表的規則: 時間必須是連續的。如果中間有間斷，用一個表示間斷的具體類型 （比如午餐時間）。時間表一旦被你的上級審閱通過，你不能再更改它。但是，你能刪除它。如果你是超級用戶，你不需要任何人來審閱你的時間表。",
"timesheetlist" => "時間表列表",
"TimesheetList" => "時間表列表",
"itemsperpage" => "每頁",
"ID" => "ID",
"name" => "名字",
"Name" => "名字",
"Description" => "描述",
"Desc" => "描述",
"DateRange" => "日期範圍",
"nodata" => "沒有數據",
"Time" => "時間",
"Hours" => "小時",
"TotalHours" => "總小時",
/*  "Detaildatehours" => "細節 (日期 - 小時)",  */
"Detail" => "細節",
"Detaildatehours" => "細節<br/><input type=\"radio\" name=\"detail\" value=\"hours\" onclick=\"getTimesheetDetailHours(this);\" checked=\"checked\" />小時&nbsp;&nbsp;<input type=\"radio\" name=\"detail\" value=\"datehours\" onclick=\"getTimesheetDetailHours(this);\" />日期-小時",                             /*  "Detail (date - hours)",  */
"Approved" => "通過",
"Approvedby" => "審閱人",
"ApprovedTime" => "審閱時間",
"ReviewNotes" => "審閱意見",
"Status" => "狀態",
"create" => "新建",
"Create" => "新建",
"edit" => "編輯",
"Edit" => "編輯",
"delete" => "刪除",
"Delete" => "刪除",
"Dashboardrules" => "圖表的規則: 圖表顯示的是審閱通過的時間表的統計數據。",
"more" => "更多",
"More" => "更多",
"Onlyshowoneusersworkhourschart" => "只顯示一個用戶的圖表",
"Onlyshowonetypesuserhourschart" => "只顯示一個具體類型的圖表",
"from" => "從",
"From" => "從",
"to" => "到",
"To" => "到",
"date" => "日期",
"Date" => "日期",
"fromdate" => "起始日期",
"Fromdate" => "起始日期",
"todate" => "終止日期",
"Todate" => "終止日期",
"chooseoneuser" => "選一個用戶",
"Chooseoneuser" => "選一個用戶",
"showchart" => "顯示圖表",
"removeallchart" => "刪除所有圖表",
"chooseonetype" => "選擇一個具體類型",
"Chooseonetype" => "選擇一個具體類型",
"Reportrules" => "報告的規則: 你能看到你和下屬的通過的時間表記錄並下載。",
"downloadzippedexcelcsvJSONpdffiles" => "下載壓縮的excel, CSV, JSON, pdf 文件",
"ReportbyTypes" => "按具體類型報告",
"ReportbyUsers" => "按用戶報告",
"datefrom" => "日期 － 從",
"DateFrom" => "日期 － 從",
"chooseone" => "選一個",
"Chooseone" => "選一個",
"Showresultonthepage" => "顯示結果",
"Allinonefile" => "全在一個文件裡",
"Eachrowinseparatefiles" => "每一行放在單獨的文件裡",
"Filename" => "文件名",
"CreateFile" => "新建文件",
"searchbyname" => "按名字搜索",
"Searchbyname" => "按名字搜索",
"Choosealltheusers" => "選所有用戶",
"Searchuserbyname" => "按名字搜索用戶",
"Searchrolebyname" => "按名字搜索角色",
"Eachuserinseparatefiles" => "每個用戶放在單獨的文件裡",
"ReviewTimesheetrules" => "審閱時間表的規則: 你能審閱你的下屬的時間表並決定是否通過 (30天以內新建的 (缺省, 你能修改常量 REVIEW_TIMESHEET_DAYS_RANGE_INT 的值來改變))。",
"ReviewTimesheetsListcreatedwithin30days" => "審閱時間表列表 （30天內新建）",
"NoTimesheetforReview" => "沒有時間表待審閱",
"Areyousureyouwanttoimpersonateasthisuser" => "你確定你想用這個用戶登錄？",
"Areyousureyouwanttodeletethistimesheet" => "你確定你想刪除這個時間表？",
"RequestfailederrorPleaserefreshthepageandtryagain" => "請求失敗。請刷新頁面再試一次。",
"submitting" => "正在提交",
"Submitting" => "正在提交",
"cancel" => "取消",
"Cancel" => "取消",
"Sunday" => "週日",
"Monday" => "週一",
"Tuesday" => "週二",
"Wednesday" => "週三",
"Thursday" => "週四",
"Friday" => "週五",
"Saturday" => "週六",
"Sun" => "週日",
"Mon" => "週一",
"Tue" => "週二",
"Wed" => "週三",
"Thu" => "週四",
"Fri" => "週五",
"Sat" => "週六",
"approved" => "通過",
"created" => "建立",
"Created" => "建立",
"ended"=>"終止",
"Ended"=>"終止",
"TypeCategoryrules" => "類別的規則： 類別的例子： 工作，休息，商務旅行，休假，會議。",
"TypeCategoriesList" => "類別列表",
"submit" => "提交",
"Submit" => "提交",
"Areyousureyouwanttodeletethistypecategory" => "你確定你想刪除這個類別？",
"OK" => "好",
"NewTypeCategory" => "新類別",
"NewType" => "新具體類型",
"Billable" => "計費",
"yes" => "是",
"no" => "否",
"Yes" => "是",
"No" => "否",
"DivisionAccess" => "部門權限",
"RoleAccess" => "角色權限",
"UserAccess" => "用戶權限",
"sales" => "銷售",
"marketing" => "市場",
"accounting" => "會計",
"IT" => "IT",
"read" => "讀",
"Read" => "讀",
"search" => "搜索",
"Search" => "搜索",
"Typerules" => "具體類型的規則： 每個具體類型必須屬於某一個類別。只有admin, 經理和以上級別才能進入這個網頁來創建，修改一個具體類型。",
"TypeList" => "具體類型列表",
"AccessList" => "權限列表",
"Accesslist" => "權限列表",
"Areyousureyouwanttodeletethistype" => "你確定你想刪除這個具體類型？",
"access" => "權限",
"Access" => "權限",
"user" => "用戶",
"User" => "用戶",
"role" => "角色",
"Role" => "角色",
"division" => "部門",
"Division" => "部門",
"autoemail" => "自動發郵件",
"AutoEmail" => "自動發郵件",
"webhook" => "自動發數據到另外網站",
"Webhook" => "自動發數據到另外網站",
"constant" => "常量",
"Constant" => "常量",
"Accessrules" => "權限的規則： 讀／新建／更新／刪除／搜索 網頁的權限是基於部門，角色，和用戶。",
"AccessList" => "權限列表",
"Title" => "標題",
"Userrules" => "用戶的規則：出於安全考慮，只有admin，管理層能註冊新用戶。",
"UsersList" => "用戶列表",
"impersonate" => "以此人登錄",
"Impersonate" => "以此人登錄",
"phone" => "電話",
"Phone" => "電話",
"email" => "電郵",
"Email" => "電郵",
"SendPasswordResetLink" => "發送密碼重置鏈接",
"reportto" => "報告給",
"Reportto" => "報告給",
"username" => "用戶名",
"UserName" => "用戶名",
"NewUser" => "新用戶",
"Username" => "用戶名",
"password" => "密碼",
"Password" => "密碼",
"ConfirmPassword" => "確認密碼",
"forgotyourpassword" => "忘記了密碼？",
"resetpassword" => "重置密碼",
"firstname" => "名",
"lastname" => "姓",
"FirstName" => "名",
"LastName" => "姓",
"address" => "地址",
"address2" => "地址 2",
"Address" => "地址",
"Address2" => "地址 2",
"city" => "城市",
"province" => "省",
"country" => "國家",
"postalcode" => "郵政編碼",
"picture" => "圖片",
"City" => "城市",
"Province" => "省",
"Country" => "國家",
"PostalCode" => "郵政編碼",
"Picture" => "圖片",
"browse" => "瀏覽",
"nofileselected" => "沒有選定文件",
"hourlyrate" => "每小時工資",
"yearlyrate" => "每年工資",
"HourlyRate" => "每小時工資",
"YearlyRate" => "每年工資",
"Areyousureyouwanttodeletethisuser" => "你確定你想刪除這個用戶？",
"noimage" => "無圖片",
"token" => "驗證碼",
"tokenvalidto" => "驗證碼有效期至",
"lastupdated" => "最後一次更新日期",
"Token" => "驗證碼",
"TokenValidTo" => "驗證碼有效期至",
"LastUpdated" => "最後一次更新日期",
"Rolerules" => "角色的規則：角色例子：主任，經理，項目帶頭人。",
"RoleList" => "角色列表",
"NewRole" => "新角色",
"RoleEdit" => "更新角色",
"active" => "在使用",
"notactive" => "不再使用",
"Active" => "在使用",
"NotActive" => "不再使用",
"Areyousureyouwanttodeletethisrole" => "你確定你想刪除這個角色？",
"Divisionrules" => "部門的規則： 部門的例子： 銷售，市場，會計。在數據庫中的id應該與2有關。比如： 1, 2, 4, 8, 16, 32, 64, 128。",
"DivisionList" => "部門列表",
"divisionlist" => "部門列表",
"NewDivision" => "新部門",
"DivisionEdit" => "更新部門",
"Areyousureyouwanttodeletethisdivision" => "你確定你想刪除這個部門？",
"Autoemailrules" => "自動發送郵件的規則：郵件能發給自己，經理，和admin。也許它在spam文件夾。",
"Autoemail" => "自動發郵件",
"Onceatimesheetiscreated" => "一旦建立一個新的時間表",
"Emailself" => "發郵件給自己",
"Emailthispersonsmanager" => "發郵件給此人的經理",
"Emailadmin" => "發郵件給admin",
"Onceatimesheetisedited" => "一旦一個時間表被更新",
"Onceatimesheetisreviewedbyamanager" => "一旦一個時間表被經理審閱",
"Emailtimesheetcreator" => "發郵件給時間表建立者",
"Emailtimesheetcreatorsmanager" => "發郵件給時間表建立者的經理",
"Webhookrules" => "自動發數據到另外一個網站的規則：當一個時間表被建立或者審閱，自動發數據到另外一個網站。",
"Constantrules" => "常量的規則：更改常量值之後，退出再登陸才會生效。如果常量是整數，在常量尾加'_INT'。如果常量是數組，在常量尾加'_ARRAY'， 在描述裡寫入其對應的JSON。如果常量是數 (比如 3.58)，在常量尾加'_NUMBER'。根據你的主辦公室的地點，更新你的TIMEZONE常量。時間區的列表在這裡：http://php.net/manual/en/timezones.php。",
"ConstantList" => "常量列表",
"Youdidnotchangeanything" => "你什麼都沒改。",
"NewConstant" => "新常量"
];
