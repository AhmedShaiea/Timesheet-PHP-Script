@extends('layouts.master')

@section('title', 'Access')

@section('sidebar')
    @parent

    <p>Access Rules: {{ UserHelpers::getConstants('ACCESS_RULES') }}</p>
@endsection

@section('content')
  <h3>Access Detail:</h3>

        <div class="container-fluid">
          <div class="row">
            <div  class="col-xs-12 col-md-6">Name: <input type="text" name="name" id="name" class="form-control" value="{{ $access->name }}"  disabled /></div>
          </div>
          
          <div class="row">
            <div  class="col-xs-12 col-md-6">Desc: <textarea name="desc" id="desc" class="form-control" rows="3"  disabled>{{ $access->description }}</textarea></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Created: <input type="text" name="created" id="created" class="form-control datetimepicker" value="{{ $access->created }}" /></div>
          </div>

      </div>

@endsection

