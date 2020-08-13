<?php 
return [ 
	/**
	 * Available fonts
	 */
	'fonts' => [
		[
			"font-type"	=> "google",
			"title"     => "Verdana, Geneva, sans-serif",
			"name"      => "Verdana, Geneva, sans-serif",
			"font"      => "Verdana, Geneva, sans-serif",
		],
		[
			"font-type" => "google",
			"title"     => "Georgia, Times New Roman, Times, serif",
			"name"      => "Georgia, Times New Roman, Times, serif",
			"font"      => "Georgia, Times New Roman, Times, serif",
		],
		[
			"font-type" => "google",
			"title"     => "Arial, Helvetica, sans-serif",
			"name"      => "Arial, Helvetica, sans-serif",
			"font"      => "Arial, Helvetica, sans-serif",
		],
		[
			"font-type" => "google",
			"title"     => "Impact, Arial, Helvetica, sans-serif",
			"name"      => "Impact, Arial, Helvetica, sans-serif",
			"font"      => "Impact, Arial, Helvetica, sans-serif",
		],
		[
			"font-type" => "google",
			"title"     => "Tahoma, Geneva, sans-serif",
			"name"      => "Tahoma, Geneva, sans-serif",
			"font"      => "Tahoma, Geneva, sans-serif",
		],
		[
			"font-type" => "google",
			"title"     => "Trebuchet MS, Arial, Helvetica, sans-serif",
			"name"      => "Trebuchet MS, Arial, Helvetica, sans-serif",
			"font"      => "Trebuchet MS, Arial, Helvetica, sans-serif",
		],
		[
			"font-type" => "google",
			"title"     => "Arial Black, Gadget, sans-serif",
			"name"      => "Arial Black, Gadget, sans-serif",
			"font"      => "Arial Black, Gadget, sans-serif",
		],
		[
			"font-type" => "google",
			"title"     => "Times New Roman, Times, serif",
			"name"      => "Times New Roman, Times, serif",
			"font"      => "Times New Roman, Times, serif",
		],
		[
			"font-type" => "google",
			"title"     => "Palatino Linotype, Book Antiqua, Palatino, serif",
			"name"      => "Palatino Linotype, Book Antiqua, Palatino, serif",
			"font"      => "Palatino Linotype, Book Antiqua, Palatino, serif",
		],
		[
			"font-type" => "google",
			"title"     => "Lucida Sans Unicode, Lucida Grande, sans-serif",
			"name"      => "Lucida Sans Unicode, Lucida Grande, sans-serif",
			"font"      => "Lucida Sans Unicode, Lucida Grande, sans-serif",
		],
		[
			"font-type" => "google",
			"title"     => "MS Serif, New York, serif",
			"name"      => "MS Serif, New York, serif",
			"font"      => "MS Serif, New York, serif",
		],
		[
			"font-type" => "google",
			"title"     => "Comic Sans MS, cursive",
			"name"      => "Comic Sans MS, cursive",
			"font"      => "Comic Sans MS, cursive",
		],
		[
			"font-type" => "google",
			"title"     => "Courier New, Courier, monospace",
			"name"      => "Courier New, Courier, monospace",
			"font"      => "Courier New, Courier, monospace",
		],
		[
			"font-type" => "google",
			"title"     => "Lucida Console, Monaco, monospace",
			"name"      => "Lucida Console, Monaco, monospace",
			"font"      => "Lucida Console, Monaco, monospace",
		]
	],
	/**
	 * Available background colors
	 */
	'colors' => [
		'blue' => [
			'name' 	=> 'blue',
			'code'	=> '#0000FF',
			'rgb'	=> '0,0,255',
			'title'	=> 'blue',
		],
		'red' => [
			'name' 	=> 'red',
			'code'	=> '#FF0000',
			'rgb'	=> '255,0,0',
			'title'	=> 'red',
		],
		'green' => [
			'name' 	=> 'green',
			'code'	=> '#008000',
			'rgb'	=> '0,128,0',
			'title'	=> 'green',
		],
	],
	/**
	 * Available background patterns
	 */
	'patterns' => [
		'simple' => [
			'src'	=> '#!',
			'name'  => 'simple',	
			'title' => 'simple',	
		]
	],
	/**
	 * Available background images
	 */
	'images' => [
		'simple' => [
			'src'	=> '#!',
			'name'  => 'simple',	
			'title' => 'simple',	
		]
	],
	/**
	 * Available background slides
	 */
	'slides' => [
		'simple' => [
			'images'=> [
				'#!', '#!'
			], 
			'name'  => 'simple',	
			'title' => 'simple',	
		]
	],

	/**
	 * Positive size.
	 */
	'responsive' => [
		'mp' => [
			'name' => 'mobile-portrait',
			'min-size' => '0',
			'max-size' => '480',
		],
		'ml' => [
			'name' => 'mobile-landscape', 
			'min-size' => '481', 
			'max-size' => '768', 
		],
		'tp' => [
			'name' => 'tablet-portrait',
			'min-size' => '769',
			'max-size' => '1024',
		],
		'tl' => [
			'name' => 'tablet-landscape',
			'min-size' => '1025',
			'max-size' => '1280',
		],
		'dl' => [
			'name' => 'notebook',  
			'min-size' => '1281',  
			'max-size' => '1366',  
		],
		'dx' => [
			'name' => 'desktop',
			'min-size' => '1366',
			'max-size' => '*',
		],
	],

	/**
	 * Path of template assets.
	 */
	'asset_path' =>	'templates',

	/**
	 * Positive template positions.
	 */
	'position' => [
		'header1' 	=> [ 
			'name' 		=> 'header1',
			'id'		=> 'header1',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '0',
		],
		'header2' 	=> [ 
			'name' 		=> 'header2',
			'id'		=> 'header2',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '1',
		],
		'header3' 	=> [ 
			'name' 		=> 'header3',
			'id'		=> 'header3',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '2',
		], 
		'menu' 	=> [ 
			'name' 		=> 'menu',
			'id'		=> 'menu',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '3',
		],  
		'top1' 	=> [ 
			'name' 		=> 'top1',
			'id'		=> 'top1',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '4',
		], 
		'top2' 	=> [ 
			'name' 		=> 'top2',
			'id'		=> 'top2',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '5',
		], 
		'top3' 	=> [ 
			'name' 		=> 'top3',
			'id'		=> 'top3',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '6',
		], 
		'top4' 	=> [ 
			'name' 		=> 'top4',
			'id'		=> 'top4',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '7',
		], 
		'top5' 	=> [ 
			'name' 		=> 'top5',
			'id'		=> 'top5',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '8',
		], 
		'top6' 	=> [ 
			'name' 		=> 'top6',
			'id'		=> 'top6',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '9',
		], 
		'top7' 	=> [ 
			'name' 		=> 'top7',
			'id'		=> 'top7',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '10',
		], 
		'top8' 	=> [ 
			'name' 		=> 'top8',
			'id'		=> 'top8',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '11',
		], 
		'top9' 	=> [ 
			'name' 		=> 'top9',
			'id'		=> 'top9',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '12',
		], 
		'top10' 	=> [ 
			'name' 		=> 'top10',
			'id'		=> 'top10',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '13',
		],  
		'rightside' 	=> [ 
			'name' 		=> 'rightside',
			'id'		=> 'rightside',
			'parent'	=> 'middle',
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '14',
		],  
		'middle' 	=> [ 
			'name' 		=> 'middle',
			'id'		=> 'middle',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> true,
			'priority'	=> '15',
		],
		'breadcrumb' 	=> [ 
			'name' 		=> 'breadcrumb',
			'id'		=> 'breadcrumb',
			'parent'	=> 'middle',
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '16',
		],    
		'mainheader' 	=> [ 
			'name' 		=> 'mainheader',
			'id'		=> 'mainheader',
			'parent'	=> 'middle',
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '17',
		],   
		'main' 	=> [ 
			'name' 		=> 'main',
			'id'		=> 'main',
			'parent'	=> 'middle',
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '18',
		],    
		'mainfooter' 	=> [ 
			'name' 		=> 'mainfooter',
			'id'		=> 'mainfooter',
			'parent'	=> 'middle',
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '19',
		],     
		'leftside' 	=> [ 
			'name' 		=> 'leftside',
			'id'		=> 'leftside',
			'parent'	=> 'middle',
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '20',
		],  
		'footer1' 	=> [ 
			'name' 		=> 'footer1',
			'id'		=> 'footer1',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '21',
		], 
		'footer2' 	=> [ 
			'name' 		=> 'footer2',
			'id'		=> 'footer2',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '22',
		], 
		'footer3' 	=> [ 
			'name' 		=> 'footer3',
			'id'		=> 'footer3',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '23',
		], 
		'footer4' 	=> [ 
			'name' 		=> 'footer4',
			'id'		=> 'footer4',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '24',
		], 
		'footer5' 	=> [ 
			'name' 		=> 'footer5',
			'id'		=> 'footer5',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '25',
		], 
		'footer6' 	=> [ 
			'name' 		=> 'footer6',
			'id'		=> 'footer6',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '26',
		], 
		'footer7' 	=> [ 
			'name' 		=> 'footer7',
			'id'		=> 'footer7',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '27',
		], 
		'footer8' 	=> [ 
			'name' 		=> 'footer8',
			'id'		=> 'footer8',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '28',
		], 
		'footer9' 	=> [ 
			'name' 		=> 'footer9',
			'id'		=> 'footer9',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '29',
		], 
		'footer10' 	=> [ 
			'name' 		=> 'footer10',
			'id'		=> 'footer10',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '30',
		], 
		'copyright' 	=> [ 
			'name' 		=> 'copyright',
			'id'		=> 'copyright',
			'parent'	=> null,
			'active'	=> true,
			'hidden'	=> false,
			'priority'	=> '31',
		],        
    ],
];