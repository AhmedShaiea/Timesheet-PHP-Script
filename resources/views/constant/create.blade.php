@extends('layouts.master')

@section('title', trans('messages.Constant'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.Constantrules') }}</p>
@endsection

@section('content')
  <h3>{{ trans('messages.NewConstant') }}:</h3>

  <form id="" action="" method="POST">
      <div class="container-fluid">
          <div class="row">
            <div class="col-xs-12 col-md-6">{{ trans('messages.Name') }}: <input type="text" name="name" id="name" class="form-control" value="" /></div>
          </div>

          <div class="row">
            <div class="col-xs-12 col-md-6">{{ trans('messages.Desc') }}: <textarea name="desc" id="desc" class="form-control" rows="3"></textarea></div>
          </div>

          <div class="row">
            <div class="col-xs-12 col-md-6">{{ trans('messages.Status') }}:
                <select class="form-control" name="status" id="status">
                    <option value="0" >{{ trans('messages.NotActive') }}</option>
                    <option value="1" selected >{{ trans('messages.Active') }}</option>
                </select>
            </div>
          </div>

          <div class="row">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" name="submit" class="btn btn-primary" value="{{ trans('messages.Submit') }}" />
            <a href="{{ url('/constant') }}" class="btn btn-default" target="_self">{{ trans('messages.Cancel') }}</a>
          </div>
      </div>
  </form>
@endsection

