@if(!Auth::guard('admin')->check())
	<?php return redirect('/panel'); ?> 
@endif
<!DOCTYPE html>

<!--[if IEMobile 7]><html class="no-js iem7 oldie"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html class="no-js ie7 oldie" lang="en"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html class="no-js ie8 oldie" lang="en"><![endif]-->
<!--[if (IE 9)&!(IEMobile)]><html class="no-js ie9" lang="en"><![endif]-->
<!--[if (gt IE 9)|(gt IEMobile 7)]><!-->
<html class="no-js" lang="{{ App::getLocale() }}"><!--<![endif]-->

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>@trans('dashboard::title.armin_cms') {{ armin_version() }}</title>
	<meta name="description" content="">
	<meta name="author" content="">

	<meta name="csrf-token" content="{{ csrf_token() }}"> 

	<!-- http://davidbcalhoun.com/2010/viewport-metatag -->
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">

	<!-- http://www.kylejlarson.com/blog/2012/iphone-5-web-design/ and http://darkforge.blogspot.fr/2010/05/customize-android-browser-scaling-with.html -->
	<meta name="viewport" content="user-scalable=0, initial-scale=1.0, target-densitydpi=115">

	<!-- For all browsers -->
	<link rel="stylesheet" href="/admin/rtl/css/reset.css?v=1">
	<link rel="stylesheet" href="/admin/rtl/css/style.css?v=1">
	<link rel="stylesheet" href="/admin/rtl/css/colors.css?v=1">
	<link rel="stylesheet" media="print" href="/admin/rtl/css/print.css?v=1">
	
	<!-- For progressively larger displays -->
	<link rel="stylesheet" media="only all and (min-width: 480px)" href="/admin/rtl/css/480.css?v=1">
	<link rel="stylesheet" media="only all and (min-width: 768px)" href="/admin/rtl/css/768.css?v=1">
	<link rel="stylesheet" media="only all and (min-width: 992px)" href="/admin/rtl/css/992.css?v=1">
	<link rel="stylesheet" media="only all and (min-width: 1200px)" href="/admin/rtl/css/1200.css?v=1">

	<!-- For Retina displays -->
	<link rel="stylesheet" media="only all and (-webkit-min-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (min-device-pixel-ratio: 1.5)" href="/admin/rtl/css/2x.css?v=1">

	<!-- Additional styles -->
	<link rel="stylesheet" href="/admin/rtl/css/styles/agenda.css?v=1">
	<link rel="stylesheet" href="/admin/rtl/css/styles/dashboard.css?v=1">
	<link rel="stylesheet" href="/admin/rtl/css/styles/form.css?v=1">
	<link rel="stylesheet" href="/admin/rtl/css/styles/modal.css?v=1">
	<link rel="stylesheet" href="/admin/rtl/css/styles/progress-slider.css?v=1">
	<link rel="stylesheet" href="/admin/rtl/css/styles/switches.css?v=1">
	<link rel="stylesheet" href="/admin/rtl/css/styles/calendars.css?v=1">
	<link rel="stylesheet" href="/admin/rtl/css/styles/table.css?v=1">
	<link rel="stylesheet" href="/admin/rtl/css/styles/files.css?v=1">

	<!-- DataTables -->
	<link rel="stylesheet" href="/admin/rtl/js/libs/DataTables/jquery.dataTables.css?v=1">

	<!-- JavaScript at bottom except for Modernizr -->
	<script src="/admin/rtl/js/libs/modernizr.custom.js"></script>
	<link rel="stylesheet" href="/admin/rtl/css/custom.css"> 

	<!-- For Modern Browsers -->
	<link rel="shortcut icon" href="/admin/rtl/img/favicons/favicon.png">
	<!-- For everything else -->
	<link rel="shortcut icon" href="/admin/rtl/img/favicons/favicon.ico">

	<!-- iOS web-app metas -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">

	<!-- iPhone ICON -->
	<link rel="apple-touch-icon" href="/admin/rtl/img/favicons/apple-touch-icon.png" sizes="57x57">
	<!-- iPad ICON -->
	<link rel="apple-touch-icon" href="/admin/rtl/img/favicons/apple-touch-icon-ipad.png" sizes="72x72">
	<!-- iPhone (Retina) ICON -->
	<link rel="apple-touch-icon" href="/admin/rtl/img/favicons/apple-touch-icon-retina.png" sizes="114x114">
	<!-- iPad (Retina) ICON -->
	<link rel="apple-touch-icon" href="/admin/rtl/img/favicons/apple-touch-icon-ipad-retina.png" sizes="144x144">


    <link rel="stylesheet" type="text/css" href="/admin/rtl/css/jquery.nestable.css"/>

	<!-- iPhone SPLASHSCREEN (320x460) -->
	<link rel="apple-touch-startup-image" href="/admin/rtl/img/splash/iphone.png" media="(device-width: 320px)">
	<!-- iPhone (Retina) SPLASHSCREEN (640x960) -->
	<link rel="apple-touch-startup-image" href="/admin/rtl/img/splash/iphone-retina.png" media="(device-width: 320px) and (-webkit-device-pixel-ratio: 2)">
	<!-- iPhone 5 SPLASHSCREEN (640x1096) -->
	<link rel="apple-touch-startup-image" href="/admin/rtl/img/splash/iphone5.png" media="(device-height: 568px) and (-webkit-device-pixel-ratio: 2)">
	<!-- iPad (portrait) SPLASHSCREEN (748x1024) -->
	<link rel="apple-touch-startup-image" href="/admin/rtl/img/splash/ipad-portrait.png" media="(device-width: 768px) and (orientation: portrait)">
	<!-- iPad (landscape) SPLASHSCREEN (768x1004) -->
	<link rel="apple-touch-startup-image" href="/admin/rtl/img/splash/ipad-landscape.png" media="(device-width: 768px) and (orientation: landscape)">
	<!-- iPad (Retina, portrait) SPLASHSCREEN (2048x1496) -->
	<link rel="apple-touch-startup-image" href="/admin/rtl/img/splash/ipad-portrait-retina.png" media="(device-width: 1536px) and (orientation: portrait) and (-webkit-min-device-pixel-ratio: 2)">
	<!-- iPad (Retina, landscape) SPLASHSCREEN (1536x2008) -->
	<link rel="apple-touch-startup-image" href="/admin/rtl/img/splash/ipad-landscape-retina.png" media="(device-width: 1536px)  and (orientation: landscape) and (-webkit-min-device-pixel-ratio: 2)">  
	<link rel="stylesheet" type="text/css" href="/admin/rtl/js/libs/formValidator/developr.validationEngine.css">
	<link rel="stylesheet" type="text/css" href="/admin/rtl/css/font-armin.css">
	
	<link rel="stylesheet" type="text/css" href="/admin/css/developer.css">
	
	@yield('links')
	@stack('links')
	<style type="text/css">
		#message{
			margin-top: 0;
			padding: 0;
		}
		.alert-modal {
			overflow: hidden;
			height: auto;
		}
		.transparent {background: transparent;}
	</style>
	<!-- Microsoft clear type rendering -->
	<meta http-equiv="cleartype" content="on">

	<!-- IE9 Pinned Sites: http://msdn.microsoft.com/en-us/library/gg131029.aspx -->
	<meta name="application-name" content="Developr Admin Skin">
	<meta name="msapplication-tooltip" content="Cross-platform admin template.">
	<meta name="msapplication-starturl" content="http://www.display-inline.fr/demo/developr">
	<!-- These custom tasks are examples, you need to edit them to show actual pages -->
	<meta name="msapplication-task" content="name=Agenda;action-uri=http://www.display-inline.fr/demo/developr/agenda.html;icon-uri=http://www.display-inline.fr/demo/developr/admin/img/favicons/favicon.ico">
	<meta name="msapplication-task" content="name=My profile;action-uri=http://www.display-inline.fr/demo/developr/profile.html;icon-uri=http://www.display-inline.fr/demo/developr/admin/img/favicons/favicon.ico">
</head>

<body  dir="rtl" class="clearfix with-menu with-shortcuts reversed">

	<!-- Prompt IE 6 users to install Chrome Frame -->
	<!--[if lt IE 7]><p class="message red-gradient simpler">Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a>or<a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->

	<!-- Title bar -->
	<header role="banner" id="title-bar">
		<h2>@trans('dashboard::title.armin_cms') - {{ armin_version() }}</h2>
	</header>

	<!-- Button to open/hide menu -->
	<a href="#" id="open-menu"><span>منو</span></a>

	<!-- Button to open/hide shortcuts -->
	<a href="#" id="open-shortcuts"><span class="icon-thumbs"></span></a>
