<?php 
namespace Core\Documentation;

use Illuminate\Support\ServiceProvider;
use Config;

class DocumentationServiceProvider extends ServiceProvider
{
     
    /**
     * Define your route model bindings, pattern filters, etc.
     * 
     * @return void
     */
    public function boot()
    {       
        \Route::group([
            'prefix'     => '/panel/docs',
            'namespace'  => __NAMESPACE__.'\Http\Controllers',
            'as'         => 'larecipe.',
            'middleware' => ['web', 'auth:admin']
        ], function () {
            \Route::get('/', 'DocumentationController@index')->name('index');
            \Route::get('/{version}/{page?}', 'DocumentationController@show')->where('page', '(.*)')->name('show');
        }); 

    }

    public function register()
    {
		Config::set('larecipe.docs.route', '/panel/docs');    	
		Config::set('larecipe.docs.path', '/core/src/Documentation/resources/views');    	
		Config::set('larecipe.repository', []); 
    }
}
