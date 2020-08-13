<?php 

Route::apiResource('app-log', 'AppLogController');   
Route::apiResource('app-error', 'AppErrorController');   
Route::get("{os}/version", 'AppVersionController@lastVersion');   