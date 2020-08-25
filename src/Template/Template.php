<?php 
namespace Core\Template;

use Illuminate\Database\Eloquent\Model; 
use Illuminate\View\View;
use Core\Layout\Layout; 
use Core\Layout\StyleSheet; 

class Template extends Layout  
{
    public $id; 

    protected $setting = [];

    public function setBody(String $content = null)
    {
        $this->body = $content;
        
        return $this;
    }

    public function setModules($modules)
    {
        $this->modules = $modules;

        return $this;
    }  

    public function getModules()
    {
        return collect($this->modules);
    } 

    public function css() : StyleSheet
    {
        return new TemplateCss($this->name);
    }

    public function display(array $data = [], array $config = [])
    { 
        $viewName = "{$this->name()}::{$this->name()}";

        return tap(view('template::front.base'), function($view) use ($data, $config) {
           $view->getEngine()->mergeData($data)->mergeConfig($config); 
        });    
    }  

    public function resolveRouteBinding()
    { 
        return $this->name();
    } 

    public function isDefault()
    {
        return default_template() === $this->name();
    }


    public function plugins()
    {  
        $plugins = option("_{$this->name()}_plugins", collect())->mapWithKeys(function($plugin) {
            return [$plugin => '*'];
        })->all();

        return array_merge($plugins, (array) parent::plugins());
    }

    public function toHtml()
    {
        return $this->display();
    }

    public function setting($key = null, $default = null)
    {
        if(is_null($key)) {
            return $this->setting;
        }
        
        return data_get($this->setting, $key, $default);
    }

    public function setSetting(array $setting = [])
    {
        $this->setting = $setting;

        return $this;
    }

    public function __toString()
    {
        return $this->name();
    }
}
