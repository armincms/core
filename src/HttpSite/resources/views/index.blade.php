@extends('dashboard::layouts.main')  

@section('title')@trans("armin::title.doamins")@stop

@section('breadcrumbs')
	@component('dashboard::components.breadcrumbs')
		@slot('title')@trans("armin::title.doamins")@endslot 
	@endcomponent 
@stop

@section('main')  
<div class="columns">
	<div class="four-columns">
		{!! 
			$form->open([
				'method' => $form->getModel()? 'put':'post', 
				'route' => $form->getModel()? ['domain.update', $form->getModel()]:[
					'domain.store'
				], 
			]) 
		!!}
		{!! $form !!}
		<span class="button-group compact pull-left">
			<button class="button glossy icon-floppy green-gradient" name="next_action" value="save"></button>
			<button class="button glossy icon-floppy orange-gradient"><span class="icon-plus"></span></button>
			<a href="{{ route('domain.index') }}" class="button glossy red-gradient icon-cancel"></a>
		</span>
		{!! $form->close() !!}
	</div>
	<div class="eight-columns">{!! $table !!}</div>
</div>
@stop