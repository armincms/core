@extends('dashboard::layouts.main')

@section('title')
	@if($resource = $form->getModel())
		@trans("admin-crud::title.editing_resource", ['id' => $resource->id])
	@else
		@trans($title)
	@endif 
@stop 

@section('breadcrumbs')  
	@component('dashboard::components.breadcrumbs')
		@slot('title')@trans($title)@endslot 
	@endcomponent 
@stop

@section('main') 
	<div class="columns">
		<div class="twelve-columns"> 
			@include('admin-crud::include.messages')
		</div> 

		<div class="three-columns twelve-columns-tablet">   
			{!! 
				$form->open([
					'route' => array_merge(
						[$resource? "{$name}.update" : "{$name}.store"], $routeParameters ?? []
					),
					'method'=> $resource ? 'put' : 'post',
					'files'  => true
				])
			!!} 
			{!! $form !!}
			<hr>
			<span class="button-group compact pull-left">
				{!! $actions->map->toInlineHtml()->implode('') !!}  
			</span> 
			{!! $form->close() !!}
		</div>
		<div class="nine-columns twelve-columns-tablet">{!! $table !!}</div>
	</div> 
	
@stop 

@push('links') 
	{!! $form->styles() !!}
	{!! $form->childs()->map->styles()->implode('') !!}
@endpush

@push('scripts')
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(this).on('mouseover', '[role=translatable-field]', function(event) {
			$(this).addClass('syncing')
		});
		$(this).on('change', '[role=translatable-field]', function(event) {
			/* Act on the event */ 
			var $lang = this.value; 
			var $wrap = $(this).closest('p');
			var $targets = ['input', 'textarea', 'label.button', 'span.input-unstyled'];
			$wrap.find($targets.join(',')).each(function() {
				if($trans = $(this).data('translatable')) {  
					if($trans == $lang) {
						$(this).trigger('isVisible').show();
						
						$wrap.find('label.translatable-label').attr(
							'for', $(this).attr('id')
						);
					} else {
						$(this).trigger('isInvisible').hide();
					} 
				}

			}); 
			if($(this).closest('.select-cloned').length > 0) { 
				$('[role=translatable-field]').filter(function() {
					return $(this).closest('.select-cloned').length === 0;
				}).val($lang).trigger('change')
					.closest('span').trigger('change');
			}
			
		});
		$('[role=translatable-field]').change();
	});
</script> 
{!! $form->scripts() !!}
{!! $form->childs()->map->scripts()->implode('') !!} 
@endpush