@extends('dashboard::layouts.main')  

@section('title')@trans("extension::title.installer")@stop

@section('breadcrumbs')

	@arminBreadcrumbs(['title' => trans("extension::title.extensions")])
		{{ route('extension.index') }}
	@endarminBreadcrumbs 

	@arminBreadcrumbs(['title' => trans("extension::title.installer")])@endarminBreadcrumbs 
@stop

@section('main') 
	{{-- <div class="standard-tabs margin-bottom tabs-active" id="add-tabs" style="height: 30px;"> 
		<ul class="tabs">
			<li class="active"><a href="#tab-1">بارگذاری</a></li> 
		</ul>

		<div class="tabs-content" style="min-height: 29px;">
			<span class="tabs-back with-right-arrow top-bevel-on-light dark-text-bevel">Back</span> 

			<div id="tab-1" class="with-padding tab-active columns"> 
				<div class="eight-columns"> 
					<p class="inline-label button-height"> 
						لطفا فایل فشرده افزونه های مورد نظر خود را انتخاب نموده و برای نصب بارگذاری نمایید.
						<br>
						دقت کنیدن که فایل فشرده شما باید بصورت زیر نام گذاری شده باشد.
						<br>
						<strong>extension-name.type.zip</strong>
						<br>
						<p class="inline-label button-height align-left ltr">
							<label class="red label pull-left">examples:</label>
							<p class="margin-left pull-left ltr"> 
								<b class="blue">estate.component.zip</b>
								<span class="mid-margin-left">کامپوننت</span><br> 
								<br>
								<b class="blue">localizer.package.zip</b>
								<span class="mid-margin-left">پکیج</span><br>
								<br>
								<b class="blue">slider.module.zip</b>
								<span class="mid-margin-left">ماژول</span><br>
								<br>
								<b class="blue">slick.plugin.zip</b>
								<span class="mid-margin-left">پلاگین</span><br>
								<br>
								<b class="blue">default.template.zip</b>
								<span class="mid-margin-left">قالب</span><br>
								<br>
							</p>
						</p>
							
					</p>
				</div>
				<div class="four-columns align-right"> 
					<p class="button-height"> 
						<input type="file" id="uploader" class="file" accept=".zip" multiple> 
					</p> 
					<p class="installing">
						<p id="components"></p>
						<p id="packages"></p>
						<p id="plugins"></p>
						<p id="modules"></p>
						<p id="templates"></p>
					</p>
				</div>
			</div> 
		</div> 
	</div>  --}}
@stop 
@push('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
			var extensions = {};  

			function _install(type, index) 
			{
				console.log(extensions[type], extensions, type)
			}

			$('#uploader').change(function(event) {
				/* Act on the event */
				$('.installing').html('');
				for (i=0; i<this.files.length; i++) {
					var parts = this.files[i].name.split('.');
					var name = parts[0];
					var type = parts[1];
					if(extensions[type] == undefined) {
						extensions[type] = [];
					}

					extensions[type].push(this.files[i]);

					// $('.installing').append(
					// 	$('<div>', {id:})
					// 	$('<b>', {text: this.files[i].name})
					// ).append('<br>');
				}

				_install('package', 0);
			});
		});
	</script>
@endpush