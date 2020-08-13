@var($options = array_merge([
	'default' => null,
	'type' => 'text',
	'value' => null,
	'name' 	=> 'name',
	'placeholder' 	=> null,
	'loaded'	=> false,
	'inline' => 'inline-label',
	'label' => null,
	'label-class' => null,
	'input-label' => false, 
	'input-label-float' => 'right', 
	'input-label-color' => 'orange-gradient', 
	'wrapper-class' => null,
	'class' => null,
	'id' => null,
	'uploader' => true,
], (array) @$options))  

@if(! is_true(array_get($options, 'id')))
	@var($options['id'] = dot_to_id(array_get($options, 'name')))
@endif 
@var($options['class'] = array_get($options, 'class') . ' tinymce') 
 
<p class="button-height {{ array_get($options, 'inline') }} {{ array_get($options, 'wrapper-class') }}">
	@if(is_true(array_get($options, 'inline'))) 
	<label 
		class="label {{ array_get($options, 'label-class') }}" 
		for="{{ dot_to_id(array_get($options, 'name')) }}">
		{{ array_get($options, 'label') }}
	</label>
	@endif    
	@include('form::fields.textarea', compact('options'))  
</p>    
@var($plugins = [
	"advlist", "autolink", "autosave", "link", "lists", "charmap", "print", "preview", "hr", "anchor", 
	"pagebreak", "searchreplace", "wordcount", "visualblocks", "visualchars", "code", "fullscreen", 
	"insertdatetime", "media", "nonbreaking", "table", "contextmenu", "directionality", "emoticons", 
	"template", "textcolor", "paste", "textcolor", "colorpicker", "textpattern", "image", "imagetools "
])

@var($toolbars = collect([
	0 => [
		0 => [
			"bold", "italic", "underline", "strikethrough"
		],
		1 => [
			"alignleft", "aligncenter", "alignright", "alignjustify"
		], 
		2 => [
			"uploader", "image"
		], 
		3 => [
			"styleselect", "formatselect", "fontselect", "fontsizeselect"
		],
	],
	1 => [
		0 => [
			"cut", "copy", "paste"
		],
		1 => [
			"searchreplace"
		],
		2 => [
			"bullist", "numlist"
		],
		3 => [
			"outdent", "indent", "blockquote"
		],
		4 => [
			"undo", "redo"
		],
		5 => [
			"link", "unlink", "anchor", "code"
		],
		6 => [
			"insertdatetime", "preview"
		],
		7 => [
			"forecolor", "backcolor"
		],
	],
	2 => [
		0 => [
			"table"
		],
		1 => [
			"hr", "removeformat"
		],
		2 => [
			"subscript", "superscript"
		],
		3 => [
			"charmap", "emoticons"
		],
		4 => [
			"print", "fullscreen"
		],
		5 => [
			"ltr", "rtl"
		],
		6 => [
			"visualchars", "visualblocks", "nonbreaking", "template", "pagebreak", "restoredraft"
		]
	]
]))  
@push('scripts')  
	<script src="/{{-- {{ $_locale->direction }} --}}admin/rtl/js/tinymce/tinymce.min.js"></script>  
  	<script>  
		tinymce.init({ 
		  selector: 'textarea.tinymce', 
  			height: 500,
			setup: function (editor) {
			    editor.addButton('uploader', {
			      text: 'Uploader',
			      icon: 'upload',
			      id: 'uploader',
			      class: 'button red-gradient',
			      style: 'float:right; padding:2px 5px;',
			      subtype:'button',
			      onclick: function () {
			      	var $files = $('<input>', {'type':'file', 'multiple':'multiple'}).click();

			      	$files.change(function(event) {
			      		var files = this.files;  
						formData = new FormData();
						for (var i = 0; i < files.length; i++) { 
							formData.append('files[]', files[i]);
						}  
						formData.append('_token', '{{ csrf_token() }}');
						formData.append('path', "{{ URL::current() }}");
						formData.append('manager', "file_manager");
			      		formData.append('_token', '{{ csrf_token() }}');

			      		$.ajax({
							url: '{{ route('uploader') }}',
							type: 'post',
							dataType: 'json',
							processData: false,
							contentType: false,
							data: formData,
						})
						.done(function(imageArray) {
							for(i in imageArray)
								editor.insertContent('<img class="img-responsive armin-uploader editable-image" src="' +imageArray[i]+ '">') 
						}) 
						.fail(function(error) {
							console.log(error.responseText);
						});
						
			      	});
			      }
			    });
			},

		  	plugins: @json($plugins), 

		  	toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify |  uploader image | styleselect formatselect fontselect fontsizeselect",
		  	toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor code | insertdatetime preview | forecolor backcolor",
		  	toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft",

		  	menubar: false,

		  	toolbar_items_size: 'small',

		  	style_formats: [{
			    title: 'Bold text',
			    inline: 'b'
			  }, {
			    title: 'Red text',
			    inline: 'span',
			    styles: {
			      color: '#ff0000'
			    }
			  }, {
			    title: 'Red header',
			    block: 'h1',
			    styles: {
			      color: '#ff0000'
			    }
			  }, {
			    title: 'Example 1',
			    inline: 'span',
			    classes: 'example1'
			  }, {
			    title: 'Example 2',
			    inline: 'span',
			    classes: 'example2'
			  }, {
			    title: 'Table styles'
			  }, {
			    title: 'Table row 1',
			    selector: 'tr',
			    classes: 'tablerow1'
			  }],

		  	templates: [{
		    	title: 'Test template 1',
		    	content: 'Test 1'
		  	}, {
		    	title: 'Test template 2',
		    	content: 'Test 2'
		  	}] ,
		  	convert_urls: false,
		 });  
  	</script> 
@endpush