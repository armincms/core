jQuery(document).ready(function() {   
	$(this).on('change', 'input[role="responsive-button"]', function(event) { 
		event.preventDefault();

		var $target = $(this).data('target');
		var $value = $(this).val(); 

		$($target).each(function(index, el) { 
			if($(this).hasClass('responsive-' + $value)) {
				$(this).show(10).trigger('shown-responsive-item');
			} else {
				$(this).hide().trigger('hide-responsive-item');
			} 
		});
	});  
	$('input[role="responsive-button"]:checked').change(); 
});  