@extends('layouts.master')

@section('title', trans('messages.Report'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.Reportrules') }}</p>
@endsection

@section('content')
  <h3>New Report:</h3>

  <form id="" action="" method="POST">
      <div class="container-fluid">
          <div class="row">
            <div  class="col-xs-12 col-md-6">Name: <input type="text" name="name" id="name" class="form-control" value="" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Desc: <textarea name="desc" id="desc" class="form-control" rows="3"></textarea></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Created: <input type="text" name="created" id="created" class="form-control datetimepicker" value="" /></div>
          </div>

          <div class="row">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" name="submit" class="" value="Submit" />
            <a href="{{ url('/report') }}" class="" target="_self">Cancel</a>
          </div>
      </div>
  </form>
@endsection

