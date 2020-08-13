<?php 
namespace Core\HttpSite;

use Closure; 
use Illuminate\Support\Fluent;
use Core\HttpSite\Contracts\TemplateRepository;

class SiteManager 
{  
	/**
	 * List of vailable sites
	 * 
	 * @var \Illuminate\Support\Collection
	 */
	protected $sites = [];
 
	public function __construct()
	{ 
		$this->sites = collect(); 
	}

	public function push(string $name, Closure $callback)
	{ 
		if(! $this->sites->has($name)) { 
			$this->sites->put( $name, $this->newSite($name) );
		}

		$callback($this->sites->get($name)); 

		return $this;
	}

	public function newSite(string $name)
	{ 
		return (new Site)->name($name);
	}

	public function all()
	{
		return $this->sites->all();
	}

	public function get(string $name)
	{
		return $this->sites->get($name);
	}

	public function each(callable $callback)
	{
		$this->sites->each($callback);

        return $this;
	}

	public function first(callable $callback)
	{
		return $this->sites->first($callback); 
	}
	
	public function last(callable $callback)
	{
		return $this->sites->last($callback); 
	}

	public function findByComponent(Component $component)
	{
		return $this->sites->first(function($site) use ($component) {
			return false !== $site->components()->search(function($item) use ($component) {
				return get_class($item) === get_class($component);
			});
		});
	}

	public function collect()
	{
		return collect($this->all());
	}
}
