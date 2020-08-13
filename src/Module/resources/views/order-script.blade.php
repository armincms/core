jQuery(document).ready(function($) { 
	$('select#position').change(function(event) {
		/* Act on the event */
		let $ordering = $('select#ordering'), $position = $(this).val();

		$ordering.find('option').each(function(index, el) { 
			if($(this).data('position') == undefined || $(this).data('position') == $position) {
				$(this).removeAttr('disabled')
			} else {
				$(this).attr('disabled', 'disabled');
			}
		});

		$ordering.trigger('change').closest('span').change();
	}).change();
});