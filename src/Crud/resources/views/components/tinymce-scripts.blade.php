@push('links')
<style type="text/css">
	.mce-fullscreen{top: 35px !important; z-index: 999999 !important} 
</style>
@endpush
jQuery(document).ready(function() {   
	$('[data-tinymce]').each(function() {
		var $options = $(this).data('tinymce');
		var $id = '#' + $(this).attr('id');
		$options.selector = $id;  
		$options.fullscreen_settings = {
		    theme_advanced_path_location : "bottom"
		}
		$options.images_upload_url = "{{ route('file-manager.store') }}";
		$options.images_upload_base_path = "/";
		$options.images_upload_handler = function(blobInfo, success, failure) {  
		    formData = new FormData();
		    formData.append('files[0]', blobInfo.blob());
			formData.append('disk', 'armin.image'); 

			axios.post("{{ route('file-manager.store') }}", formData, {
                headers: {
                  'Content-Type': 'multipart/form-data'
                }
            }).then(response => {  
                for(var i=0; i < response.data.contents.length; i++) {
                	var img = response.data.contents[i];
					success(img.url)
            	} 
            }).catch(error => {
				failure('upload failed');
            }); 		
		};

		$options.setup  = function (editor) {
			$('#' + editor.id).on('isInvisible', function() { 
				editor.hide();
			}).on('isVisible', function() {
				editor.show();
			}); 
		},

		tinymce.init($options);    
	});  
}); 