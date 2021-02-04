<?php 

namespace Core\Document\Concerns;

use Html;
use Illuminate\Contracts\Support\Htmlable;
use Armincms\Nova\General;

trait InteractsWithMetaData
{    
	/**
	 * Title of Document.
	 * 
	 * @var string
	 */
	protected $keywords = '';

	/**
	 * List Of available Html metadata`s.
	 * 
	 * @var string
	 */
	protected $metadata = [];

	public function meta(string $tag, string $name, array $attributes = [])
	{ 
		if(empty($attributes)) {
			$attributes = array_merge([
				'name' => $tag,
				'content' => $name,
			], $attributes);

			$tag = 'meta';
			$name = 'name';
		} 

		$this->metadata[$tag][$name] = $attributes;

		return $this;
	} 

	public function pushMeta(Htmlable $meta)
	{
		$this->metadata[] = $meta;

		return $this;
	}

	public function getMetaString()
	{
		return collect($this->metadata)->filter()->map(function($fields, $tag) {
			if($fields instanceof Htmlable) {
				return strval($fields);
			}

			return collect($fields)->map(function($attributes) use ($tag) {
				return '<'.$tag.' '.Html::attributes($attributes). ' />'; 
			})->values()->implode('');
		})->values()->implode("\r\n");
	}

	/**
	 * Get the document title.
	 *  
	 * @return string        
	 */
	public function getTitle()
	{
		return General::option('_app_name_').' | '.$this->title;
	}

	/**
	 * Get the document description.
	 *  
	 * @return string        
	 */
	public function getDescription()
	{
		return $this->description ?: $this->getTitle();
	}

	/**
	 * Get the document keywords.
	 *  
	 * @return string        
	 */
	public function getKeywords()
	{
		return $this->keywords ?: preg_replace('/\W+/', ',', $this->getTitle());
	}

	/**
	 * Get the document keywords.
	 *
	 * @param  string $keywords
	 * @return string        
	 */
	public function keywords(string $keywords)
	{
		$this->keywords = $keywords;

		return $this;
	}
}
