@extends('layouts.master')

@section('title', trans('messages.Report'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.Reportrules') }}</p>
@endsection

@section('content')
  <h3>Report Detail:</h3>

    <p>This is Report {{ $report->name }}</p>

@endsection

