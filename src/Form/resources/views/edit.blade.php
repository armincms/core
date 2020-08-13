@extends('dashboard::layouts.main')  
@section('main')
<div class="columns">  
	{!! Form::open(['url' => 'formable.index', 'method' => 'post']) !!}




	{!! Form::close() !!} 
</div>
@stop