@extends('layouts.master')

@section('title', 'Department')

@section('sidebar')
    @parent

    <p>Division rules: {{ UserHelpers::getConstants('DIVISION_RULES') }}</p>
@endsection

@section('content')
  <h3>Department Detail:</h3>

    <p>This is Department {{ $department->name }}</p>

@endsection
