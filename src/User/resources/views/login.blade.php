<!DOCTYPE html> 

<!--[if IEMobile 7]><html class="no-js iem7 oldie linen"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html class="no-js ie7 oldie linen" lang="en"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html class="no-js ie8 oldie linen" lang="en"><![endif]-->
<!--[if (IE 9)&!(IEMobile)]><html class="no-js ie9 linen" lang="en"><![endif]-->
<!--[if (gt IE 9)|(gt IEMobile 7)]><!--><html class="no-js linen" lang="en"><!--<![endif]-->

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>ورود به بخش مدیریت</title>
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- http://davidbcalhoun.com/2010/viewport-metatag -->
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">

	<!-- http://www.kylejlarson.com/blog/2012/iphone-5-web-design/ and http://darkforge.blogspot.fr/2010/05/customize-android-browser-scaling-with.html -->
	<meta name="viewport" content="user-scalable=0, initial-scale=1.0, target-densitydpi=115">
	<!-- For all browsers -->
	<link rel="stylesheet" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/css/reset.css?v=1">
	<link rel="stylesheet" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/css/style.css?v=1">
	<link rel="stylesheet" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/css/colors.css?v=1">
	<link rel="stylesheet" media="print" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/css/print.css?v=1">
	<!-- For progressively larger displays -->
	<link rel="stylesheet" media="only all and (min-width: 480px)" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/css/480.css?v=1">
	<link rel="stylesheet" media="only all and (min-width: 768px)" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/css/768.css?v=1">
	<link rel="stylesheet" media="only all and (min-width: 992px)" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/css/992.css?v=1">
	<link rel="stylesheet" media="only all and (min-width: 1200px)" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/css/1200.css?v=1">
	<!-- For Retina displays -->
	<link rel="stylesheet" media="only all and (-webkit-min-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (min-device-pixel-ratio: 1.5)" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/css/2x.css?v=1">

	<!-- Additional styles -->
	<link rel="stylesheet" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/css/styles/form.css?v=1">
	<link rel="stylesheet" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/css/styles/switches.css?v=1">

	<!-- Login pages styles -->
	<link rel="stylesheet" media="screen" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/css/login.css?v=1">

	<!-- JavaScript at bottom except for Modernizr -->
	<script src="/admin/{{-- {{ $_locale->direction }} --}}rtl/js/libs/modernizr.custom.js"></script>

	<!-- For Modern Browsers -->
	<link rel="shortcut icon" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/img/favicons/favicon.png">
	<!-- For everything else -->
	<link rel="shortcut icon" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/img/favicons/favicon.ico">

	<!-- iOS web-app metas -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">

	<!-- iPhone ICON -->
	<link rel="apple-touch-icon" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/img/favicons/apple-touch-icon.png" sizes="57x57">
	<!-- iPad ICON -->
	<link rel="apple-touch-icon" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/img/favicons/apple-touch-icon-ipad.png" sizes="72x72">
	<!-- iPhone (Retina) ICON -->
	<link rel="apple-touch-icon" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/img/favicons/apple-touch-icon-retina.png" sizes="114x114">
	<!-- iPad (Retina) ICON -->
	<link rel="apple-touch-icon" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/img/favicons/apple-touch-icon-ipad-retina.png" sizes="144x144">

	<!-- iPhone SPLASHSCREEN (320x460) -->
	<link rel="apple-touch-startup-image" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/img/splash/iphone.png" media="(device-width: 320px)">
	<!-- iPhone (Retina) SPLASHSCREEN (640x960) -->
	<link rel="apple-touch-startup-image" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/img/splash/iphone-retina.png" media="(device-width: 320px) and (-webkit-device-pixel-ratio: 2)">
	<!-- iPhone 5 SPLASHSCREEN (640x1096) -->
	<link rel="apple-touch-startup-image" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/img/splash/iphone5.png" media="(device-height: 568px) and (-webkit-device-pixel-ratio: 2)">
	<!-- iPad (portrait) SPLASHSCREEN (748x1024) -->
	<link rel="apple-touch-startup-image" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/img/splash/ipad-portrait.png" media="(device-width: 768px) and (orientation: portrait)">
	<!-- iPad (landscape) SPLASHSCREEN (768x1004) -->
	<link rel="apple-touch-startup-image" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/img/splash/ipad-landscape.png" media="(device-width: 768px) and (orientation: landscape)">
	<!-- iPad (Retina, portrait) SPLASHSCREEN (2048x1496) -->
	<link rel="apple-touch-startup-image" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/img/splash/ipad-portrait-retina.png" media="(device-width: 1536px) and (orientation: portrait) and (-webkit-min-device-pixel-ratio: 2)">
	<!-- iPad (Retina, landscape) SPLASHSCREEN (1536x2008) -->
	<link rel="apple-touch-startup-image" href="/admin/{{-- {{ $_locale->direction }} --}}rtl/img/splash/ipad-landscape-retina.png" media="(device-width: 1536px)  and (orientation: landscape) and (-webkit-min-device-pixel-ratio: 2)">

	<!-- Microsoft clear type rendering -->
	<meta http-equiv="cleartype" content="on"> 
