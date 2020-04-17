@extends('layouts.master')

@section('title', trans('messages.Constant'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.Constantrules') }}</p>
@endsection

@section('javascript')
<script>
  master_totalpagenumber = {{$totalpagenumber}};
  $(document).ready(function(){
      master_createPagination({{ $totalpagenumber }}, {{ $topagenumber }});
  });
</script>
@endsection

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

        <div class="row form-inline">
        <div class="col-xs-12">
            <div class="form-group loading">
                <span>{{ trans('messages.ConstantList') }}: <a href="{{ url('/constant/create') }}" class="btn btn-success">{{ trans('messages.Create') }}</a></span>
                <select class="form-control" onchange="master_showResultOnReloadedPage('constant');" style="display:inline-block;" name="master_amountperpage" id="master_amountperpage">
                  @foreach (json_decode(UserHelpers::getConstants('ITEMS_PER_PAGE_ARRAY'), true) as $key => $val )
                  <option value="{{$key}}" @if($rowperpage == $val) selected @endif>{{$val}}</option>
                  @endforeach
                </select> 
                <label>{{ trans('messages.itemsperpage') }}</label>
                <img class="loading" src="/images/loading.gif" alt="loading" style="width:20px;height:20px;">
            </div>
        </div>
    </div>
    <div style="clear:both;"></div><br/>
    <div class="row master_pwrapper">
        <div class="col-xs-6 master_mypagination" style="display: inline-block;">
        </div>
    </div><br/>
    <div style="clear:both;"></div>
  @if (!count($constants))
      <p>{{ trans('messages.Noconstants') }}</p>
  @else
<div class="table-responsive" id="master_show_result">
        <table class="table table-bordered table-hover table-striped">
        <tr>  <!-- ['ID'=>'id', 'Name'=>'name', 'Desc'=>'description', 'Active'=>'active', 'Created'=>'created', ''=>'']  -->
            @foreach (json_decode(UserHelpers::getConstants('HEADER_CONSTANT_ARRAY'), true) as $key => $val )
            <th class="{{ $val }}">
				@if(!empty($key))
					{!! trans("messages.$key") !!}&nbsp;&nbsp;&nbsp;
				@endif
                @if($val === $sortby && $order === 'asc')
                    <i class="fa fa-sort-asc"></i>
                @elseif($val === $sortby && $order === 'desc')
                    <i class="fa fa-sort-desc"></i>
                @elseif($val !== '')
                    <i class="fa fa-sort"></i>
                @endif
            </th>
            @endforeach
        </tr>

      @foreach ($constants as $p)
        <tr>
			<th><a href="{{ url('/constant/edit/' . $p->id) }}" class="btn btn-primary">{{ trans('messages.Edit') }}</a>
			    <!-- <a href="javascript:void(0);" onclick="master_delete(this, {{$p->id}}, 'constant');" class="btn btn-danger">Delete</a> -->
			</th>
			<th>{{$p->id}}</th>
			<td>{{$p->name}}</td>
			<td>{{$p->description}}</td>
			<td>{{empty($p->status) ? trans('messages.NotActive') : trans('messages.Active')}}</td>
			<td>{{$p->created}}</td>
        </tr>
      @endforeach
        </table>
</div>

  @endif
    <div style="clear:both;"></div>
    <div class="row master_pwrapper">
        <div class="col-xs-6 master_mypagination" style="display: inline-block;">
        </div>
    </div>
    <div style="clear:both;"></div>

@endsection

