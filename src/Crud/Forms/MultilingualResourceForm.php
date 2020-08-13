<?php 
namespace Core\Crud\Forms;

use Annisa\Form\AnnisaBuilder; 
use Component\ContentManagement\Content;
use Illuminate\Support\HtmlString;
use Core\Crud\Contracts\Titled; 
use Core\Crud\Concerns\HasTitle; 
use Closure;


abstract class MultilingualResourceForm extends ResourceForm
{    

	protected function toDictionary($data)
	{
		return collect($this->dataMap())->map(function($keys, $group) use ($data) {
			$values = []; 

			foreach ($keys as $key => $value) { 
				if(is_numeric($key)) {
					$key = $value;
				}

				if($group == 'general') {  
					$values[$key] = data_get($data, $key);
				} else if ($group === 'translates') {  
					foreach ($this->languages as $language) {
						$values[$language->alias][$key] = data_get(
							$data, "{$key}.{$language->alias}"
						); 
					} 
				} else { 
					if(is_array($value)) {
						$inputKey = reset($value);
						$method   = key($value);
					} else {
						$method  = $value;
						$inputKey= $key;
					} 

					$values[$key][$method] = data_get($data, $inputKey);
				} 
			} 

			return collect($values);
		}); 
	}

	/**
	 * Getting input from request. 
	 * 
	 * @return Illuminate\Support\Collection
	 */
	public function getInput($key, $default = null)
	{    
		if($this->isTranslatableField($key)) {
			return $this->retrieveTranslatableInput($key, $default);
		} 

		return parent::getInput($key, $default);  
	} 

	protected function isTranslatableField($name)
	{
		$row = (array) $this->rows($name)->first();

		if(! starts_with(array_get($row, 'type', ''), $this->component)) { 
			return false;
		} 
		
		return (boolean) array_get($row, 2, true); 
	}

	protected function retrieveTranslatableInput($key, $default = null)
	{ 
		$inputs = []; 

		$key = $this->getDottedKey($key); 


		foreach ($this->languages as $language) {
			$inputs[$language->alias] = parent::getInput(
				"{$language->alias}.{$key}"
			);
		}

		return $inputs; 
	}


	protected function dataMap() { 
		return [
			'general' 	=> $this->generalMap(),
			'relations' => $this->relationMap(),
			'translates'=> $this->translateMap(),
		];
	} 
 
	abstract public function translateMap();
}
