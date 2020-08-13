jQuery(document).ready(function($) {
	$(this).on('change', '[role=theme-facility]', function(event) { 
		/* Act on the event */
		$('[role=background]:not(.hidden)').addClass('hidden');
		if(this.value) { 
			$('[role=background][data-' +this.value+ ']').removeClass('hidden');
		}
		if(this.value === 'image' || this.value === 'slide' || this.value === 'pattern' ) {  
			var $slide = this.value === 'slide';
			var $pattern = this.value === 'pattern';

			setTimeout(function() { 
				$('select[role=gradient]').change();
				if(! $pattern) { 
					$('[role=animation]').each(function() {
						if($slide && this.value === 'smooth') {
							$(this).attr('disabled', 'disabled').removeAttr('checked')
									.closest('label').addClass('hidden');
						} else {
							$(this).removeAttr('disabled').closest('label').removeClass('hidden');
						}
					}).change().closest('label').change();
				}
			}, 500, $pattern, $slide);  
		}
		 
	});
	$('select[role=theme-facility]').change();
	$(this).on('change', 'select[role=gradient]', function(event) { 
		/* Act on the event */  
		if(this.value === 'color') { 
			$('[role=background][data-gradient]').addClass('hidden'); 
			$('[role=background][data-color]').removeClass('hidden'); 
		} else if(this.value === 'gradient') {
			$('[role=background][data-gradient]').removeClass('hidden'); 
		} else { 
			$('[role=background][data-gradient]').addClass('hidden'); 
		}
	}); 
	$('input[role=animation]').change(function(event) {
		/* Act on the event */  
		if(this.checked && this.value === 'smooth') { 
			$('[role=background][data-animation]').removeClass('hidden');  
		} else if(this.checked) {
			$('[role=background][data-animation]').addClass('hidden');  
		}
	}); 
});