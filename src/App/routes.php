<?php      

	Route::resource('app-log', 'AppLogController', ['only' => 'index']); 

	Route::resource('app-page', 'AppPageController', [
		'except' => [
			'create', 'show'
		]
	]); 
	Route::resource('app-menu', 'AppMenuController', [
		'except' => [
			'create', 'show'
		]
	]); 
	Route::resource('app-version/{os}', 'AppVersionController', [
		'except' => [
			'create'
		],
		'parameters' => [
			'{os}' => 'id'
		]
	]);  

	$menu = \Menu::get('bigMenu');
	$menu->add('armin::title.armin_app', '#!'); 

	$menu->add('armin::title.app_pages', route('app-page.index'), 'app'); 
	$menu->add('armin::title.app_menu', route('app-menu.index'), 'app'); 
	$menu->add('armin::title.app_version', '#!');
	$menu->add('armin::title.app_logs', route('app-log.index'), 'app'); 

	foreach(config('armin.app.available_os') as $os) { 
		$menu->add("armin::title.{$os}", route('{os}.index', [$os]));
	}
 