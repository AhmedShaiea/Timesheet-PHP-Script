@extends('layouts.master')

@section('title', trans('messages.User'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.Userrules') }}</p>
@endsection

@section('content')
  <h3>User Detail:</h3>

      <div class="container-fluid">
          <div class="row">
            <div  class="col-xs-12 col-md-6">User Name: <input type="text" name="username" id="username" class="form-control" value="{{  $user->username }}" disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Password: <input type="password" name="password" id="password" class="form-control" value="{{  $user->password }}" disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">First Name: <input type="text" name="first_name" id="first_name" class="form-control" value="{{  $user->first_name }}" disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Last Name: <input type="text" name="last_name" id="last_name" class="form-control" value="{{  $user->last_name }}" disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Phone: <input type="text" name="phone" id="phone" class="form-control" value="{{  $user->phone }}" disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Address: <input type="text" name="address" id="address" class="form-control" value="{{  $user->address }}" disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Address2: <input type="text" name="address2" id="address2" class="form-control" value="{{  $user->address2 }}" disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">City: <input type="text" name="city" id="city" class="form-control" value="{{  $user->city }}" disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Province: <input type="text" name="province" id="province" class="form-control" value="{{  $user->province }}" disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Country: <input type="text" name="country" id="country" class="form-control" value="{{  $user->country }}" disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Postal Code: <input type="text" name="zip" id="zip" class="form-control" value="{{  $user->zip }}" disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Email: <input type="text" name="email" id="email" class="form-control" value="{{  $user->email }}" disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Picture: <input type="file" name="picture" id="picture" value="{{  $user->picture }}" disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Desc: <textarea name="desc" id="desc" class="form-control" rows="3" disabled >{{  $user->description }}</textarea></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Department ID: <input type="text" name="departmentid" id="departmentid" class="form-control" value="{{  $user->departmentid }}" disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Group ID: <input type="text" name="groupid" id="groupid" class="form-control" value="{{  $user->groupid }}" disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Token: <input type="text" name="token" id="token" class="form-control" value="{{  $user->token }}" disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Token Valid To: <input type="text" name="token_valid_to" id="token_valid_to" class="form-control datetimepicker" value="{{  $user->token_valid_to }}" disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Created: <input type="text" name="created" id="created" class="form-control datetimepicker" value="{{  $user->created }}" disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">Last Updated: <input type="text" name="last_update" id="last_update" class="form-control datetimepicker" value="{{  $user->last_update }}" disabled /></div>
          </div>

      </div>

@endsection

