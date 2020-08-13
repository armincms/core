jQuery(document).ready(function($) {
	$('input#maintenance-on').change(function(event) {

		$.ajax({
			url: "{{ route('maintenance') }}",
			type: 'POST',
			dataType: 'json',
			data: {maintenance: (this.checked? 1 : 0), '_token': "{{ csrf_token() }}"},
		})
		.done(function() {
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});

	});
}); 