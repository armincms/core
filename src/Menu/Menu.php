<?php  
namespace Core\Menu; 

use Illuminate\Database\Eloquent\Model; 
use Core\Crud\Contracts\Publicatable;
use Core\Crud\Concerns\Publishing;


class Menu extends Model implements Publicatable
{
    use Publishing;

    public $timestamps = false;

    protected $guarded = [];
    protected $publishStatus = 'activated';

    protected $casts = [
        'params' => 'collection'
    ]; 


    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot() { 
        parent::boot();

        static::deleting(function($menu) { 
            $menu->items()->get()->map->delete(); 
        }); 
    }

    public function parent()
    {
    	return $this->belongsTo($this);
    }

    public function setParamsAttribute($params)
    {
        if(! is_array($params) && !json_decode($params)) {
            $params = [];
        }  

        $this->attributes['params'] = json_encode((array) $params);
    }

    public function site()
    {
        return $this->belongsTo(\Core\HttpSite\Site::class);
    }

    public function items()
    {
        return $this->hasMany(MenuItem::class)->whereNull('menu_item_id')->with('childs')->orderBy('level');
    } 
}
