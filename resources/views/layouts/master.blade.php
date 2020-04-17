<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>
        <!-- <link rel="stylesheet" href="/css/bootstrap.min.css"> -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="/js/jquery.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/jquery.datetimepicker.js"></script>
		<script>
		    var mytemp = "{{ Session::get('my_locale') == null ? (UserHelpers::getConstants('LANGUAGE') == null ? config('app.locale') : UserHelpers::getConstants('LANGUAGE')) : Session::get('my_locale')}}", mylang = "en", mytrans = {};
			if(mytemp === "bp") {
				mylang = "pt";
			} else if(mytemp === "zhs" || mytemp === "zht") {
				mylang = "ch";
			}
		    $(document).ready(function(){
		        $('input.datepicker').datetimepicker({lang: mylang});
		    });
		</script>
		<script src="/js/masterpage.js"></script>
        @yield('javascript')
        <link rel="stylesheet" href="/css/jquery.datetimepicker.css">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="/css/style.css">
        <noscript><style>nav#myNavbar, div.container {display:none}</style>
            <font face=arial>JavaScript must be enabled in order for you to use this website. However, it seems JavaScript is either disabled or not supported by your browser. To use this website, enable JavaScript by changing your browser options, then <a href="">try again</a>.</font>
			<font face=arial>O JavaScript deve estar ativado para que você possa usar este site. No entanto, parece que o JavaScript está desativado ou não é suportado pelo seu navegador. Para usar este site, ative o JavaScript alterando as opções do navegador e, em seguida, <a href="">tente novamente</a>.</font>
			<font face=arial>必须启用JavaScript才能使用此网站。但是，您的浏览器似乎禁用了JavaScript或不支持JavaScript。要使用此网站，请通过更改浏览器选项来启用JavaScript，然后 <a href="">重试</a>.</font>
			<font face=arial>必須啟用JavaScript才能使用此網站。但是，您的瀏覽器似乎禁用了JavaScript或不支持JavaScript。要使用此網站，請通過更改瀏覽器選項來啟用JavaScript，然後 <a href="">重試</a>.</font>
        </noscript>
    </head>
    <body>
          <nav id="myNavbar" class="navbar navbar-default navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="container">
              <div class="navbar-header" id="homeicon">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbarCollapse">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand"  href="{{ url('/') }}" target="_self">@lang('messages.TIMESHEET')</a>
              </div>
              {{  App::setLocale(Session::get('my_locale') == null ? (UserHelpers::getConstants('LANGUAGE') == null ? config('app.locale') : UserHelpers::getConstants('LANGUAGE')) : Session::get('my_locale'))  }}
              <!-- Collect the nav links, forms, and other content for toggling -->
              <div class="collapse navbar-collapse" id="navbarCollapse">

                <ul class="nav navbar-nav">
                @if(!in_array('dashboard', UserHelpers::getmenuExceptions(true)))
                  <li id="dashboard"><a href="{{ url('/dashboard') }}" target="_self">@lang('messages.Dashboard')</a></li>
                @endif
                @if(!in_array('report', UserHelpers::getmenuExceptions(true)))
                  <li id="report" ><a href="{{ url('/report') }}" target="_self">@lang('messages.Report')</a></li>
                  @endif
                @if(!in_array('timesheet', UserHelpers::getmenuExceptions(true)))
                  <li id="timesheet" ><a href="{{ url('/timesheet') }}" target="_self">@lang('messages.Timesheet')</a></li>
                  @endif
                @if(!in_array('reviewtimesheet', UserHelpers::getmenuExceptions(true)))
                  <li id="reviewtimesheet" ><a href="{{ url('/reviewtimesheet') }}" target="_self">@lang('messages.ReviewOthersTimesheet')</a></li>
                  @endif
                @if(!in_array('typecategory', UserHelpers::getmenuExceptions(true)))
                  <li id="typecategory" ><a href="{{ url('/typecategory') }}" target="_self">@lang('messages.TypeCategory')</a></li>
                  @endif
                @if(!in_array('type', UserHelpers::getmenuExceptions(true)))
                  <li id="type"><a href="{{ url('/type') }}" target="_self">@lang('messages.Type')</a></li>
                  @endif
                  @if(!in_array('access', UserHelpers::getmenuExceptions(true)) || 
                      !in_array('user', UserHelpers::getmenuExceptions(true)) ||
                      !in_array('role', UserHelpers::getmenuExceptions(true)) ||
                      !in_array('division', UserHelpers::getmenuExceptions(true)) ||
                      !in_array('autoemail', UserHelpers::getmenuExceptions(true)) ||
                      !in_array('webhook', UserHelpers::getmenuExceptions(true)) ||
					  !in_array('constant', UserHelpers::getmenuExceptions(true))
                  )
                  <li id="setting" class="dropdown">
                      <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">@lang('messages.Setting') <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                      @if(!in_array('access', UserHelpers::getmenuExceptions(true)))
                        <li id="access"><a href="{{ url('/access') }}" target="_self">@lang('messages.Access')</a></li>
                        @endif

                      @if(!in_array('user', UserHelpers::getmenuExceptions(true)))
                        <li role="separator" class="divider"></li>
                        <li id="user" ><a href="{{ url('/user') }}" target="_self">@lang('messages.User')</a></li>
                      @endif

                      @if(!in_array('role', UserHelpers::getmenuExceptions(true)))
                        <li role="separator" class="divider"></li>
                        <li id="role" ><a href="{{ url('/role') }}" target="_self">@lang('messages.Role')</a></li>
                      @endif

                      @if(!in_array('division', UserHelpers::getmenuExceptions(true)))
                        <li role="separator" class="divider"></li>
                        <li id="division" ><a href="{{ url('/division') }}" target="_self">@lang('messages.Division')</a></li>
                      @endif

                      @if(!in_array('autoemail', UserHelpers::getmenuExceptions(true)))
                          <li role="separator" class="divider"></li>
                        <li id="autoemail" ><a href="{{ url('/autoemail') }}" target="_self">@lang('messages.AutoEmail')</a></li>
                      @endif

                      @if(!in_array('webhook', UserHelpers::getmenuExceptions(true)))
                          <li role="separator" class="divider"></li>
                        <li id="webhook" ><a href="{{ url('/webhook') }}" target="_self">@lang('messages.Webhook')</a></li>
                      @endif
					  
					  @if(!in_array('constant', UserHelpers::getmenuExceptions(true)))
						  <li role="separator" class="divider"></li>
						<li id="constant" ><a href="{{ url('/constant') }}" target="_self">@lang('messages.Constant')</a></li>
					  @endif
                      </ul>
                  </li>
                  @endif

                </ul>

                  <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user-circle-o fa-6"></i><span style="margin-right:5px;">&nbsp;</span><span class="caret"></span><span style="margin-right:10px;">&nbsp;</span></a>
                      <ul class="dropdown-menu" style="margin-right:15px;min-width: 300px;">
                        @if (Auth::check() && Session::get('UID'))
                            <li class="dropdown-submenu" style="font-weight:bold;padding:3px 20px;">@lang('messages.Language'):							
							    <select class="form-control" onchange="master_switchLang();" style="display:inline-block;" name="master_lang" id="master_lang">                                     
									@if(Session::get('my_locale') === "en")
									    <option style="font-weight:bold;" value="en" selected="">English</option>
                                        <!-- <option style="font-weight:bold;" value="bp">Portugues do Brasil</option>  -->
										<option style="font-weight:bold;" value="zhs">中文（简体）</option>
										<option style="font-weight:bold;" value="zht">中文（繁体）</option>
									@elseif(Session::get('my_locale') === "bp")
									    <option style="font-weight:bold;" value="en">English</option>
                                        <!-- <option style="font-weight:bold;" value="bp" selected="">Portugues do Brasil</option>  -->
										<option style="font-weight:bold;" value="zhs">中文（简体）</option>
										<option style="font-weight:bold;" value="zht">中文（繁体）</option>
									@elseif(Session::get('my_locale') === "zhs")
									    <option style="font-weight:bold;" value="en">English</option>
                                        <!-- <option style="font-weight:bold;" value="bp">Portugues do Brasil</option>  -->
										<option style="font-weight:bold;" value="zhs" selected="">中文（简体）</option>
										<option style="font-weight:bold;" value="zht">中文（繁体）</option>
									@elseif(Session::get('my_locale') === "zht")
									    <option style="font-weight:bold;" value="en">English</option>
                                        <!-- <option style="font-weight:bold;" value="bp">Portugues do Brasil</option>  -->
										<option style="font-weight:bold;" value="zhs">中文（简体）</option>
										<option style="font-weight:bold;" value="zht" selected="">中文（繁体）</option>
									@elseif(UserHelpers::getConstants('LANGUAGE') === "en")
									    <option style="font-weight:bold;" value="en" selected="">English</option>
                                        <!-- <option style="font-weight:bold;" value="bp">Portugues do Brasil</option>  -->
										<option style="font-weight:bold;" value="zhs">中文（简体）</option>
										<option style="font-weight:bold;" value="zht">中文（繁体）</option>
									@elseif(UserHelpers::getConstants('LANGUAGE') === "bp")
									    <option style="font-weight:bold;" value="en">English</option>
                                        <!-- <option style="font-weight:bold;" value="bp" selected="">Portugues do Brasil</option>  -->
										<option style="font-weight:bold;" value="zhs">中文（简体）</option>
										<option style="font-weight:bold;" value="zht">中文（繁体）</option>
									@elseif(UserHelpers::getConstants('LANGUAGE') === "zhs")
									    <option style="font-weight:bold;" value="en">English</option>
                                        <!-- <option style="font-weight:bold;" value="bp">Portugues do Brasil</option>  -->
										<option style="font-weight:bold;" value="zhs" selected="">中文（简体）</option>
										<option style="font-weight:bold;" value="zht">中文（繁体）</option>
									@elseif(UserHelpers::getConstants('LANGUAGE') === "zht")
									    <option style="font-weight:bold;" value="en">English</option>
                                        <!-- <option style="font-weight:bold;" value="bp">Portugues do Brasil</option> -->
										<option style="font-weight:bold;" value="zhs">中文（简体）</option>
										<option style="font-weight:bold;" value="zht" selected="">中文（繁体）</option>
								    @endif
                                </select>								
							</li>
                            <li role="separator" class="divider"></li>
                            <li><a href="javascript:void(0);" onclick="document.getElementById('form_signout').submit();" >@lang('messages.Signout')</a></li>
						    <li role="separator" class="divider"></li>
							<li style="font-weight:bold;padding:3px 20px;">{{ UserHelpers::getUsername() }}</li>
                            <form id="form_switchlang" action="{{ url('/timesheet/switchLang') }}" method="post">{{ csrf_field() }}
                            </form>
                            <form id="form_signout" action="{{ url('/logout') }}" method="post">{{ csrf_field() }}
                            </form>
                        @elseif(Auth::check() && (!Session::get('UID'))) 
						    @php
						      session_unset();
							  Session::flush();
							  Auth::logout();
							@endphp
							<!--  <script>window.location = "{{ url('/') }}";</script>  -->
                            <li><a href="{{ url('/login') }}">@lang('messages.Signin')</a></li>
						@else
							<li><a href="{{ url('/login') }}">@lang('messages.Signin')</a></li>
                        @endif
                      </ul>
                    </li>
                  </ul>

              </div>
              <div id="loadinggif" class="label label-danger" style="position:absolute;z-index:1000;top:0px;width:80px;margin-left:auto;margin-right:auto;left:0;right:0;display:none;">Loading...</div>
            </div>
          </nav><br/><br/><br/>

        @section('sidebar')
            
        @show

        <div class="container">
            @yield('content')
        </div>
        
        <div class="container">
            @yield('othercontent')
        </div>
    </body>
</html>
