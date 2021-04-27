<?php 
namespace Core\HttpSite;
  
use Closure; 

class Site  
{     

    /**
     * Name of site.
     *
     * @var string
     */
    public $name = null; 

    /**
     * Title of site.
     *
     * @var string
     */
    public $title = null; 

    /**
     * Description of site.
     *
     * @var string
     */
    public $description = null;  

    /**
     * Template of site.
     *
     * @var string
     */
    public $template; 

	/**
	 * Directory of site.
	 * @var string
	 */
	protected $directory = null;  

    /**
     * Fallback Handling.
     *
     * @var boolean
     */
    public $fallback = false; 

    /**
     * Home site for specific domain.
     *
     * @var boolean
     */
    public $home = false; 

	/**
	 * List of site components.
	 * @var array
	 */
	protected $components = [];  

	/**
	 * List of site domains.
	 * @var array
	 */
	protected $domains = [];  

	/**
	 * List of site middlewares.
	 * @var array
	 */
	protected $middlewares = [];  


	public function __construct()
	{
		$this->domains['*'] = UrlHelper::defaultHost();
	}


	public function name(string $name = null)
	{
		if(is_null($name)) {
			return $this->name;
		}
		
		$this->name = $name;

		return $this;
	}

	public function title(string $title = null)
	{
		if(is_null($title)) {
			return armin_trans($this->title);
		}

		$this->title = $title;

		return $this;
	}

	public function description(string $description = null)
	{
		if(is_null($description)) {
			return $this->description;
		}

		$this->description = $description;

		return $this;
	} 

	public function domain(string $domain)
	{
		return $this->pushDomain($domain); 
	}
	 
	public function pushDomain(string $domain, string $locale = '*')
	{ 
		$this->domains[$locale] = UrlHelper::getNormalizedHost($domain);

		return $this;
	}  

	public function getDomain(string $locale = '*')
	{   
		if('*' !== $locale && ! $this->domains()->has($locale)) {
			return $this->getDomain('*');
		}

		return $this->domains()->get($locale); 
	} 

	public function domains()
	{ 
		return collect($this->domains);
	}
	 
	public function directory(string $directory = null)
	{ 
		if(is_null($directory)) {
			return $this->directory;
		}

		$this->directory = UrlHelper::trimSlash($directory);

		return $this;
	}  
 
	public function template(string $template = null)
	{
		if(is_null($template)) { 
			return empty($this->template) ? default_template() : $this->template;
		}

		$this->template = $template;

		return $this;
	} 

	public function pushComponent(Component $component, Closure $callback = null)
	{    
		if(! is_null($callback)) {
			$callback($component);
		} 

		$this->components[] = $component;  

   		return $this;
	}

	public function components()
	{ 
		return collect($this->components)->keyBy->name();
	} 

	public function component(string $component)
	{ 
   		return $this->components()->get($component);
	}   

	public function pushMiddleware($middleware)
	{
		$middlewares = is_array($middleware)? (array) $middleware : func_get_args();

		$this->middlewares = array_merge((array) $this->middlewares, $middlewares);

		return $this;
	} 

	public function middlewares()
	{
		return collect($this->middlewares);
	} 

	public function url($url = '/', string $locale = '*')
	{
		$base 	= $this->baseUrl($locale);  
		$fullUrl= UrlHelper::trimSlash("{$base}/{$url}");

		return UrlHelper::ensureProtocol($fullUrl);
	}


	public function baseUrl(string $locale = '*')
	{   
		return $this->getDomain($locale) . ($this->directory ? "/{$this->directory}" : '');
	}

	public function fallback(Bool $fallback = true)
	{
		$this->fallback = $fallback;

		return $this;
	}

	public function isFallback()
	{
		return (boolean) $this->fallback === true;
	} 

	public function home()
	{
		$this->home = true;

		return $this;
	}

	public function isHome()
	{
		return (boolean) $this->home;
	} 

	public function findComponentByRoute(string $route)
	{
		return $this->components()->first(function($component) use ($route) { 
            return UrlHelper::trimSlash($component->route()) === $route; 
        });
	}
}
