@extends('layouts.master')

@section('title', trans('messages.TIMESHEET'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.TimesheetRules') }}</p>
@endsection

@section('content')
  <h3>Timesheet Detail:</h3>

    <p>This is Timesheet {{ $timesheet->name }}</p>

@endsection

