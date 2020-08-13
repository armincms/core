@extends('dashboard::layouts.main')

@section('title')@trans($title)@stop 

@section('breadcrumbs')  
	@component('dashboard::components.breadcrumbs')
		@slot('title')@trans($title)@endslot 
	@endcomponent 
@stop

@section('main') 
	@component('admin-crud::components.fixed-buttons')
		@if(Route::has("{$name}.create"))
		<a href="{{ route("{$name}.create", $routeParameters) }}" class="button with-tooltip margin-left">
			<span class="button-icon  green-gradient glossy right-side">
				<span class="icon-plus"></span>
			</span>
			@trans('admin-crud::title.new_resource', ['resource' => armin_trans($title)])
		</a>
		@endif
	@endcomponent

	@include('admin-crud::include.messages')
	
	{!! $table !!}
@stop 