@extends('layouts.master')

@section('title', trans('messages.ReviewOthersTimesheet'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.ReviewTimesheetrules') }}</p>
@endsection

@section('content')
  <h3>Timesheet Review:</h3>

  <form id="" action="" method="POST">
      <div class="container-fluid">
          <div class="row">
            <div  class="col-xs-12 col-md-6">Name: <input type="text" name="name" id="name" class="form-control" value="{{ $timesheet->name }}"  disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Desc: <textarea name="desc" id="desc" class="form-control" rows="3" disabled >{{ $timesheet->description }}</textarea></div>
          </div>

          <div class="row">
              <div  class="col-xs-12 col-md-6">Approved:
                <select class="form-control" name="approved" id="approved">
                     <option value="0" {{ empty($timesheet->approved) ? 'selected' : '' }}>Not Approved</option>
                     <option value="1" {{ empty($timesheet->approved) ? '' : 'selected' }}>Approved</option>
                </select> 
            </div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Review Notes: <input type="text" name="reviewnotes" id="reviewnotes" class="form-control" value="{{ $timesheet->reviewnotes }}" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Approved Time: <input type="text" name="approvedtime" id="approvedtime" class="form-control" value="{{ $timesheet->approvedtime }}"  disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Status: <input type="text" name="status" id="status" class="form-control" value="{{ empty($timesheet->status) ? 'Not Active' : 'Active' }}" disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Created: <input type="text" name="created" id="created" class="form-control" value="{{ $timesheet->created }}"  disabled /></div>
          </div>

          <div class="row">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" name="submit" class="btn btn-primary"  value="Submit" />
            <a href="{{ url('/reviewtimesheet') }}" class="btn btn-default"  target="_self">Cancel</a>
          </div>
      </div>
  </form>
@endsection

