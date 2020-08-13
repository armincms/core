	<div class="remodal alert-modal" data-remodal-id="modal-confirm" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
		<button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
		<div class="remodal-body">
			<h2 id="modal1Title">@trans('error.have content')</h2>
			<div id="modal1Desc" style="text-align: center"> 
				<button class="button huge green-gradient" data-remodal-action="confirm">@trans('actions.accept')</button>
				<button class="button huge red-gradient" data-remodal-action="close">@trans('actions.cancel')</button>  	
			</div>
		</div>
	</div>

	<div class="remodal alert-modal" data-remodal-id="modal-confirm" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
		<button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
		<div class="remodal-body">
			<h2 id="modal1Title">@trans('error.have content')</h2>
			<div id="modal1Desc" style="text-align: center"> 
				<button class="button huge green-gradient" data-remodal-action="close">@trans('actions.accept')</button> 	
			</div>
		</div>
	</div>

	<div class="remodal alert-modal" data-remodal-id="modal-delete" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
		<button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
		<div class="remodal-body">
			<h2 id="modal1Title">@trans('message.confirm_delete')</h2>
			<div id="modal1Desc" style="text-align: center"> 
				<button class="button huge green-gradient" data-remodal-action="confirm">@trans('actions.accept')</button>
				<button class="button huge red-gradient" data-remodal-action="close">@trans('actions.cancel')</button>  	
			</div>
		</div>
	</div>

	<!-- End sidebar/drop-down menu -->
	<div class="remodal alert-modal transparent" id="loading-modal" data-remodal-id="loading-modal" role="dialog" aria-labelledby="modal1Title" aria-describedby="waiting foe response">
		<div class="remodal-body">
			<div id="modal1Desc">
				<div style="text-align:center">
					<div class="loadingbox">
						<div></div>
						<span></span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- End sidebar/drop-down menu -->
	<div class="remodal alert-modal transparent" id="waiting-modal" role="dialog" 
			data-remodal-id="waiting-modal" aria-labelledby="modal1Title" 
			aria-describedby="modal1Desc">
		<div class="remodal-body">
			<div id="modal1Desc">
				<div style="text-align:center">
					<div class="loadingbox"><div></div><span></span></div>
				</div>
			</div>
		</div>
	</div>

	<!-- JavaScript at the bottom for fast page loading -->
	<!-- Scripts -->
	<script src="/admin/rtl/js/libs/jquery-1.10.2.min.js"></script>

	<script type="text/javascript">  
		function run_auto_width() {
			$('[role=auto-width]').each(function() {
				var $offset = 0,
					$parent = $(this).parent(),
					$width  = $parent.width();
				$(this).siblings().each(function() {
					if($(this).attr('role') !== 'auto-width') {
						$offset += $(this).outerWidth()
					} 
					
				});  

				$offset += $width / 10;

				$(this).width($width - $offset) 
			});
		} 
		run_auto_width(); 
	</script>

	<script type="text/javascript" src="/admin/js/developer.js"></script>
	<script src="/admin/rtl/js/setup.js"></script>
	<script src="/admin/rtl/js/remodal.js"></script>
	<!-- Template functions -->
	<script src="/admin/rtl/js/developr.input.js"></script>
	<script src="/admin/rtl/js/developr.message.js"></script>

	<script src="/admin/rtl/js/developr.navigable.js"></script>
	<script src="/admin/rtl/js/developr.notify.js"></script>
	<script src="/admin/rtl/js/developr.scroll.js"></script>
	<script src="/admin/rtl/js/developr.progress-slider.js"></script>
	<script src="/admin/rtl/js/developr.tooltip.js"></script>
	<script src="/admin/rtl/js/developr.confirm.js"></script>
	<script src="/admin/rtl/js/developr.agenda.js"></script>
	<script src="/admin/rtl/js/developr.tabs.js"></script>

	<script src="/admin/rtl/js/developr.calendar.js"></script> 
	<!-- Must be loaded last -->
	<!-- Tinycon -->
	<script src="/admin/rtl/js/libs/tinycon.min.js"></script>
	<script src="/admin/rtl/js/libs/jquery.details.min.js"></script> 
	<script src="/admin/rtl/js/tree-menu.js"></script> 
	<script src="/admin/rtl/js/custom.js"></script> 
	<script src="/admin/rtl/js/developr.accordions.js"></script>
	<script src="/admin/rtl/js/developr.auto-resizing.js"></script>

	<!-- jQuery Form Validation -->
	<script src="/admin/rtl/js/libs/formValidator/jquery.validationEngine.js?v=1"></script>
	<script src="/admin/rtl/js/libs/formValidator/languages/jquery.validationEngine-{{ App::getLocale() }}.js?v=1"></script>
	<script src="/admin/rtl/js/armin-plugins.js"></script>
	<script type="text/javascript">$.template.init()</script>
	@yield('scripts')
	@stack('scripts')

	
	<script type="text/javascript"> 
		// Form validation
		$('form').validationEngine();
		
		var pageContentChanged = false;

		$('input,select').change(function () {
			pageContentChanged =true; 
		}); 

		$('a.change').on('click', function(e){   
			href = $(this).attr('href'); 

			if (pageContentChanged) { 
				location.hash = '#modal-confirm';

				$(document).on('confirmation', '.remodal', function () {
					location.href = href;
				});  

				return false;
			} 

			return true; 
		}); 
 		
		var messagesTimeout = null;

 		function clearMessages(messgae = null) { 

 			messagesTimeout = setTimeout(function () {
 				messgae.fadeOut(1000).html(''); 
 			}, 4000);
 		}
 		
 		$(document).on('change', '#message .big-message.response', function () {  
 			// body...
 			clearMessages($(this)); 
 		}); 

 		clearMessages($('#message'));
 		clearMessages($('.response'));


		$(document).ready(function () {
			$.fn.arminUploader.settings = {
				// Mode
				multiple: true,
				// vlidation mimes,
				mimes: [],
				// ajax configuration
				ajax: {
					// upload path
					url: '', //{ route('admin.uploader') }
					// ajax type
					type: 'post',
					// data type
					dataType: 'json',
				},  
				// passed data to server
				data: {'_token':'{{ csrf_token() }}'},
				// uploaded files
				uploaded: [],
				// failed upload files
				failed: []
			},

			$("input[type='file']").arminUploader();
		});
 		
	</script>
	<script type="text/javascript">
		$(document).ready(function($) { 
			$('select[role=translatable]').change(function(event) {
				var trans = $(this).find('option:selected').attr('data-value');

				$('.translatable').each(function() {  
					if(! $(this).hasClass('trans-' +trans)) {
						$(this).hide();
					} else {
						$(this).show();
					}  
				}); 
			}).change();
		});
	</script>
 

</body>

</html>
  