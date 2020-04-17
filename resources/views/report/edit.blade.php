@extends('layouts.master')

@section('title', trans('messages.Report'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.Reportrules') }}</p>
@endsection

@section('content')
  <h3>Report Edit:</h3>

  <form id="" action="" method="POST">
      <div class="container-fluid">
          <div class="row">
            <div  class="col-xs-12 col-md-6">Name: <input type="text" name="name" id="name" class="form-control" value="{{ $report->name }}" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Desc: <textarea name="desc" id="desc" class="form-control" rows="3">{{ $report->description }}</textarea></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Assume Start Time: <input type="text" name="assumestarttime" id="assumestarttime" class="form-control datetimepicker" value="{{ $report->assumestarttime }}" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Assume End Time: <input type="text" name="assumeendtime" id="assumeendtime" class="form-control datetimepicker" value="{{ $report->assumeendtime }}" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Actual Start Time: <input type="text" name="actualstarttime" id="actualstarttime" class="form-control datetimepicker" value="{{ $report->actualstarttime }}" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Actual End Time: <input type="text" name="actualendtime" id="actualendtime" class="form-control datetimepicker" value="{{ $report->actualendtime }}" /></div>
          </div>

          <div class="row">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" name="submit" class="" value="Submit" />
            <a href="{{ url('/report') }}" class="" target="_self">Cancel</a>
          </div>
      </div>
  </form>
@endsection

