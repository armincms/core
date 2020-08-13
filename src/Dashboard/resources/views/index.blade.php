@extends('dashboard::layouts.main') 
@section('title')@trans('dashboard::title.armin_cms') {{ armin_version() }}@stop 

@section('main') 
	<div class="dashboard" style="margin: 0 -20px;">
		<div class="columns">
			<div class="two-columns twelve-columns-mobile new-row-mobile">
				@include('dashboard::inc.quick-reveiw')
			</div>
			<div class="ten-columns twelve-columns-mobile">
				@include('dashboard::inc.chart')
			</div>
		</div> 
	</div> 
	<div class="columns margin-top">

		<div class="twelve-columns">
			@include('admin-crud::include.messages')
		</div>
		
		@foreach(config('admin.panel.widgets', []) as $widget) 
			@var($widgetContent = array_get($widget, 'content')) 
			@if(is_callable($widgetContent))
				{!! $widgetContent($widget) !!}
			@elseif(view()->exists($widgetContent))
				@includeIf($widgetContent)
			@else
				{!! $widgetContent !!}
			@endif 
		@endforeach
	</div>   
@endsection 