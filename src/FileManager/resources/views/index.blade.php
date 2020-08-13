@extends('dashboard::layouts.main')

@section('title')@trans('file manager')@stop 

@section('breadcrumbs')  
	@component('dashboard::components.breadcrumbs')
		@slot('title')@trans('file manager')@endslot 
	@endcomponent 
@stop

@section('main')  
	<file-manager  
			v-bind:input-name="'file'"
			v-bind:base-url="'{{ Storage::disk('armin.file')->url('/') }}'"
			v-bind:disk="'armin.file'"
			v-bind:width="'100'" 
			v-bind:height="'100'"
			v-bind:title="'{{ armin_trans('admin-crud::title.image') }}'" 
		></file-manager> 
@stop 