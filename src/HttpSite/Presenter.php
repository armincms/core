<?php 
namespace Core\HttpSite;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Core\Crud\Contracts\SearchEngineOptimize;
use Component\Tagging\Contracts\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\RouteAction;
use App;

abstract class Presenter extends Controller
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests; 

	protected $request;
	protected $route;
	protected $domain;
	protected $permalink;
	protected $site;
	protected $template;
	protected $section;
	protected $presentable;

	function __construct(Request $request)
	{
		$this->request 	= $request;
		$this->route	= $request->route(); 
		$this->domain 	= $this->getDoamin();
		$this->permalink= $this->getPermalink();  
		$this->site 	= $this->getSite();  
		$this->template = $this->getTemplate($this->site);
		$this->section 	= $this->getSection();  
		$this->locale 	= language()->firstWhere('alias', \App::getLocale()); 
	}

	public function home()
	{  
		return $this->display(
			null, 
			$this->getModules(
				App::getLocale(), ['sites', 'site'], [$this->site->id, $this->site->id]
			), 
			['meta' => $this->getMeta()]
		);
		// Display Home 
	}

	public function present()
	{    
		$resource = $this->getResource(
			$this->getResourceKey(func_get_args()), $this->section
		);

		if(is_null($resource)) {
			return $this->error(404);
		}   

		$modules = $this->getModules(
			App::getLocale(), 
			[$this->section->section_name, 'site'], 
			[$resource->id, $this->site->id]
		);

		presenting_resource($resource);

		if($resource instanceof \Core\HttpSite\Contracts\Hitsable) {
			$resource->increaseVisiting();
		}

		return $this->display(
			$this->displayResource($resource), $modules, ['meta' => $this->getMeta($resource)]
		);  
		// Display Content 
	}

	protected function display($layout = null, $modules = [], $options = [])
	{  
		return $this->template->display($layout, $modules, $options);
	}

	protected function getPresentable()
	{
		if(! isset($this->presentable)) { 
			$this->presentable = collect(config('http-site.presentables'))->first(
				function($presentable) {
					$uses 	= array_get($this->route->getAction(), 'uses');
					$action = $presentable['action'];

					if(is_string($action) && class_exists($action)) {
						return $this->getActionPresenter($uses) === $action;
					}


					$action = RouteAction::parse(
						$this->route->uri(), ['uses' => $action]
					);  

					if(is_string($uses) && $this->isHomeAction($uses)) {
						return $this->getActionPresenter($uses) === $this->getActionPresenter($action['uses']) && array_get($presentable, 'home'); 
					} 

					return $uses === $action['uses'];
				}
			);
		}
 
		return $this->presentable; 
	}

	public function isHomeAction($action)
	{
		return preg_match('/@home+/', $action);
	}

	public function getActionPresenter($action)
	{
		return preg_replace('/@[^@]+/', '', $action);
	}

	protected function getDoamin()
	{  
		if(! isset($this->domain)) {
			$domain = $this->config('domain'); 

			$this->domain = empty($domain) ? $this->request->getHost() : $domain;
		} 

		return $this->domain;  
	}

	protected function getPermalink()
	{ 
		if(! isset($this->permalink)) {
			$this->permalink = str_after($this->request->fullUrl(), $this->domain);
		} 

		return str_after($this->permalink, \App::getLocale().'/');  
	}

	protected function getSite()
	{ 
		if(! isset($this->site)) {
			$domain = str_after($this->domain, 'www.');

			$this->site = Site::whereIn('name', [$domain, "www.{$domain}"])->first(); 
		}
		
		return $this->site;
	}


	protected function getSection()
	{
		if(! isset($this->section)) {
			$this->section = section()->filter(function($section){ 
				if($this->config('prefix') !== $section->prefix()) {
					return false;
				} 

				return (int) $section->site_id === (int) $this->site->id;
			})->first(); 
		}  
		
		return $this->section;
	}

	protected function getTemplate(Site $site)
	{  
		return active_template()?: app('armin.template')->get()->firstWhere('name','default');
	} 

	protected function getModules($locale = 'fa', $location = 'other', $resource = 'index')
	{ 
		return app('armin.repository.module')->get(true)->filter(
			function ($module) use ($locale, $location, $resource) { 
				return $module->locatedAt($locale, $location, $resource) 
					&& $module->inTemplate($this->template->id);   
			}
		)->unique()->sortBy('ordering')->groupBy('position');
	} 

	protected function getMeta(Model $resource = null)
	{
		$title = $this->site->title;
		$description = $this->site->description;
		$keywords = $this->site->keywords;
		$h1 = $this->site->h1_title;

		if(! is_null($resource)) {
			$h1 = $this->site->h1_appended_title;

			if($resource instanceof SearchEngineOptimize) {
				$title = $resource->metaTitle()?: $title;
				$description = $resource->metaDescription()?: $description;
				$h1 .= " | {$title}"; 
			} 

			if($resource instanceof Taggable) {
				$resource->relationLoaded('tags') || $resource->load('tags');
				$keywords = $resource->tags->pluck('title')->implode(',');
			} 
		}  

		return compact('title', 'description', 'h1', 'keywords');
	}

	protected function config($key, $default = null)
	{
		return array_get($this->getPresentable(), $key, $default);
	}

	protected function getResourceKey($parts)
	{  
		preg_match_all('/[^\/\{\}]+/', $this->config('pattern'), $matches); 

		return $parts[array_search('id', $matches[0])];
	}

	public function displayResource($resource)
	{
		$layout = $this->getResourceLayout($resource);

		foreach ((array) array_get($layout, 'items.plugins', []) as $plugin) {
			$this->template->pushPlugin($plugin);
		}
		
		return $layout->display($resource, (array) $this->config('layouts.single.config')); 
	}

	public function getResourceLayout($resource)
	{
		return layouts($this->config('layouts.single.layout'));
	}


    protected function parseDomain($domain)
    {  
        preg_match_all('#[^\\\\\/]+#', $domain, $matches);

        $parsed['domain'] = (string) array_shift($matches[0]);
        $parsed['prefix'] = (string) implode('/', $matches[0]);

        preg_match_all('/\./', $parsed['domain'], $matches);

        if(count($matches[0]) == 1) {
            $parsed['domain'] = "www.{$parsed['domain']}";
        } 

        return $parsed;
    } 

	abstract public function getResource(string $url);
}
