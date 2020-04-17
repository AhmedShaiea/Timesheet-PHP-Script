@extends('layouts.master')

@section('title', trans('messages.Constant'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.Constantrules') }}</p>
@endsection

@section('content')
  <h3>{{ trans('messages.Edit') }} {{ trans('messages.Constant') }}:</h3>

  <form id="" action="" method="POST">
      <div class="container-fluid">
          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Name') }}: <input type="text" name="name" id="name" class="form-control" value="{{ $constant->name }}" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Desc') }}: <textarea name="desc" id="desc" class="form-control" rows="3">{{ $constant->description }}</textarea></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Status') }}:
                <select class="form-control" name="status" id="status">
                    <option value="0" {{ empty($constant->status) ? 'selected' : ''  }}>{{ trans('messages.No') }}</option>
                    <option value="1" {{ empty($constant->status) ? '' : 'selected'  }} >{{ trans('messages.Yes') }}</option>
                </select> 
            </div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Created') }}: <input type="text" name="created" id="created" class="form-control" value="{{ $constant->created }}" disabled /></div>
          </div>

          <div class="row">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" name="submit" class="btn btn-primary" value="{{ trans('messages.Submit') }}" />
            <a href="{{ url('/constant') }}" class="btn btn-default" target="_self">{{ trans('messages.Cancel') }}</a>
          </div>
      </div>
  </form>
@endsection

