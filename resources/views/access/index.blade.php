@extends('layouts.master')

@section('title', trans('messages.Access'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.Accessrules') }}</p>
@endsection

@section('javascript')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="/js/access.js"></script>
<script>
  $(document).ready(function(){
      $("#tabs, #tabs0, #tabs_user_access").tabs();
      $("#tabs_role_access,#container_employee_access, #container_exceptionemployee_access").tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
      $("#tabs_role_access li,#container_employee_access li, #container_exceptionemployee_access li").removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
  });
</script>
<style>
  .ui-tabs-vertical { width: 55em; }
  .ui-tabs-vertical .ui-tabs-nav { padding: .2em .1em .2em .2em; float: left; width: 12em; }
  .ui-tabs-vertical .ui-tabs-nav li { clear: left; width: 100%; border-bottom-width: 1px !important; border-right-width: 0 !important; margin: 0 -1px .2em 0; }
  .ui-tabs-vertical .ui-tabs-nav li a { display:block; }
  .ui-tabs-vertical .ui-tabs-nav li.ui-tabs-active { padding-bottom: 0; padding-right: .1em; border-right-width: 1px; }
  .ui-tabs-vertical .ui-tabs-panel { padding: 1em; float: right; width: 40em;}

  div.dd-left {
    border:1px solid #dddddd !important;
    min-height: 300px;
  }

  div.dd-right {
    border-width:1px;
    border-style:solid;
    max-height: 350px;
    overflow-y: scroll;
  }

  div.dd-right div.item:nth-child(even) {
    background-color: #e7e7e7;
  }

  div.dd-right div.item:hover {
    background-color: #87CEFA;
    cursor:grab;
  }

  div.left-item {
      padding: 2px 2px 2px 2px;
      width: 100%;
      position: relative;
  }

  div.dd-left div.left-item:nth-child(even) {
    background-color: #e7e7e7;
  }

  div.dd-left div.left-item:hover {
    background-color: #FFFACD;
    cursor: default;
  }

  a.remove-left-item {
      position: absolute;
      right: 5px;
  }

</style>
@endsection

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="row form-inline">
        <div class="col-xs-12">
            <div class="form-group loading">
                <span>{{ trans('messages.AccessList') }}: </span>
            </div>
        </div>
    </div>
    <div style="clear:both;"></div><br/>
    <div class="row form-inline">
        <div class="col-xs-12">
            <div id="tabs0">
                <ul>
                    <li><a href="#tabs0-1">{{ trans('messages.DivisionAccess') }}</a></li>
                    <li><a href="#tabs0-2">{{ trans('messages.RoleAccess') }}</a></li>
                    <li><a href="#tabs0-3">{{ trans('messages.UserAccess') }}</a></li>
                </ul>
                <div id="tabs0-1">
                    @if (!count($accesses))
                    <p>{{ trans('messages.No') }} {{ trans('messages.accesslist') }}</p>
                    @else
                    <div id="tabs">
				        @if (!count($divisions))
						<p>{{ trans('messages.No') }} {{ trans('messages.divisionlist') }}</p>
						@else
						  <ul>
							@for($i=0; $i < count($divisions); $i++)
							<li><a href="#tabs-{{$i+1}}">{{$divisions[$i]['name']}}</a></li>
							@endfor
						  </ul>
						  @for($i=0; $i < count($divisions); $i++)
						  <div id="tabs-{{$i+1}}">
							  @if (!count($accesses))
								  <p>{{ trans('messages.No') }} {{ trans('messages.accesslist') }}</p>
							  @else
								<div class="table-responsive">
									<table class="table table-bordered table-hover table-striped">
									
									<tr>
									<th>{{ trans('messages.Title') }}</th><th>{{ trans('messages.Read') }}</th><th>{{ trans('messages.Create') }}</th><th>{{ trans('messages.Edit') }}</th><th>{{ trans('messages.Delete') }}</th>
									<th>{{ trans('messages.Search') }}</th>
									</tr>
			<!--        accessid(in the access table, primary key) | tab index | access read/create/edit/delete/search | access read/create/edit/delete/search value   
			  
						only admin role can access read/create/edit/delete/search, 
						
						only admin role can drag/drop roles for the role section.
						
						admin can drag/drop all users for the employee,exceptionemployee section.
						manager can drag/drop own associates for the employee,exceptionemployee section.

					//division access tab:
					//input - id:  1|1|read|10, 1|8|read|10
					//$p->id . '|' . $divisions[$i]['id'] . '|read|' . $p->read
					//accessid(in the access table, primary key) | division id (tab index) | access read/create/edit/delete/search | access read/create/edit/delete/search value   
					//get access id, read/create/edit/delete/search
					
					
			-->
								  @foreach ($accesses as $p)
									@if(in_array(strtolower($p->target), array_map('strtolower', array_keys(json_decode(UserHelpers::getConstants('ACCESS_PAGES_ARRAY'), true)))))
										<tr>
										<th>{{$p->target}}</th>
										<td><input type="checkbox" class="tabs_division_access" name="{{$p->id . '|' . $divisions[$i]['id'] . '|read|' . $p->read}}" id="{{$p->id . '|' . $divisions[$i]['id'] . '|read|' . $p->read}}" {{ (($p->read & $divisions[$i]['id']) === $divisions[$i]['id'] && $divisions[$i]['id'] !== 0 && $p->read !== 0) ? 'checked' : ''  }} /></td>
										<td><input type="checkbox" class="tabs_division_access" name="{{$p->id . '|' . $divisions[$i]['id'] . '|create|' . $p->create}}" id="{{$p->id . '|' . $divisions[$i]['id'] . '|create|' . $p->create}}" {{ (($p->create & $divisions[$i]['id']) === $divisions[$i]['id'] && $divisions[$i]['id'] !== 0 && $p->create !== 0) ? 'checked' : ''  }} /></td>
										<td><input type="checkbox" class="tabs_division_access" name="{{$p->id . '|' . $divisions[$i]['id'] . '|edit|' . $p->edit}}" id="{{$p->id . '|' . $divisions[$i]['id'] . '|edit|' . $p->edit}}" {{ (($p->edit & $divisions[$i]['id']) === $divisions[$i]['id'] && $divisions[$i]['id'] !== 0 && $p->edit !== 0) ? 'checked' : ''  }} /></td>
										<td><input type="checkbox" class="tabs_division_access" name="{{$p->id . '|' . $divisions[$i]['id'] . '|delete|' . $p->delete}}" id="{{$p->id . '|' . $divisions[$i]['id'] . '|delete|' . $p->delete}}" {{ (($p->delete & $divisions[$i]['id']) === $divisions[$i]['id'] && $divisions[$i]['id'] !== 0 && $p->delete !== 0) ? 'checked' : ''  }} /></td>
										<td><input type="checkbox" class="tabs_division_access" name="{{$p->id . '|' . $divisions[$i]['id'] . '|search|' . $p->search}}" id="{{$p->id . '|' . $divisions[$i]['id'] . '|search|' . $p->search}}" {{ (($p->search & $divisions[$i]['id']) === $divisions[$i]['id'] && $divisions[$i]['id'] !== 0 && $p->search !== 0) ? 'checked' : ''  }} /></td>
										</tr>
									@endif
								  @endforeach
									</table>
								</div>
							  @endif
						  </div>
						  @endfor
					  @endif
                    </div>
                    @endif
                </div>
                <div id="tabs0-2" class="container">
                    <div  class="row">
                    <div id="tabs_role_access" class="col-xs-12 col-md-8">
                      <ul>
                        @for($i=0; $i < count($accesses); $i++)
                            @if(in_array(strtolower($accesses[$i]->target), array_map('strtolower', array_keys(json_decode(UserHelpers::getConstants('ACCESS_PAGES_ARRAY'), true)))))                            
                                <li><a href="#tabs_role_access_{{ $accesses[$i]->id }}">{{ $accesses[$i]->target }}</a></li>
                            @endif
                        @endfor
                      </ul>

                      @for($i=0; $i < count($accesses); $i++)
                          @if(in_array(strtolower($accesses[$i]->target), array_map('strtolower', array_keys(json_decode(UserHelpers::getConstants('ACCESS_PAGES_ARRAY'), true)))))    
                          <div id="tabs_role_access_{{ $accesses[$i]->id }}" class="dd-left">
                              @php
                                  $rolesArr = empty($accesses[$i]->role) ? array() : explode(",", $accesses[$i]->role)
                              @endphp
                              @foreach($rolesArr as $role)
								  @if(isset($roles[$role]))
								  <div id="role_{{ $accesses[$i]->id }}_{{ $role }}" class="left-item">
									<span class="left-name role_name_{{ $role }}">{{ $roles[$role] }}</span>
									<a class="remove-left-item" href="javascript:void(0);" onclick="removeParent(this);"><i class="fa fa-trash-o font24px" aria-hidden="true" title="Remove this row"></i></a>
								  </div>
								  @endif
                              @endforeach
                          </div>
                          @endif
                      @endfor
                    </div>

                    <div id="role" class="role_create_right_section col-xs-12 col-md-4">
                        <input type="text" name="search_role" id="search_role" onclick="searchfilter(this, 'role');" onkeyup="searchfilter(this, 'role');" placeholder="{{ trans('messages.Searchrolebyname') }}" class="form-control" style="width:90% !important;" value="" />
                        @if (!count($roles))
                            <p>{{ trans('messages.Noroles') }}</p>
                        @else
                            <div class="dd-right">
                                @foreach ($roles as $k => $v)
                                   <div class="item draggable doubleclickable" id="role_{{$k}}" title="Drag and Drop to the Left Box. Or double click.">{{$v}}</div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    </div>
                </div>
                <div id="tabs0-3">
                    <div id="tabs_user_access">
                      <ul>
                        <li><a href="#tabs_employee_access">{{ trans('messages.Employee') }}</a></li>
                        <li><a href="#tabs_exceptionemployee_access">{{ trans('messages.ExceptionEmployee') }}</a></li>
                      </ul>
                      <div id="tabs_employee_access" class="container">
                        <div  class="row">
                            <div id="container_employee_access" class="col-xs-12 col-md-8">
                              <ul>
                                @for($i=0; $i < count($accesses); $i++)
                                    @if(in_array(strtolower($accesses[$i]->target), array_map('strtolower', array_keys(json_decode(UserHelpers::getConstants('ACCESS_PAGES_ARRAY'), true)))))
                                        <li><a href="#tabs_employee_access_{{ $accesses[$i]->id }}">{{$accesses[$i]->target}}</a></li>
                                    @endif
                                @endfor
                              </ul>

                              @for($i=0; $i < count($accesses); $i++)
                                  @if(in_array(strtolower($accesses[$i]->target), array_map('strtolower', array_keys(json_decode(UserHelpers::getConstants('ACCESS_PAGES_ARRAY'), true)))))
                                  <div id="tabs_employee_access_{{ $accesses[$i]->id }}" class="dd-left">
                                      @php
                                          $usersArr = empty($accesses[$i]->employee) ? array() : explode(",", $accesses[$i]->employee);
                                          $usersReportToThisUser = UserHelpers::getAssociatesForManager(UserHelpers::getUID());
                                      @endphp
                                      @foreach($usersArr as $user)
                                         @if(in_array($user, $usersReportToThisUser)) 
                                              <div id="employee_{{ $accesses[$i]->id }}_{{ $user }}" class="left-item">
                                                <span class="left-name user_name_{{ $user }}">{{ $users[$user] }}</span>
                                                <a class="remove-left-item" href="javascript:void(0);" onclick="removeParent(this);"><i class="fa fa-trash-o font24px" aria-hidden="true" title="Remove this row"></i></a>
                                              </div>
                                         @endif
                                      @endforeach
                                  </div>
                                  @endif
                              @endfor

                            </div>
                            <div id="user" class="user_create_right_section col-xs-12 col-md-4">
                                <input type="text" name="search_user" id="search_user" onclick="searchfilter(this, 'user');" onkeyup="searchfilter(this, 'user');" placeholder="{{ trans('messages.Searchuserbyname') }}" class="form-control" style="width:90% !important;" value="" />
                                @if (!count($users))
                                    <p>{{ trans('messages.Nousers') }}</p>
                                @else
                                    <div class="dd-right">
                                        @foreach ($users as $k => $v)
                                           @if(in_array($k, $usersReportToThisUser))
                                               <div class="item draggable doubleclickable" id="user_{{$k}}" title="Drag and Drop to the Left Box. Or double click.">{{$v}}</div>
                                           @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                      </div>
                      <div id="tabs_exceptionemployee_access" class="container">
                        <div  class="row">
                            <div id="container_exceptionemployee_access" class="col-xs-12 col-md-8">
                              <ul>
                                @for($i=0; $i < count($accesses); $i++)
                                    @if(in_array(strtolower($accesses[$i]->target), array_map('strtolower', array_keys(json_decode(UserHelpers::getConstants('ACCESS_PAGES_ARRAY'), true)))))
                                        <li><a href="#tabs_exceptionemployee_access_{{ $accesses[$i]->id }}">{{$accesses[$i]->target}}</a></li>
                                    @endif
                                @endfor
                              </ul>

                              @for($i=0; $i < count($accesses); $i++)
                                  @if(in_array(strtolower($accesses[$i]->target), array_map('strtolower', array_keys(json_decode(UserHelpers::getConstants('ACCESS_PAGES_ARRAY'), true)))))
                                  <div id="tabs_exceptionemployee_access_{{ $accesses[$i]->id }}" class="dd-left">
                                      @php
                                          $userexceptionsArr = empty($accesses[$i]->exceptionemployee) ? array() : explode(",", $accesses[$i]->exceptionemployee)
                                      @endphp
                                      @foreach($userexceptionsArr as $userexception)
                                          @if(in_array($userexception, $usersReportToThisUser)) 
                                              <div id="exceptionemployee_{{ $accesses[$i]->id }}_{{ $userexception }}" class="left-item">
                                                <span class="left-name user_name_{{ $userexception }}">{{ $users[$userexception] }}</span>
                                                <a class="remove-left-item" href="javascript:void(0);" onclick="removeParent(this);"><i class="fa fa-trash-o font24px" aria-hidden="true" title="Remove this row"></i></a>
                                              </div>
                                          @endif
                                      @endforeach
                                  </div>
                                  @endif
                              @endfor

                            </div>
                            <div id="user2" class="user_create_right_section col-xs-12 col-md-4">
                                <input type="text" name="search_user2" id="search_user2" onclick="searchfilter(this, 'user2');" onkeyup="searchfilter(this, 'user2');" placeholder="{{ trans('messages.Searchuserbyname') }}" class="form-control" style="width:90% !important;" value="" />
                                @if (!count($users))
                                    <p>{{ trans('messages.Nousers') }}</p>
                                @else
                                    <div class="dd-right">
                                        @foreach ($users as $k => $v)
                                           @if(in_array($k, $usersReportToThisUser))
                                               <div class="item draggable doubleclickable" id="user2_{{$k}}" title="Drag and Drop to the Left Box. Or double click.">{{$v}}</div>
                                           @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="clear:both;"></div><br/>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-6 col-md-4">
                <a href="javascript:void(0);" class="btn btn-primary" onclick="submitAccessTabs();">{{ trans('messages.Submit') }}</a>
            </div>
        </div>
    </div>
    <div style="clear:both;"></div><br/>
    <form id="accessupdate" action="{{url()->current()}}/updateAccess" method="post">
        <input type="hidden" name="mydata" id="mydata" />
        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
    </form>

@endsection

