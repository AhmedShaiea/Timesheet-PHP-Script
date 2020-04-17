@extends('layouts.master')

@section('title', 'Error')

@section('sidebar')
    @parent

    <p></p>
@endsection

@section('content')

        <div class="alert alert-danger bold">
            Sorry, we couldn't find that page. Please go to <a href="{{ URL::to('/') }}" target="_self">homepage</a>.
        </div>

@endsection