</head>

<body dir="rtl">

	<div id="container">

		<hgroup id="login-title" class="large-margin-bottom">
			<h1 class="login-title-image">Developr</h1>
			<h5>&copy; ورود به بخش مدیریت</h5>
		</hgroup>

		<div id="form-block" class="scratch-metal">
			<form class="input-wrapper blue-gradient glossy" id="form-login" role="form" method="POST" action="{{ route('admin.login') }}" title="Login">

				@if($errors->count())
					<p class="red big-message red-gradient">
					@foreach($errors->all() as $error)
					<strong>{{ $error }}</strong> <br>
					@endforeach
					</p>
				@endif  
				{{ csrf_field() }}
				<ul class="inputs black-input large">
					<!-- The autocomplete="off" attributes is the only way to prevent webkit browsers from filling the inputs with yellow -->
					<li> 
						@if ($errors->has('msg'))
						<span class="mid-margin-left"></span> 
						<span class="red">
							{{ $errors->first('msg') }}
						</span>
						@endif   
					</li>
					<li><span class="icon-user mid-margin-left"></span><input type="text" name="username" id="login" value="{{ old('username') }}" class="input-unstyled" placeholder="نام کاربری" autocomplete="off">
						@if ($errors->has('username'))
                        <span class="red">
                            {{ $errors->first('username') }}
                    	</span>
						@endif 
					</li>
					<li><span class="icon-lock mid-margin-left"></span><input type="password" name="password" id="pass" value="" class="input-unstyled" placeholder="رمز عبور" autocomplete="off">
						@if ($errors->has('password'))
                        <span class="red">
                            {{ $errors->first('password') }}
                    	</span>
						@endif
					</li>
				</ul>
				<h5>یکبار رمز  <small>( ویژه دارندگان يکبار رمز )</small></h5>
				<ul class="inputs black-input large">
					<li><span class="icon-key mid-margin-left"></span><input type="password" name="pass" id="pass2" value="" class="input-unstyled" placeholder="یکبار رمز خود را وارد نمایید" autocomplete="off"></li>
				</ul>
				<!--<p class="button-height security-code inputs  black-input">
					<img class="pull-right" src="img/security.jpg">
					<input type="text" name="security_code" value="" class="input-unstyled" placeholder="کد امنیتی" autocomplete="off">
				</p>-->
				<p class="button-height">
					<input type="checkbox" name="remind" id="remind" value="0" class="switch tiny mid-margin-left with-tooltip" title="فعال کردن لاگین اتوماتیک">
					<label for="remind">به خاطر بسپار</label>
					<select class="select" name="admin_language">
						<option value="0" selected>@trans('titles.default')</option>
						@foreach(language() as $language) 
						<option value="{{ $language->alias }}">
							{{$language->title}}
						</option>
						@endforeach 
					</select>
				</p>
			        <div id="form-switch" class="scratch-metal">
                                    <button type="submit" class="button green-gradient full-width" id="login">ورود</button>
			        </div>
			   </form>
			   
		</div>
                <p class="button-height margin-top align-center">
                                <a href="https://www.armindesign.com" target="_blank">
		                   <img src="http://www.armindesign.ir/images/signature.png" alt="گروه طراحی آرمین">
		                </a>
		</p>
	</div>

	<!-- JavaScript at the bottom for fast page loading -->

	<!-- Scripts -->
	<script src="/admin/{{-- {{ $_locale->direction }} --}}rtl/js/libs/jquery-1.10.2.min.js"></script>
	<script src="/admin/{{-- {{ $_locale->direction }} --}}rtl/js/setup.js"></script>

	<!-- Template functions -->
	<script src="/admin/{{-- {{ $_locale->direction }} --}}rtl/js/developr.input.js"></script>
	<script src="/admin/{{-- {{ $_locale->direction }} --}}rtl/js/developr.message.js"></script>
	<script src="/admin/{{-- {{ $_locale->direction }} --}}rtl/js/developr.notify.js"></script>
	<script src="/admin/{{-- {{ $_locale->direction }} --}}rtl/js/developr.tooltip.js"></script>

	<script>

		/*
		 * How do I hook my login script to this?
		 * --------------------------------------
		 *
		 * This script is meant to be non-obtrusive: if the user has disabled javascript or if an error occurs, the login form
		 * works fine without ajax.
		 *
		 * The only part you need to edit is the login script between the EDIT SECTION tags, which does inputs validation
		 * and send data to server. For instance, you may keep the validation and add an AJAX call to the server with the
		 * credentials, then redirect to the dashboard  ??  display an error depending on server return.
		 *
		 * Or if you don't trust AJAX calls, just remove the event.preventDefault() part and let the form be submitted.
		 */

		$(document).ready(function()
		{
			/*
			 * JS login effect
			 * This script will enable effects for the login page
			 */
				// Elements
			var doc = $('html').addClass('js-login'),
				container = $('#container'),
				formBlock = $('#form-block'),

				// If layout is centered
				centered;

			/******* EDIT THIS SECTION *******/

			/*
			 * AJAX login
			 * These functions will handle the login process through AJAX
			 */
			$('#form-login').submit(function(event)
			{
				// Values
				var login = $.trim($('#login').val()),
					pass = $.trim($('#pass').val());

				// Check inputs
				if (login.length === 0)
				{
					// Display message
					displayError('لطفا اطلاعات ورود را پر کنيد');
					return false;
				}
				else if (pass.length === 0)
				{
					// Remove empty login message if displayed
					formBlock.clearMessages('لطفا اطلاعات ورود را پر کنيد');

					// Display message
					displayError('لطفا پسورد را وارد کنيد');
					return false;
				}
				else
				{
					// Remove previous messages
					formBlock.clearMessages();

					// Show progress
					displayLoading('بررسي اطلاعات...');

					// Stop normal behavior
					//event.preventDefault();

					/*
					 * This is where you may do your AJAX call, for instance:
					 * $.ajax(url, {
					 * 		data: {
					 * 			login:	login,
					 * 			pass:	pass
					 * 		},
					 * 		success: function(data)
					 * 		{
					 * 			if (data.logged)
					 * 			{
					 * 				document.location.href = 'index.html';
					 * 			}
					 * 			else
					 * 			{
					 * 				formBlock.clearMessages();
					 * 				displayError('Invalid user/password, please try again');
					 * 			}
					 * 		},
					 * 		error: function()
					 * 		{
					 * 			formBlock.clearMessages();
					 * 			displayError('Error while contacting server, please try again');
					 * 		}
					 * });
					 */

					// Simulate server-side check
					setTimeout(function() {
						document.location.href = '/../panel'
					}, 10000);
				}
			});

			/******* END OF EDIT SECTION *******/

			// Handle resizing (mostly for debugging)
			function handleLoginResize()
			{
				// Detect mode
				centered = (container.css('position') === 'absolute');

				// Set min-height for mobile layout
				if (!centered)
				{
					container.css('margin-top', '');
				}
				else
				{
					if (parseInt(container.css('margin-top'), 10) === 0)
					{
						centerForm(false);
					}
				}
			};

			// Register and first call
			$(window).on('normalized-resize', handleLoginResize);
			handleLoginResize();

			/*
			 * Center function
			 * @param boolean animate whether or not to animate the position change
			 * @param string|element|array any jQuery selector, DOM element  ??  set of DOM elements which should be ignored
			 * @return void
			 */
			function centerForm(animate, ignore)
			{
				// If layout is centered
				if (centered)
				{
					var siblings = formBlock.siblings(),
						finalSize = formBlock.outerHeight();

					// Ignored elements
					if (ignore)
					{
						siblings = siblings.not(ignore);
					}

					// Get other elements height
					siblings.each(function(i)
					{
						finalSize += $(this).outerHeight(true);
					});

					// Setup
					container[animate ? 'animate' : 'css']({ marginTop: -Math.round(finalSize/2)+'px' });
				}
			};

			// Initial vertical adjust
			centerForm(false);

			/**
			 * Function to display error messages
			 * @param string message the error to display
			 */
			function displayError(message)
			{
				// Show message
				var message = formBlock.message(message, {
					append: false,
					arrow: 'bottom',
					classes: ['red-gradient'],
					animate: false					// We'll do animation later, we need to know the message height first
				});

				// Vertical centering (where we need the message height)
				centerForm(true, 'fast');

				// Watch for closing and show with effect
				message.on('endfade', function(event)
				{
					// This will be called once the message has faded away and is removed
					centerForm(true, message.get(0));

				}).hide().slideDown('fast');
			}

			/**
			 * Function to display loading messages
			 * @param string message the message to display
			 */
			function displayLoading(message)
			{
				// Show message
				var message = formBlock.message('<strong>'+message+'</strong>', {
					append: false,
					arrow: 'bottom',
					classes: ['blue-gradient', 'align-center'],
					stripes: true,
					darkStripes: false,
					closable: false,
					animate: false					// We'll do animation later, we need to know the message height first
				});

				// Vertical centering (where we need the message height)
				centerForm(true, 'fast');

				// Watch for closing and show with effect
				message.on('endfade', function(event)
				{
					// This will be called once the message has faded away and is removed
					centerForm(true, message.get(0));

				}).hide().slideDown('fast');
			}
		});   
	</script>

</body>
</html>
