<?php 

namespace Core\Menu; 
use Illuminate\Database\Eloquent\Model; 

class Menutype extends Model
{ 
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
