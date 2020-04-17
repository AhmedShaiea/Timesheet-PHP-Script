@extends('layouts.master')

@section('title', 'Worktype')

@section('sidebar')
    @parent

    <p>Type rules: {{ UserHelpers::getConstants('TYPE_RULES') }}</p>
@endsection

@section('content')
  <h3>Worktype Detail:</h3>

    <div class="container-fluid">
      <div class="row">
        <div  class="col-xs-12 col-md-6">Name: <input type="text" name="name" id="name" class="form-control" value="{{ $worktype->name }}"  disabled /></div>
      </div>
      
      <div class="row">
        <div  class="col-xs-12 col-md-6">Desc: <textarea name="desc" id="desc" class="form-control" rows="3"  disabled>{{ $worktype->description }}</textarea></div>
      </div>

      <div class="row">
        <div  class="col-xs-12 col-md-6">Responsible Users: <textarea name="responsibleusers" id="responsibleusers" class="form-control" rows="3"  disabled>{{ $worktype->responsibleusers }}</textarea></div>
      </div>

      <div class="row">
        <div  class="col-xs-12 col-md-6">Assume Start Time: <input type="text" name="assumestarttime" id="assumestarttime" class="form-control datetimepicker" value="{{ $worktype->assumestarttime }}"  disabled /></div>
      </div>

      <div class="row">
        <div  class="col-xs-12 col-md-6">Assume End Time: <input type="text" name="assumeendtime" id="assumeendtime" class="form-control datetimepicker" value="{{ $worktype->assumeendtime }}" disabled /></div>
      </div>

      <div class="row">
        <div  class="col-xs-12 col-md-6">Actual Start Time: <input type="text" name="actualstarttime" id="actualstarttime" class="form-control datetimepicker" value="{{ $worktype->actualstarttime }}" disabled /></div>
      </div>

      <div class="row">
        <div  class="col-xs-12 col-md-6">Actual End Time: <input type="text" name="actualendtime" id="actualendtime" class="form-control datetimepicker" value="{{ $worktype->actualendtime }}" disabled /></div>
      </div>

      <div class="row">
        <div  class="col-xs-12 col-md-6">Created: <input type="text" name="created" id="created" class="form-control datetimepicker" value="{{ $worktype->created }}" /></div>
      </div>
      
  </div>

@endsection

