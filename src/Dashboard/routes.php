<?php 
Route::get('panel', [
	'uses' => 'DashboardController@index',
	'as' 	=> 'panel'
]);

Route::resource(config('admin.panel.path_prefix', 'panel').'/dashboard', "DashboardController"); 