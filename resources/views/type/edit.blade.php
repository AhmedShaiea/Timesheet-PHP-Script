@extends('layouts.master')

@section('title', trans('messages.Type'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.Typerules') }}</p>
@endsection

@section('javascript')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="/js/type.js"></script>
<script>
  $(document).ready(function(){
      $("#tabs, #tabs0, #tabs_user_type").tabs();
      $("#tabs_role_type,#container_employee_type, #container_exceptionemployee_type").tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
      $("#tabs_role_type li,#container_employee_type li, #container_exceptionemployee_type li").removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
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
  <h3>{{ trans('messages.Edit') }} {{ trans('messages.Type') }}:</h3>

  <form id="" action="" method="POST">
      <div class="container-fluid">
            <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.TypeCategory') }}:
             <select class="form-control" name="dropdownlist_typecategory" id="dropdownlist_typecategory">
                 <option value="0" {{ $type->typecategoryid === 0 ? 'selected' : ''  }}>{{ trans('messages.Chooseone') }}...</option>
             @foreach ($typecategories as $typecategory)
                 <option value="{{ $typecategory->id }}" {{ $type->typecategoryid === $typecategory->id ? 'selected' : ''  }} >{{ $typecategory->name }}</option>
             @endforeach
            </select>
            </div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Name') }}: <input type="text" name="name" id="name" class="form-control" value="{{ $type->name }}" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Desc') }}: <textarea name="desc" id="desc" class="form-control" rows="3">{{ $type->description }}</textarea></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Billable') }}:
                <select class="form-control" name="billable" id="billable">
                    <option value="0" {{ empty($type->billable) ? 'selected' : ''  }}>{{ trans('messages.No') }}</option>
                    <option value="1" {{ empty($type->billable) ? '' : 'selected'  }} >{{ trans('messages.Yes') }}</option>
                </select>
            </div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Created') }}: <input type="text" name="created" id="created" class="form-control datetimepicker" value="{{ $type->created }}" disabled /></div>
          </div>

            <div class="row form-inline">
                <div class="col-xs-12">
                    <div id="tabs0">
                        <ul>
                            <li><a href="#tabs0-1">{{ trans('messages.DivisionAccess') }}</a></li>
                            <li><a href="#tabs0-2">{{ trans('messages.RoleAccess') }}</a></li>
                            <li><a href="#tabs0-3">{{ trans('messages.UserAccess') }}</a></li>
                        </ul>
                        <div id="tabs0-1">
                            <div id="tabs">
                              <ul>
                                @for($i=0; $i < count($divisions); $i++)
                                <li><a href="#tabs-{{$i+1}}">{{ $divisions[$i]['name'] }}</a></li>
                                @endfor
                              </ul>
                              @for($i=0; $i < count($divisions); $i++)
                              <div id="tabs-{{$i+1}}">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped">
                                        <tr>
                                        <th>{{ trans('messages.Read') }}</th><th>{{ trans('messages.Create') }}</th><th>{{ trans('messages.Edit') }}</th><th>{{ trans('messages.Delete') }}</th>
                                        <th>{{ trans('messages.Search') }}</th>
                                        </tr>
                <!--        tab index | type read/edit/delete/search | type read/edit/delete/search value   
                  
                            only admin role can type read/edit/delete/search, 
                            
                            only admin role can drag/drop roles for the role section.
                            
                            admin can drag/drop all users for the employee,exceptionemployee section.
                            manager can drag/drop own associates for the employee,exceptionemployee section.

                        //division access tab:
                        //input - id:  1|read|10, 8|read|10
                        //$divisions[$i]['id'] . '|read|' . $p->read
                        //division id (tab index) | type read/edit/delete/search | type read/edit/delete/search value   
                        //get read/edit/delete/search
                        
                        
                -->

                                        <tr>
                                        <td><input type="checkbox" class="tabs_division_type" name="{{$divisions[$i]['id'] . '|read|' . $type->read}}" id="{{$divisions[$i]['id'] . '|read|' . $type->read}}" {{ (($type->read & $divisions[$i]['id']) === $divisions[$i]['id'] && $divisions[$i]['id'] !== 0 && $type->read !== 0) ? 'checked' : ''  }}  /></td>
                                        <td><input type="checkbox" class="tabs_division_type" name="{{$divisions[$i]['id'] . '|create|' . $type->create}}" id="{{$divisions[$i]['id'] . '|create|' . $type->create}}"  {{ (($type->create & $divisions[$i]['id']) === $divisions[$i]['id'] && $divisions[$i]['id'] !== 0 && $type->create !== 0) ? 'checked' : ''  }} /></td>
                                        <td><input type="checkbox" class="tabs_division_type" name="{{$divisions[$i]['id'] . '|edit|' . $type->edit}}" id="{{$divisions[$i]['id'] . '|edit|' . $type->edit}}" {{ (($type->edit & $divisions[$i]['id']) === $divisions[$i]['id'] && $divisions[$i]['id'] !== 0 && $type->edit !== 0) ? 'checked' : ''  }}  /></td>
                                        <td><input type="checkbox" class="tabs_division_type" name="{{$divisions[$i]['id'] . '|delete|' . $type->delete}}" id="{{$divisions[$i]['id'] . '|delete|' . $type->delete}}" {{ (($type->delete & $divisions[$i]['id']) === $divisions[$i]['id'] && $divisions[$i]['id'] !== 0 && $type->delete !== 0) ? 'checked' : ''  }}  /></td>
                                        <td><input type="checkbox" class="tabs_division_type" name="{{$divisions[$i]['id'] . '|search|' . $type->search}}" id="{{$divisions[$i]['id'] . '|search|' . $type->search}}"  {{ (($type->search & $divisions[$i]['id']) === $divisions[$i]['id'] && $divisions[$i]['id'] !== 0 && $type->search !== 0) ? 'checked' : ''  }} /></td>
                                        </tr>

                                        </table>
                                    </div>
                              </div>
                              @endfor
                            </div>
                        </div>
                        <div id="tabs0-2" class="container">
                            <div  class="row">
                                <div class="col-xs-12 col-md-8">
                                  <div id="tabs_role_type_1" class="dd-left">
                                      @php
                                          $rolesArr = empty($type->role) ? array() : explode(",", $type->role)
                                      @endphp
                                      @foreach($rolesArr as $role)
                                      <div id="role_{{ $type->id }}_{{ $role }}" class="left-item">
                                        <span class="left-name role_name_{{ $role }}">{{ isset($roles[$role]) ? $roles[$role] : "N/A" }}</span>
                                        <a class="remove-left-item" href="javascript:void(0);" onclick="removeParent(this);"><i class="fa fa-trash-o font24px" aria-hidden="true" title="Remove this row"></i></a>
                                      </div>
                                      @endforeach
                                  </div>
                                </div>

                                <div id="role" class="role_create_right_section col-xs-12 col-md-4">
                                    <input type="text" name="search_role" id="search_role" onclick="searchfilter(this, 'role');" onkeyup="searchfilter(this, 'role');" placeholder="{{ trans('messages.Searchrolebyname') }}" class="form-control" style="width:90% !important;" value="" />
                                    @if (!count($roles))
                                        <p>{{ trans('messages.Noroles') }}</p>
                                    @else
                                        <div class="dd-right">
                                            @foreach ($roles as $k => $v)
                                               <div class="item draggable" id="role_{{$k}}" title="Drag and Drop to the Left Box">{{$v}}</div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div id="tabs0-3">
                            <div id="tabs_user_type">
                              <ul>
                                <li><a href="#tabs_employee_type">{{ trans('messages.Employee') }}</a></li>
                                <li><a href="#tabs_exceptionemployee_type">{{ trans('messages.ExceptionEmployee') }}</a></li>
                              </ul>
                              <div id="tabs_employee_type" class="container">
                                <div  class="row">
                                    <div id="container_employee_type" class="col-xs-12 col-md-8">
                                        <div id="tabs_employee_type_1" class="dd-left">
                                          @php
                                              $usersArr = empty($type->employee) ? array() : explode(",", $type->employee);
                                              $usersReportToThisUser = UserHelpers::getAssociatesForManager(UserHelpers::getUID());
                                          @endphp
                                          @foreach($usersArr as $user)
                                             @if(in_array($user, $usersReportToThisUser)) 
                                                  <div id="employee_{{ $type->id }}_{{ $user }}" class="left-item">
                                                    <span class="left-name user_name_{{ $user }}">{{ $users[$user] }}</span>
                                                    <a class="remove-left-item" href="javascript:void(0);" onclick="removeParent(this);"><i class="fa fa-trash-o font24px" aria-hidden="true" title="Remove this row"></i></a>
                                                  </div>
                                             @endif
                                          @endforeach
                                        </div>
                                    </div>
                                    <div id="user" class="user_create_right_section col-xs-12 col-md-4">
                                        <input type="text" name="search_user" id="search_user" onclick="searchfilter(this, 'user');" onkeyup="searchfilter(this, 'user');" placeholder="{{ trans('messages.Searchuserbyname') }}" class="form-control" style="width:90% !important;" value="" />
                                        @if (!count($users))
                                            <p>{{ trans('messages.Nousers') }}</p>
                                        @else
                                            <div class="dd-right">
                                                @foreach ($users as $k => $v)
                                                   @if(in_array($k, $usersReportToThisUser))
                                                       <div class="item draggable" id="user_{{$k}}" title="Drag and Drop to the Left Box">{{$v}}</div>
                                                   @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                              </div>
                              <div id="tabs_exceptionemployee_type" class="container">
                                <div  class="row">
                                    <div id="container_exceptionemployee_type" class="col-xs-12 col-md-8">
                                        <div id="tabs_exceptionemployee_type_2" class="dd-left">
                                          @php
                                              $userexceptionsArr = empty($type->exceptionemployee) ? array() : explode(",", $type->exceptionemployee)
                                          @endphp
                                          @foreach($userexceptionsArr as $userexception)
                                              @if(in_array($userexception, $usersReportToThisUser))
                                                  <div id="exceptionemployee_{{ $type->id }}_{{ $userexception }}" class="left-item">
                                                    <span class="left-name user_name_{{ $userexception }}">{{ $users[$userexception] }}</span>
                                                    <a class="remove-left-item" href="javascript:void(0);" onclick="removeParent(this);"><i class="fa fa-trash-o font24px" aria-hidden="true" title="Remove this row"></i></a>
                                                  </div>
                                              @endif
                                          @endforeach
                                        </div>
                                    </div>
                                    <div id="user2" class="user_create_right_section col-xs-12 col-md-4">
                                        <input type="text" name="search_user2" id="search_user2" onclick="searchfilter(this, 'user2');" onkeyup="searchfilter(this, 'user2');" placeholder="{{ trans('messages.Searchuserbyname') }}" class="form-control" style="width:90% !important;" value="" />
                                        @if (!count($users))
                                            <p>{{ trans('messages.Nousers') }}</p>
                                        @else
                                            <div class="dd-right">
                                                @foreach ($users as $k => $v)
                                                   @if(in_array($k, $usersReportToThisUser))
                                                       <div class="item draggable" id="user2_{{$k}}" title="Drag and Drop to the Left Box">{{$v}}</div>
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

          <div class="row">
              <div class="col-xs-6 col-md-4">
                <a href="javascript:void(0);" class="btn btn-primary" onclick="submitEditTypeTabs();">{{ trans('messages.Submit') }}</a>
                <a href="{{ url('/type') }}" class="btn btn-default" target="_self">{{ trans('messages.Cancel') }}</a>
              </div>
          </div>

      </div>
  </form>

    <form id="typeupdate" action='{{ preg_replace("/\/edit\/.*/", "/edit", url()->current()) }}type/{{ $type->id }}' method="post">
        <input type="hidden" name="mydata" id="mydata" />
        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
    </form>
@endsection

