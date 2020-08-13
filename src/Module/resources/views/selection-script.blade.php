jQuery(document).ready(function($) {
	var roleSelected = false;

	$('input[role=selection]').change(function(event) {
		/* Act on the event */ 
		if(false === this.checked || roleSelected) return; 

		if((this.value === 'selection' || this.value === 'rejection')) { 
			roleSelected = true; 
			
			$('div#selection').html('<hr>').removeClass('hidden');
			 
			$.ajax({
				url: '{{ route('module.selection', $module) }}',
				type: 'get',
				dataType: 'html' 
			})
			.done(function(response) {
				$('div#selection').append(response);
			})
			.fail(function() {
				$('div#selection').append("error");
			}).always(function() {
				roleSelected = false;

				$('input.locate-all').change();
			});


		} else { 
			$('div#selection').html('').addClass('hidden');
		}   
	}).change();

	$(document).on('change', 'input.locate-all', function(event) {
		event.preventDefault();
		/* Act on the event */
		$checkables = $(this).closest('ul').find('li > input:not(.locate-all)'); 

		if(this.checked) {
			$checkables.attr('disabled', 'disabled');
		} else {
			$checkables.removeAttr('disabled', 'disabled');
		} 
	});

	$('input.locate-all').change();
});