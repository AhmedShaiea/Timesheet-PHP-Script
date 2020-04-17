@extends('layouts.master')

@section('title', trans('messages.ReviewOthersTimesheet'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.ReviewTimesheetrules') }}</p>
@endsection

@section('content')
  <h3>Timesheet Detail:</h3>

    <p>This is Timesheet {{ $timesheet->name }}</p>

@endsection

