<?php  
namespace Core\User\Models;  

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements CanResetPasswordContract
{   
	use CanResetPassword;
}
