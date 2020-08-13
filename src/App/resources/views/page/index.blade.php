@extends('dashboard::layouts.main')

@section('title')
	@if(isset($page))
		@trans('actions.editing', ['content' => $page->title])
	@else
		@trans('app::title.app_pages')
	@endif
	
@stop

@section('breadcrumbs')
	@arminBreadcrumbs(['title' => trans("app::title.app_pages")]) 
	@endarminBreadcrumbs
@stop

@section('main')   
	<div class="columns">
		<div class="four-columns twelve-columns-tablet">
			<form method="post" action="@if(isset($page)){{ route('app-page.update', [$page->id]) }}@else{{ route('app-page.store') }}@endif" enctype="multipart/form-data">

				{!! csrf_field() !!}
				@isset($page){!! method_field('put') !!}@endif

				<p class="block-label button-height mid-margin-top">
					<label for="page-title" class="label red">@trans('titles.title')</label>
					<input type="text" id="page-title" class="input full-width" name="title" value="{{ old('title', optional($page)->title) }}" required autofocus>
				</p> 

				<p class="block-label button-height mid-margin-top">
					<label for="page-title" class="label">@trans('titles.image')</label> 
					<input type="file" class="file full-width" name="image_file">
					<input type="hidden"  id="page-image" name="image[path]" value="{{ array_get($page, 'image.path') }}">
				</p> 

 				@var($image = array_get($page, 'image.path'))
 				@if(Storage::disk('armin.image')->has($image))
				<div class="input pull-left block-label with-small-padding" style="position: relative;"> 
	 				<img src="{{ Storage::disk('armin.image')->url($image) }}" height="100" class="pull-left">
	 				<i class="button icon-trash red-gradient tiny remove-image" style="position: absolute;"></i>
				</div>
				<br><br>
 				@endif 
				@push('scripts')
				<script type="text/javascript">
					$(document).ready(function() { 
						$('.remove-image').click(function(event) {
							/* Act on the event */
							$(this).parent().hide();
							$('#page-image').val('');
						});

						$('span.file').find('span.button').click(function(event) {
							/* Act on the event */
							$(this).siblings('input[type=file]').click();
						});
					});
				</script>
				@endpush

				@var($position = old('image.position', array_get($page, 'image.position', 'top')))
				<p class="block-label button-height mid-margin-top">
					<label for="page-title" class="label">موقعیت تصویر</label>  
					<select name="image[position]" id="" class="select">
						<option value="top" {{ $position == 'top' ? 'selected' : '' }}>بالا</option>
						<option value="bottom" {{ $position == 'bottom' ? 'selected' : '' }}>پایین</option>
					</select>   
				</p> 

				<p class="block-label button-height mid-margin-top">
					<label for="page-text" class="label red">متن</label>
					<textarea id="page-text" name="full_text" class="input full-width">{{ old('full_text', optional($page)->full_text) }}</textarea> 
				</p> 

				<p class="button-height align-left margin-top">
					<button type="submit" name="next_action" value="save" class="button compact">
						<span class="button-icon green-gradient glossy">
							<span class="icon-ok"></span>
						</span>
						@trans('actions.save')
					</button>
					<button type="submit" name="next_action" value="save&new" class="button compact">
						<span class="button-icon orange-gradient glossy">
							<span class="icon-ok"></span>
						</span>
						@trans('actions.save&new')
					</button>  
					<a href="{{ route('app-page.index') }}" class="button compact">
						<span class="button-icon red-gradient glossy"><span class="icon-cancel"></span></span>
						@trans('actions.cancell')
					</a>
				</p> 
			</form>
		</div>
		<div class="eight-columns twelve-columns-tablet margin-top">
			<table class="table responsive-table" id="sorting-advanced">
				<thead>
					<tr> 
						<th scope="col" width="25%">
							@trans('titles.title')
						</th> 
						<th class="hidden"></th>
						<th class="hidden"></th>
						<th class="hidden"></th>
						<th scope="col" width="20%" class="align-center hide-on-tablet">
							@trans('titles.date')
						</th>
						<th scope="col" width="15%" class="align-center">
							@trans('titles.actions')
						</th>
					</tr>
				</thead>   
				<tbody>	    
					@foreach($pages as $item)
					<tr class="{{ $item->id == optional($page)->id ? 'blue' : '' }}">     
						<td class="vertical-center">
							<strong> {{ Helper::WordCount($item->title, 10) }} </strong> 
						</td>   
						<td class="hidden"></td>
						<td class="hidden"></td>
						<td class="hidden"></td> 
						 
						<td class="align-center vertical-center">@format($item->created_at, '[ h:m:s ] [ M-Y-d ]') </td>
						<td class="align-center vertical-center "> 
							<form class="button-group" action="{{ route("app-page.destroy", $item->id) }}" method="post">
								{{ csrf_field() }} {{ method_field('delete') }} 

								<a href="{{ route("app-page.edit", [$item->id]) }}" class="button blue-gradient glossy icon-pencil with-tooltip {{ $item->id == optional($page)->id ? 'hidden' : '' }}" title="@trans('actions.edit')"></a>

								<button type="submit" class="button red-gradient glossy icon-trash with-tooltip confirm {{ $item->id == optional($page)->id ? 'hidden' : '' }}" title="@trans('actions.delete')" ></button>
							</form>
						</td>
					</tr> 
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@stop
@push('scripts')

@include('backend.includes.table-script')
	<script type="text/javascript" src="/admin/rtl/js/tinymce/tinymce.min.js"></script>
	<script type="text/javascript">
		tinymce.init({ 
		  	selector: 'textarea#page-text', 
		  	plugins: "advlist autosave lists charmap preview hr anchor textcolor colorpicker textpattern",
		  	toolbar1: "bold italic bullist numlist",
	  		toolbar2: "",
	  		toolbar3: "", 
	  		menubar: false,
		  	toolbar_items_size: 'small',
  			height: 300,
			setup: function (editor) {
			    editor.addButton('uploader', {
			      text: 'Add Media',
			      icon: 'image',
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
		});
	</script>
@endpush