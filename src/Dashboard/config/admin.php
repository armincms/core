<?php 
return [
	'panel' => [
 		// admin login path
		'path_prefix' => 'panel',  
		// admin shortcuts menu
		'shortcuts'	=> [
			'dashboard' => [
				'title' => 'dashboard::title.dashboard', 
				'route' => 'panel',
				'class' => 'shortcut-dashboard', 
				'order' => 0,
			]
		],
		// admin profile menu bar
		'admin_bar' => [
			'view-site' => [
				'title' => 'dashboard::title.view_site',
				'icon'	=> 'icon-eye',
				'url' 	=> url('/'),
				'order' => 0,
			], 			'report' => [
				'title' => 'report',
				'icon'	=> 'icon-megaphone',  
				'order' => 2,
			], 
		],
		// admin profile menu bar
		'quick_review' => [
			'titles.hits' => 250,  
		],
		// admin profile menu bar
		'widgets' => [
			'view-site' => [
				'title' => 'dashboard::title.view_site',
				'icon'	=> 'icon-eye',
				'url' 	=> url('/'),
				'order' => 0,
			],  
		],
		'monthly_hits' => [ 
		]
	],
];