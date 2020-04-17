@extends('layouts.master')

@section('title', trans('messages.ReviewOthersTimesheet'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.ReviewTimesheetrules') }}</p>
@endsection

@section('content')
  <h3>New Timesheet:</h3>

  <form id="" action="" method="POST">
      <div class="container-fluid">
          <div class="row">
            <div  class="col-xs-12 col-md-6">Timesheet Type: <input type="text" name="timesheettype" id="timesheettype" class="form-control" value="" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Desc: <textarea name="desc" id="desc" class="form-control" rows="3"></textarea></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Start Time: <input type="text" name="starttime" id="starttime" class="form-control datetimepicker" value="" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">End Time: <input type="text" name="endtime" id="endtime" class="form-control datetimepicker" value="" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Created: <input type="text" name="created" id="created" class="form-control datetimepicker" value="" /></div>
          </div>

          <div class="row">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" name="submit" class="" value="Submit" />
            <a href="{{ url('/timesheet') }}" class="" target="_self">Cancel</a>
          </div>
      </div>
  </form>
@endsection

