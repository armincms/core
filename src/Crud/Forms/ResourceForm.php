<?php 
namespace Core\Crud\Forms;

use Annisa\Form\AnnisaBuilder; 
use Component\ContentManagement\Content;
use Illuminate\Support\HtmlString;
use Core\Crud\Contracts\Titled; 
use Core\Crud\Concerns\HasTitle; 
use Closure;


abstract class ResourceForm extends AnnisaBuilder implements Titled
{  
	use HasTitle;

	protected $languages;
	protected $title;
	protected $scripts = []; 
	protected $data = [];

	public function __construct($callback = null)
	{
		parent::__construct($callback);

		$this->component = 'amd';
		$this->languages = language();
		view()->share('languages', $this->languages); 
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
	 * Appending Form Model.
	 * 
	 * @param  string $model   
	 * @return string | object
	 */
	public function setModel($model = null)
	{  
		parent::setModel($model);

		$this->builder->setModel($model); 

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

	/**
	 * Prefixing.
	 *  
	 * @param  string $name     
	 * @return Form\Builder
	 */
	protected function appendPrefix(string $name)
	{   
		if(! empty($this->prefix)) {
			$name = preg_replace('/^([^\[]+)/', "{$this->prefix}[$0]", $name);
		}

		return $name;
	}

	public function arrayTransform(array $data)
	{
		$this->original = $this->transformed = collect($data);  

		$transformed = [];

		$dotted = $this->getDottedArray($this->original); 
 
  		$args = [];
  		$depth = 0;
		while (count($dotted)) { 
			$depth++; 
			foreach ($dotted as $key => $data) {  
				$parts = $this->getInputNameParts($key); 

				unset($dotted[$key]);

				if(count($parts) === 0) continue; 

				$key = implode('.', $parts); 

				if(empty($key)) continue;   

				if($depth > 1) {   
					if(isset($transformed[$key])) {
						$data = array_merge((array) $transformed[$key], $data);
					}
					$value = parent::transform($key, $data); 
				} else {  
					$value = $this->transform($key, $data); 
				}

				// if($this->needMerge($data, $key)) { 
				// 	$value = array_merge((array) $value, $data);
				// }  
 
				$index = array_pop($parts);

				$dotKey = implode('.', $parts);  

				if(empty($dotKey)) {   
					$transformed = array_replace_recursive($transformed, [$key => $value]); 
				} else if(isset($args[$dotKey])) {   
					$args[$dotKey] = array_merge((array) $args[$dotKey], [$index => $value]);
				} else {
					$args[$dotKey][$index] = $value;
				}
				
			}  
					
			$dotted = $args;
			$args = []; 
		}   
  
		$this->transformed = collect($transformed);
  
		return $this->getTransformed();
	}


	public function getDottedArray($array)
	{
		$dotted = array_dot($array);

		foreach ($dotted as $key => $value) {
			if(preg_match('/\.([0-9]+)^/', $key, $matches)) { 
				unset($dotted[$key]);
				$key = str_replace(".{$matches[1]}", '', $key);
				$dotted[$key][$matches[1]] = $value;
			}
		} 

		return $dotted;
	}

	// public function needMerge($data, $key)
	// {
	// 	if(is_array($data)) { 
	// 		return collect($data)->keys()->filter(function($value) {
	// 			return is_numeric($value);
	// 		})->count() === 0;
	// 	}

	// 	return false;
	// }

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
			$data = collect(
				array_replace_recursive($data->toArray(), $input->toArray()) 
			);     
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

				if($group == 'relations') {
					$values[$key][$value] = data_get($data, $key);
				} else {
					$values[$key] = data_get($data, $key);
				} 
			}  

			return collect($values);
		}); 
	} 

	public function scripts()
	{ 
		return collect($this->scripts)->implode(PHP_EOL) . $this->childs()->map->scripts()->implode(PHP_EOL); 
	}

	public function pushScript($name, $script, $src = false)
	{ 
		$this->scripts[$name] = $src 
									? "<script src='{$script}'></script>" 
									: "<script>{$script}</script>"; 

		return $this;
	}

	public function styles()
	{  
		return collect($this->styles)->implode(PHP_EOL) . $this->childs()->map->styles()->implode(PHP_EOL); 
	}

	public function pushStyle($name, $css, $link = false)
	{ 
		$this->styles[$name] = $link 
									? "<link rel='stylesheet' href='{$css}'></link>" 
									: "<style>{$css}</style>"; 

		return $this;
	}

	protected function dataMap() {
		return [
			'general' 	=> $this->generalMap(),
			'relations' => $this->relationMap(),
		];
	} 


    /**
     * Add a piece of data to the view.
     *
     * @param  string|array  $key
     * @param  mixed   $value
     * @return $this
     */
    public function with($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }

        return $this;
    } 

    /**
     * Determine if a piece of data is bound.
     *
     * @param  string  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Get a piece of bound data to the view.
     *
     * @param  string  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->data[$key];
    }

    /**
     * Set a piece of data on the view.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->with($key, $value);
    }

    /**
     * Unset a piece of data from the view.
     *
     * @param  string  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->data[$key]);
    }

    /**
     * Get a piece of data from the view.
     *
     * @param  string  $key
     * @return mixed
     */
    public function &__get($key)
    {
        return $this->data[$key];
    }

    /**
     * Set a piece of data on the view.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->with($key, $value);
    }

    /**
     * Check if a piece of data is bound to the view.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * Remove a piece of bound data from the view.
     *
     * @param  string  $key
     * @return void
     */
    public function __unset($key)
    {
        unset($this->data[$key]);
    }

    /**
     * Dynamically bind parameters to the view.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return \Illuminate\View\View
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {  
        if (! starts_with($method, 'with')) {
        	return parent::__call($method, $parameters);
        }

        return $this->with(camel_case(substr($method, 4)), $parameters[0]);
    } 

	abstract public function generalMap();
	abstract public function relationMap();

}
