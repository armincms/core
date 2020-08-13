<?php 
namespace Core\Form; 

use Annisa\Form\AnnisaBuilder; 
use Component\ContentManagement\Content;
use Illuminate\Support\HtmlString;
use Core\Form\Contracts\Titled; 
use Core\Form\Concerns\HasTitle; 
use Closure;


abstract class Builder extends AnnisaBuilder 
{   
 
	protected $title;
	protected $scripts = [];   

	public function __construct($callback = null)
	{
		parent::__construct($callback);

		$this->component = 'bs';	
	}

	/**
	 * Appending a form element.
	 * 
	 * @param  string $type 
	 * @param  string $name   
	 * @param  ...   
	 * @return Form\Builder
	 */
	public function element(string $type, string $name)
	{   
		$args = (array) array_except(func_get_args(), [0,1]);   
		$key  = $name;
		$name = $this->appendPrefix($name);

		$this->rows->put($key, compact('type', 'name') + $args);

		return $this; 
	} 

	public function raw(string $raw)
	{
		$name = $raw. $this->rows->count();

		$this->rows->put($name, [
			'html' => new HtmlString($raw),
			'name' => $name
		]);

		return $this;
	}  


	/**
	 * Appending or Getting child form.
	 * 
	 * @param  string  $name   
	 * @param  Closure|null $callback form build callback
	 * 
	 * @return Form\Builder
	 */
	public function setChild(string $name, Closure $callback)
	{    
		$child = $this->toBase($callback)
						->name($name)
						->parent($this)
						->setModel($this->model);

		$this->childs->put($name, $child);

		return $this; 
	} 

	/**
	 * Converting row to html.
	 * 
	 * @param	array $row   
	 * @return Collective\Html\FormBuilder
	 */
	public function toHtml($row)
	{    
		$html = array_get($row, 'html');

		if($html instanceof HtmlString) { 
			return $html->toHtml();
		}

		return parent::toHtml($row) ?? '';
	} 

	public function arrayTransform(array $data)
	{
		$this->original = $this->transformed = collect($data);  

		$transformed = [];

		$dotted = array_dot($this->original); 

		while (count($dotted)) { 
			foreach ($dotted as $key => $value) {

				unset($dotted[$key]); 

				$parts = $this->getInputNameParts($key);  

				if(count($parts) === 0) continue; 

				$key = implode('.', $parts); 

				if(empty($key)) continue;
 
				$value = $this->transform($key, data_get($transformed, $key, $value));

				data_set($transformed, $key, $value);

				array_pop($parts);

				$key = implode('.', $parts); 

				$dotted[$key] = data_get($transformed, $key, $value);  
			} 
		}  

		$this->transformed = collect($transformed);
 
		return $this->getTransformed();
	}

	public function getInputNameParts($name)
	{ 
		$striped = strip_tags(trim($name, '.'));

		if($striped !== $name) return [];

		preg_match_all('/[^\[\]\.]+/', $striped, $matches);

		return $matches[0];
	}

	public function transform($key, $value = null)
	{  
		preg_match_all('/[^\[\]]+/', $key, $matches);

		$key = array_shift($matches[0]);  

		return parent::transform($key, $this->getInput($key, $value));
	}   

	protected function getTransformer($key)
	{  
		return camel_case("transform_". str_replace('.', '_', $key));
	}

	protected function fetchData()
	{
		$data = parent::fetchData();  
	
		$data = $data->get('transformed')->filter(function($val, $key) {
			return is_numeric($key) ? 0 : 1;
		}); 

		$this->childs()->map->doBuild()->map->save(function($input, $form) use (&$data){
			$data = $data->merge($input); 
		});

		if(is_null($this->getParent())) { 
			return $this->toDictionary($data); 
		}

		return $data;
	}


	/**
	 * Getting input from request. 
	 * 
	 * @return Illuminate\Support\Collection
	 */
	public function getInput($key, $default = null)
	{     
		$key = $this->getDottedKey($key);

		return request()->hasFile($key, $default) 
							? request()->file($key, $default)
							: request()->input($key, $default);
	}  

	public function getDottedKey($key)
	{
		$parts = $this->getInputNameParts($key);

		return implode('.', $parts);
	}

	protected function toDictionary($data)
	{
		return collect($this->dataMap())->map(function($keys, $group) use ($data) {
			$values = [];  
			foreach ($keys as $key => $value) { 
				if(is_numeric($key)) {
					$key = $value;
				}

				$values[$key] = data_get($data, $key);
			}  

			return collect($values);
		}); 
	} 

	public function scripts()
	{
		return collect($this->scripts)->implode(''); 
	}

	public function pushScript($name, $script, $src = false)
	{ 
		$this->scripts[$name] = $src 
									? "<script src='{$script}'></script>" 
									: "<script>{$script}</script>"; 

		return $this;
	}

	protected function dataMap() {
		return [
			'general' 	=> $this->generalMap(),
			'relations' => $this->relationMap(),
		];
	} 

	abstract public function generalMap();
	abstract public function relationMap();
}
