<?php 
namespace Core\User\Http\Controllers;
 
use Core\User\Models\User;
use Core\User\Forms\UserForm; 

class UserController extends Controller  
{   
    public function name()
    {
        return 'user';
    }
    
    public function title()
    {
        return 'user-management::title.users';
    } 

    public function model()
    {
        return new User;
    }

    public function form()
    {
        return new UserForm;
    }  
}
