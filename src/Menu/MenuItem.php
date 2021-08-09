<?php  
namespace Core\Menu; 
use Illuminate\Database\Eloquent\Model; 

class MenuItem extends Model
{ 
    public $timestamps = false;

    protected $guarded = [];

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

        static::deleting(function($item) { 
            $item->childs()->get()->map->delete();
        }); 
    }

    public function childs()
    {
        return $this->hasMany($this)->with('childs')->orderBy('level');
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

    public function setUrlAttribute($url)
    { 
        $this->attributes['url'] = urlencode($url);
    }

    public function url($decode = true)
    {  
        return $decode ? urldecode($this->url) : $this->url;
    }
}
