<?php 
namespace Core\Module\Forms;
 
use Core\Crud\Forms\ResourceForm;  

abstract class InstanceForm extends ResourceForm 
{  

    protected $title = 'module::title.setting';

    protected $name = 'setting';  

    public function generalMap()
    {
    	return ['params'];
    }

    public function relationMap()
    {
    	return [];
    }   

    public function params(string $key = null, $default = null)
    {
        if($params = optional($this->getModel())->params) {
            return $params->get($key, $default);
        }

        return $default; 
    }

    public function trans(string $key)
    {
        $instance = $this->getModel()->module ?? request('instance');

        return armin_trans( module_hint_key("{$instance}::{$key}") ); 
    }
}
