@extends('layouts.master')

@section('title', 'Create Access')

@section('sidebar')
    @parent

    <p>Access Rules: {{ UserHelpers::getConstants('ACCESS_RULES') }}</p>
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
  <h3>New Access Rule:</h3>

  <form id="" action="" method="POST">
      <div class="container-fluid">

            <div class="row form-inline">
                <div class="col-xs-12">
                    <div class="form-group loading">
                        <span>Target:</span>
                        <select class="form-control" onchange="" style="display:inline-block;" name="target" id="target">
                                  <option value="0" >Choose one...</option>
                          @foreach(json_decode(UserHelpers::getConstants('ACCESS_PAGES_ARRAY'), true) as $key => $val )
                              @if(!in_array(strtolower($key), $targets))
                                  <option value="{{$key}}" >{{$key}}</option>
                              @endif
                          @endforeach
                        </select>
                    </div>
                </div>
            </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Desc: <textarea name="desc" id="desc" class="form-control" rows="3"></textarea></div>
          </div>

            <div class="row form-inline">
                <div class="col-xs-12">
                    <div id="tabs0">
                        <ul>
                            <li><a href="#tabs0-1">Division Access</a></li>
                            <li><a href="#tabs0-2">Role Access</a></li>
                            <li><a href="#tabs0-3">User Access</a></li>
                        </ul>
                        <div id="tabs0-1">
                            <div id="tabs">
                              <ul>
                                @for($i=0; $i < count($divisions); $i++)
                                <li><a href="#tabs-{{$i+1}}">{{$divisions[$i]['name']}}</a></li>
                                @endfor
                              </ul>
                              @for($i=0; $i < count($divisions); $i++)
                              <div id="tabs-{{$i+1}}">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped">
                                        <tr>
                                        <th>Read</th><th>Create</th><th>Edit</th><th>Delete</th>
                                        <th>Search</th>
                                        </tr>
                <!--        accessid(in the access table, primary key) | tab index | access read/edit/delete/search | access read/edit/delete/search value   
                  
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

                                        <tr>
                                        <td><input type="checkbox" class="tabs_division_access" name="{{$divisions[$i]['id'] . '|read'}}" id="{{$divisions[$i]['id'] . '|read'}}"  /></td>
                                        <td><input type="checkbox" class="tabs_division_access" name="{{$divisions[$i]['id'] . '|create'}}" id="{{$divisions[$i]['id'] . '|create'}}"  /></td>
                                        <td><input type="checkbox" class="tabs_division_access" name="{{$divisions[$i]['id'] . '|edit'}}" id="{{$divisions[$i]['id'] . '|edit'}}"  /></td>
                                        <td><input type="checkbox" class="tabs_division_access" name="{{$divisions[$i]['id'] . '|delete'}}" id="{{$divisions[$i]['id'] . '|delete'}}"  /></td>
                                        <td><input type="checkbox" class="tabs_division_access" name="{{$divisions[$i]['id'] . '|search'}}" id="{{$divisions[$i]['id'] . '|search'}}"  /></td>
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
                                  <div id="tabs_role_access_1" class="dd-left">
                                  </div>
                                </div>

                                <div id="role" class="role_create_right_section col-xs-12 col-md-4">
                                    <input type="text" name="search_role" id="search_role" onclick="searchfilter(this, 'role');" onkeyup="searchfilter(this, 'role');" placeholder="Search role by name" class="form-control" value="" />
                                    @if (!count($roles))
                                        <p>No roles</p>
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
                            <div id="tabs_user_access">
                              <ul>
                                <li><a href="#tabs_employee_access">Employee</a></li>
                                <li><a href="#tabs_exceptionemployee_access">Exception Employee</a></li>
                              </ul>
                              <div id="tabs_employee_access" class="container">
                                <div  class="row">
                                    <div id="container_employee_access" class="col-xs-12 col-md-8">
                                        <div id="tabs_employee_access_1" class="dd-left">
                                          @php
                                              $usersReportToThisUser = UserHelpers::getAssociatesForManager(UserHelpers::getUID());
                                          @endphp
                                        </div>
                                    </div>
                                    <div id="user" class="user_create_right_section col-xs-12 col-md-4">
                                        <input type="text" name="search_user" id="search_user" onclick="searchfilter(this, 'user');" onkeyup="searchfilter(this, 'user');" placeholder="Search user by name" class="form-control" value="" />
                                        @if (!count($users))
                                            <p>No users</p>
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
                              <div id="tabs_exceptionemployee_access" class="container">
                                <div  class="row">
                                    <div id="container_exceptionemployee_access" class="col-xs-12 col-md-8">
                                        <div id="tabs_exceptionemployee_access_2" class="dd-left">
                                        </div>
                                    </div>
                                    <div id="user2" class="user_create_right_section col-xs-12 col-md-4">
                                        <input type="text" name="search_user2" id="search_user2" onclick="searchfilter(this, 'user2');" onkeyup="searchfilter(this, 'user2');" placeholder="Search user by name" class="form-control" value="" />
                                        @if (!count($users))
                                            <p>No users</p>
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
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col-xs-6 col-md-4">
                <a href="javascript:void(0);" class="btn btn-primary" onclick="submitCreateAccessTabs();">Submit</a>
                <a href="{{ url('/access') }}" class="btn btn-default" target="_self">Cancel</a>
            </div>
          </div>
      </div>
  </form>

      <form id="accessupdate" action="{{url()->current()}}Access" method="post">
        <input type="hidden" name="mydata" id="mydata" />
        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
    </form>
@endsection

