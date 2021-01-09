<?php 
namespace Core\HttpSite;

use Illuminate\Support\Fluent; 
use Illuminate\Http\Request; 
use Core\Document\Document;

abstract class Component extends Fluent
{      
	/**
	 * Name of Component.
	 * 
	 * @var null
	 */
	protected $name = null;

	/**
	 * Label of Component.
	 * 
	 * @var null
	 */
	protected $label = null;

	/**
	 * SingularLabe of Component.
	 * 
	 * @var null
	 */
	protected $singularLabel = null;

	/**
	 * Route of Component.
	 * 
	 * @var null
	 */
	protected $route = null;

	/**
	 * Route Conditions of Component.
	 * 
	 * @var null
	 */
	protected $wheres = []; 


	public function route()
	{  
		return empty($this->route) ? $this->defaultRoute() : $this->route;
	} 

	public function defaultRoute()
	{
		$name 	= $this->name();
		$key	= $this->routeKey();

		return "{$name}/{{$key}}";
	}

	public function routeKey()
	{
		return 'id';
	}

	public function setRoute(String $route)
	{
		$this->route = $route; 

		return $this;
	}  

	public function wheres() 
	{   
		return (array) $this->wheres;
	}  

	public function where(String $key, String $value)
	{
		$this->wheres[$key] = $value; 

		return $this;
	}

	public function setWheres(array $wheres)
	{
		$this->wheres = $wheres; 

		return $this;
	}

	public function name()
	{ 
		return empty($this->name) ? strtolower(class_basename($this)) : $this->name;
	}

	public function label()
	{ 
		return empty($this->label) ? str_plural($this->singularLabel()) : $this->label;
	}

	public function singularLabel() 
	{ 
		return empty($this->singularLabel) ? title_case($this->name()) : $this->singularLabel; 
	}  

	public function isHome()
	{
		return (boolean) $this->home;
	} 

	public function config($key = null, $default = null)
	{
		if(is_array($key)) {
			$this->offsetSet(key($key), reset($key)); 

			return $this;
		}

		if(is_null($key)) {
			return $this->getAttributes();
		}

		return $this->get($key, $default);
	} 

	public function configGroup() : string
	{
		return null;
	}

	public function fields() : array
	{
		return [];
	}

	public function method()
	{
		return 'get';
	}

	abstract public function toHtml(Request $request, Document $document) : string;
}
