<?php 
namespace Core\Module;

use Illuminate\Database\Eloquent\Model; 
use Illuminate\Support\Facades\Log;
use Helper;
use Exception;

use Illuminate\Database\Eloquent\SoftDeletes; 
use Core\Crud\Concerns\HasCustomImage; 
use Core\Crud\Contracts\Publicatable;
use Core\Crud\Concerns\Publishing;

class ModuleInstance extends Model implements Publicatable
{
    use SoftDeletes, HasCustomImage, Publishing;

    const SELECTION_MODE = 'selection';
    const REJECTION_MODE = 'rejection';
    
    protected $guarded = []; 

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [ 
        'setting'   => 'collection', 
        'params'    => 'collection', 
        'access'    => 'collection', 
        'config'    => 'collection',  
        'locate'    => 'collection',  
        'show_on'   => 'string', 
        'ordering'  => 'integer',  
    ];

    protected $hidden = ['config', 'instance'];  

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::saved(function($model) {
            new CssWriter(self::get());

            \Cache::forget($model->cacheKey());
        });
    }  

    public function cacheKey()
    {
        return "module{$this->getKey()}.rendered";
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new ModuleCollection($models);
    }

    public function setSettingAttribute($value)
    {
        $this->attributes['setting'] = json_encode($value);
    }    
    
    public function isActive()
    {  
        return $this->published(); 
    }

    public function locatedAt(String $group, String $item = null, String $language = '*', String $template = '*')
    {     
        if($this->show_on == '*') {
            return true;
        }  

        if(! $this->inLocale($language)) {  
            return false;
        } 

        if(! $this->inTemplate($template)) {  
            return false;
        } 

        if($group == 'language') {
            return true;
        }     

        return $this->locatedInItem($group, $item) ? $this->selectionMode() : $this->rejectionMode();
    }  
    
    public function inLocale($language)
    {   
        return $this->language == '*' || (string) $this->language == (string) $language;
    }   
    
    public function inTemplate($template)
    {   
        return $this->template == '*' || (string) $this->template == (string) $template;
    }   

    public function locatedInItem(String $group, String $item = null)
    {  
        return $this->locatedInItems($group, (array) $item);  
    }

    public function locatedInItems(String $group, array $items = null)
    {  
        return false !== collect($this->locate->get($group))->search(function($value) use ($items) { 
            return empty($items) || $value === '*' || in_array($value, $items);
        });  
    }
    
    protected function selectionMode()
    {
        return $this->inMode('selection');
    }
    
    protected function rejectionMode()
    {
        return $this->inMode('rejection');
    }

    protected function inMode(String $mode)
    {
        return $this->show_on === $mode;
    }   

    public function setOrderingAttribute($order)
    {   
        if (is_numeric($order)) { 
            $this->attributes['ordering'] = $order;
        } else if ($order == 'last') { 
            $max = (int) $this->where('position', $this->attributes['position'])->max('ordering');
            $this->attributes['ordering'] = $max + 1;
        } else if($ordering = explode('-', $order)) {  
            $current = isset($current) ? $this->attributes['id'] : null;
            $position = 0;
            $ordering = isset($ordering[1]) ? $ordering[1] : $ordering[0];
            $current_place = isset($this->attributes['ordering']) 
                                        ? (int) $this->attributes['ordering']
                                        : 99999999999;

            $operator = ($current_place <= $ordering) ? '<=' : '<';

            $befores = $this->where('position', request()->input('position'))
                                ->where('id', '!=', $current)
                                ->where('ordering', $operator, (int) $ordering)
                                ->orderBy('ordering', 'ASC')->get();

            foreach($befores as $before) {
                $before->update(['ordering' => $position++]); 
            }

            $place = $position;

            $operator = ($current_place <= $ordering) ? '>' : '>=';

            $afters = $this->where('position', request()->input('position'))
                                ->where('id', '!=', $current)
                                ->where('ordering', $operator, (int) $ordering)
                                ->orderBy('ordering', 'ASC')->get();  

            foreach($afters as $after) {
                $after->update(['ordering' => ++$position]); 
            }  
 
            $this->attributes['ordering'] = (int) $place;
 
        } 
    }

    public function getParams($key = null, $default = null)
    {
        if(is_null($key)) {
            return $this->params;
        }  

        return data_get($this, "params.{$key}", $default);
    }

    public function getConfig($key = null, $default = null)
    {
        $configKey = "_config";

        if(! is_null($key)) {
            $configKey .= ".{$key}";
        } 

        return $this->getParams($configKey, $default);
    }
}
