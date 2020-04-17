@extends('layouts.master')

@section('title', trans('messages.TypeCategory'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.TypeCategoryrules') }}</p>
@endsection

@section('content')
  <h3>{{ trans('messages.Edit') }} {{ trans('messages.User') }}:</h3>
  
  <form id="" action="" method="POST">
      <div class="container-fluid">
          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Name') }}: <input type="text" name="name" id="name" class="form-control" value="{{ $typecategory->name }}" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Desc') }}: <textarea name="desc" id="desc" class="form-control" rows="3">{{ $typecategory->description }}</textarea></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Created') }}: <input type="text" name="created" id="created" class="form-control" value="{{ $typecategory->created }}" disabled /></div>
          </div>

          <div class="row">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" name="submit" class="btn btn-primary" value="{{ trans('messages.Submit') }}" />
            <a href="{{ url('/typecategory') }}" class="btn btn-default" target="_self">{{ trans('messages.Cancel') }}</a>
          </div>
      </div>
  </form>
@endsection

