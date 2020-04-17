@extends('layouts.master')

@section('title', trans('messages.Webhook'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.Webhookrules') }}</p>
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
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

  <h3>{{ trans('messages.Webhook') }}:</h3>

  <form id="" action="" method="POST">
      <div class="container-fluid">
          <h4>{{ trans('messages.Onceatimesheetiscreated') }}:</h4>
            <div class="row">
            <div  class="col-xs-12 col-md-6">
                @if(isset($webhook['create']))
                @foreach($webhook['create'] as $item)
                <input type="text" class="form-control" name="create[]" value="{{ $item }}"  />
                <br/>
                @endforeach
                @endif
            </div>
          </div>

          <hr/>

		  <h4>{{ trans('messages.Onceatimesheetisedited') }}:</h4>
            <div class="row">
            <div  class="col-xs-12 col-md-6">
                @if(isset($webhook['edit']))
                @foreach($webhook['edit'] as $item)
                <input type="text" class="form-control" name="edit[]" value="{{ $item }}"  />
                <br/>
                @endforeach
                @endif
            </div>
          </div>

          <hr/>
		  
          <h4>{{ trans('messages.Onceatimesheetisreviewedbyamanager') }}:</h4>
            <div class="row">
            <div  class="col-xs-12 col-md-6">
                @if(isset($webhook['review']))
                @foreach($webhook['review'] as $item)
                <input type="text" class="form-control" name="review[]" value="{{ $item }}" />
                <br/>
                @endforeach
                @endif
            </div>
          </div>

          <hr/>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Status') }}:
                <select class="form-control" name="status" id="status">
                    <option value="0" {{ empty($webhook['status']) ? 'selected' : ''  }}>{{ trans('messages.No') }}</option>
                    <option value="1" {{ empty($webhook['status']) ? '' : 'selected'  }} >{{ trans('messages.Yes') }}</option>
                </select>
            </div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Created') }}: <input type="text" name="created" id="created" class="form-control datetimepicker" value="{{ $webhook['created'] }}" disabled /></div>
          </div>
		  @if(UserHelpers::isAdmin())
          <div class="row">
              <div class="col-xs-6 col-md-4">
                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                <input type="submit" class="btn btn-primary" name="webhooksubmit" value="{{ trans('messages.Submit') }}" />
                <a href="{{ url('/') }}" class="btn btn-default" target="_self">{{ trans('messages.Cancel') }}</a>
              </div>
          </div>
		  @endif
          
      </div>
  </form>

@endsection

