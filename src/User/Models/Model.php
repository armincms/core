<?php  
namespace Core\User\Models; 

use Core\User\Concerns\FluentAccess;
use Illuminate\Database\Eloquent\SoftDeletes; 
use Illuminate\Database\Eloquent\Model as LaravelModel;
use Core\Crud\Contracts\Publicatable;
use Core\Crud\Concerns\Publishing; 
use Kodeine\Metable\Metable;
use Illuminate\Contracts\Auth\Authenticatable as Auth;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Zareismail\NovaPolicy\Concerns\InteractsWithPolicy; 
use Illuminate\Notifications\Notifiable;

class Model extends LaravelModel implements Publicatable, Auth 
{ 
	use SoftDeletes, Publishing, Metable, FluentAccess, Authenticatable, Authorizable; 
	use InteractsWithPolicy, Notifiable;
 
	protected $guarded = [];
	protected $casts = []; 
 

    public function getImageAttribute()
    {
    	return $this->getImages('avatar')->first();
    }  

	public function isDeveloper()
	{
		return $this->username === 'superadministrator';
	}
}
