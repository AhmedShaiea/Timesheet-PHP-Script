@extends('layouts.master')

@section('title', 'Edit Access')

@section('sidebar')
    @parent

    <p>Access Rules: {{ UserHelpers::getConstants('ACCESS_RULES') }}</p>
@endsection

@section('content')
  <h3>Access Edit:</h3>

  <form id="" action="" method="POST">
      <div class="container-fluid">
          <div class="row">
            <div  class="col-xs-12 col-md-6">Name: <input type="text" name="name" id="name" class="form-control" value="{{ $access->name }}" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Desc: <textarea name="desc" id="desc" class="form-control" rows="3">{{ $access->description }}</textarea></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Created: <input type="text" name="created" id="created" class="form-control datetimepicker" value="{{ $access->created }}" /></div>
          </div>

          <div class="row">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" name="submit" class="" value="Submit" />
            <a href="{{ url('/access') }}" class="" target="_self">Cancel</a>
          </div>
      </div>
  </form>
@endsection

