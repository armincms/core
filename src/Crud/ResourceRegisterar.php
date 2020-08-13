<?php 
namespace Core\Crud;
 

class ResourceRegisterar 
{ 
	protected $resources = [];
	protected $app;

	public function __construct($app)
	{
		$this->app = $app; 
	}

	public function register(string $name, $resource, $options = [])
	{ 
		$resource = is_string($resource)? $this->app->make($resource) : $resource;

		if($resource instanceof Resource) {
			$this->resources[$name] = compact('resource', 'options');
		} else {  
			throw new Exceptions\InvalidResourceException(
				"Resource " .get_class($resource). "::class Is Invalid."
			);
		}  

		return $this;
	}

	public function all()
	{
		return collect($this->resources); 
	}

	public function get($name)
	{   
		if(isset($this->resources[$name])) {
			return $this->resources[$name];
		} 

		throw new \Exception('Resource not found.');
	}
}