@extends('installer::base')
@section('main') 
<form method="post" data-wizard-locked="false" class="block wizard same-height"> 

	{!! csrf_field() !!}
	<h3 class="block-title">نصب خودکار آرمین</h3>
	@var($step = (int) request('step', 1)) 
	<fieldset data-step="1" class="wizard-fieldset fields-list @if(Request::segment(2) === 'configuration') current active @endif">
		<legend class="legend">خوش آمدید</legend> 
		@yield('step1') 
	</fieldset>  
	<fieldset data-step="2" 
			data-href="{{ route('installer.configuration') }}"
			class="wizard-fieldset fields-list @if(Request::segment(2) === 'migrate') current active @endif">
		<legend class="legend">نصب پایگاه داده</legend> 
		@yield('step2')  
	</fieldset>
	<fieldset data-step="3" data-href="{{ route('installer.migrate') }}" 
		class="wizard-fieldset fields-list @if(Request::segment(2) === 'dbseed') current active @endif">   
		<legend class="legend">درون ریزی پایگاه داده</legend> 
		@yield('step3') 
	</fieldset>  
	<fieldset data-step="4" data-href="{{ route('installer.dbseed') }}" 
		class="wizard-fieldset fields-list @if(Request::segment(2)=='login') current active @endif"> 
		<legend class="legend">ورود</legend>    
		@yield('step4')
	</fieldset>  
	@if(isset($errors) && $errors->count())
	<div class="field-block align-left orange" dir="ltr"> 
		<label class="label red">errors</label>
		@foreach($errors->all() as $error)
			 <pre>{{ $error }}</pre>
		@endforeach 
	</div> 
	@endif
</form>

@stop
@push('scripts')

 
<script>

	$(document).ready(function()
	{
			// Elements
		var form = $('.wizard'),

			// If layout is centered
			centered;

		// Handle resizing (mostly for debugging)
		function handleWizardResize()
		{
			centerWizard(false);
		};

		// Register and first call
		$(window).on('normalized-resize', handleWizardResize);

		/*
		 * Center function
		 * @param boolean animate whether or not to animate the position change
		 * @return void
		 */
		function centerWizard(animate)
		{	
			var margin = Math.round(($.template.viewportHeight-30-form.outerHeight())/2);

			form[animate ? 'animate' : 'css']({ 
				marginTop: Math.max(0, margin)+'px' 
			});
		};

		// Initial vertical adjust
		centerWizard(false);

		// Refresh position on change step
		form.on('wizardchange', function() { centerWizard(true); });
 
			$('.wizard-next').removeClass('float-right').addClass('float-left').html('ادامه');
			$('.wizard-prev').removeClass('float-left').addClass('float-right').html('بازگشت');

			$(document).on('click', '.wizard-steps, .wizard-next', function () {
				// $('form').attr('data-wizard-locked', 'true')
				var step = $(this).hasClass('wizrad-steps') 
					? $(this) 
					: $(this).closest('.wizrad-steps');
				
			});

			form.on('wizardleave', function (event) {

				event.preventDefault();

				var target 	= $(event.wizard.target),
				 	current	= $(event.wizard.current),
					href 	= $(target).data('href'); 

				if(target.data('step') < current.data('step')) {
					window.location = current.data('href');
				} else { 
					$(form).attr('action', href); 
					$(form).submit();
				} 

				console.log(href)
				return false;
			});
	});   
</script> 
@endpush