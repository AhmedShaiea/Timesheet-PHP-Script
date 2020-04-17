@extends('layouts.master')

@section('title', trans('messages.Role'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.Rolerules') }}</p>
@endsection

@section('content')
  <h3>{{ trans('messages.Edit') }} {{ trans('messages.Role') }}:</h3>

  <form id="" action="" method="POST">
      <div class="container-fluid">
          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Name') }}: <input type="text" name="name" id="name" class="form-control" value="{{ $role->name }}" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Desc') }}: <textarea name="description" id="description" class="form-control" rows="3">{{ $role->description }}</textarea></div>
          </div>

          <div class="row">
              <div  class="col-xs-12 col-md-6">{{ trans('messages.Status') }}:
                <select class="form-control" name="status" id="status">
                     <option value="0" {{ empty($role->status) ? 'selected' : '' }}>{{ trans('messages.NotActive') }}</option>
                     <option value="1" {{ empty($role->status) ? '' : 'selected' }}>{{ trans('messages.Active') }}</option>
                </select> 
            </div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Created') }}: <input type="text" name="created" id="created" class="form-control" value="{{ $role->created }}" disabled /></div>
          </div>

          <div class="row">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" name="submit" class="btn btn-primary" value="{{ trans('messages.Submit') }}" />
            <a href="{{ url('/role') }}" class="btn btn-default" target="_self">{{ trans('messages.Cancel') }}</a>
          </div>
      </div>
  </form>
@endsection

