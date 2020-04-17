@extends('layouts.master')

@section('title', 'Role')

@section('sidebar')
    @parent

    <p>Role rules: {{ UserHelpers::getConstants('ROLE_RULES') }}</p>
@endsection

@section('content')
  <h3>Role Detail:</h3>

    <p>This is Role {{ $role->name }}</p>

@endsection

