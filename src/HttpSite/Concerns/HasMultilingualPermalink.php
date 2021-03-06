<?php 
namespace Core\HttpSite\Concerns;
 
trait HasMultilingualPermalink
{
	use HasPermalink;  

	static public function bootHasMultilingualPermalink()
	{
		self::saved(function($model) {
			$model->getTranslationModel()::saved(function($translate) use ($model) {
				if($model->translates->count() === 0 && $model::autoLink()) { 
					$model->load('translates')->setPermalink();
				}
			});
		});
	}

    public function setPermalink(string $column = 'url', bool $encode = true) 
    {      
		$translates = $this->translates;  

        foreach ($translates as $translate) { 
            $previous = array_get($translate, "attributes.{$column}");

            $this->setRelation('translates', collect([$translate])); 

            $url = $this->buildUrl($this->component()->route()); 

	    	$this->setAttribute(
	    		"{$translate->language}::$column",  $encode ? $this->encode($url) : $url
	    	);

	    	$translate->save();

	    	$this->modificationEvent($url, $previous);  
        }

        $this->setRelation('translates', $translates);   
    }    

	public function url(bool $decode = true, string $locale = null)
	{     
		if($relativeUrl  = $this->relativeUrl($decode, $locale)) { 
			return $this->site()->url($relativeUrl, $this->ensureLocale($locale)); 
		}  
	} 

	public function relativeUrl(bool $decode = true, string $locale = null)
	{ 
		if($url = $this->trans('url', $this->ensureLocale($locale))) {
			return $decode ? $this->decode($url) : $url;
		} 		
	}

	public function getUrlAttribute()
	{
		return $this->url();
	}

	public function getRelativeUrlAttribute()
	{
		return $this->relativeUrl();
	}
}
