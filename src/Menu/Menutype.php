<?php 
namespace Core\Menu; 
use Illuminate\Database\Eloquent\Model;
use Core\Log\Loggable;

class Menutype extends Model
{
	use Loggable;

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
    	'status' => 'boolean'
    ]; 

    function menus()
    {
    	return $this->hasMany(Menu::class);
    }
}
