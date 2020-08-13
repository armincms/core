<?php 
namespace Core\HttpSite\Concerns;
 
use Core\HttpSite\UrlHelper;

trait HasPermalink
{

    protected $mutated  = []; 
    protected static $isLinked = []; 
	

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function bootHasPermalink()
    {    
        self::saved(function($model) {     
            if(self::autoLink() && ! in_array($model->id, self::$isLinked)){ 
                self::$isLinked[] = $model->id;
                $model->setPermalink(); 
            } 
        });
    }  

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function autoLink()
    {
        return !isset(self::$autoLink) || self::$autoLink === true;
    }

	public function setPermalink(string $column = 'url', bool $encode = true) 
    {      
        $previous = array_get($this, "attributes.{$column}"); 
        
        $url = $this->buildUrl($this->component()->route()); 

        $this->setAttribute($column,  $encode ? $this->encode($url) : $url);

        $this->save();

        $this->modificationEvent($url, $previous);  

        return $this;   
    }   

    public function modificationEvent(string $url, string $previous = null)
    {  
        event("url.created", $url);
        event("url.deleted", $previous);
    }

    public function url(bool $decode = true)
    {     
        return $this->site()->url(
            $this->relativeUrl($decode), $this->ensureLocale()
        );  
    } 

    public function relativeUrl(bool $decode = true)
    {  
        return $decode ? $this->decode($this->url) : $this->url;
    }  

    public function ensureLocale(string $locale = null)
    {
        return $locale ?: app()->getLocale();
    } 

    public function buildUrl(string $pattern)
    {
        return preg_replace_callback('/\{([^}]+)\}/', function($matched) { 
            $slug = "%{$matched[1]}%";

            $repeat = $this->slugMutationCount($slug);

            return $this->geMutatedSlug($slug, $repeat);
        }, $pattern);
    }

    public function geMutatedSlug($slug, $repeat = 0)
    {
        $mutator = $this->getSlugMutator($slug); 

        if(! is_callable($mutator)) {
            return $this->defaultMutaion($slug); 
        }

        return call_user_func_array($mutator, [$this, $repeat]); 
    }

    public function getSlugMutator($slug)
    {
        $slug = trim($slug, '%');

        return config("http-site.slugs.%{$slug}%.mutator");
    }

    public function defaultMutaion($slug)
    {
        if((boolean) config("armin.permalink.slugs.{$slug}.optional", false)) {
            return '';
        }

        return config("armin.permalink.slugs.{$slug}.default");
    }

    private function slugMutationCount($slug)
    {
        if (isset($this->mutated[$slug])) {
            return $this->mutated[$slug]++; 
        } else {
            $this->mutated[$slug] = 0;
            return 0;
        }
    }

    public function encode(string $url = null)
    {
        return UrlHelper::encode($url);
    }  

    public function decode(string $url = null)
    {
        return UrlHelper::decode($url);
    } 
}
