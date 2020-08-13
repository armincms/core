<?php 
namespace Core\User\Http\Controllers;
 
use Core\User\Models\Admin;
use Core\User\Forms\AdminForm;  

class AdminController extends Controller  
{   
    
    public function name()
    {
        return 'admin';
    }

    public function title()
    {
        return 'user-management::title.admins';
    } 

    public function model()
    {
        return new Admin;
    } 

    public function form()
    {
        return new AdminForm;
    }  
    
    public function routes($router)
    {
        $router->get('{admin}/profile', 'AdminController@edit')->name('profile'); 
    }
}
