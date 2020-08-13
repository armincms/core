<?php 
namespace Core\Document;


abstract class Asset 
{  
	/**
	 * Path of Asset Manifiest
	 * 
	 * @var String
	 */
	protected $path = null;

	/**
	 * Relative Url of Asset.
	 * 
	 * @var String
	 */
	protected $url = null; 

	/**
	 * Cdn Url of asset.
	 * 
	 * @var String
	 */
	protected $cdn = null;


	public function path()
	{
		return $this->path;
	}

	public function uri()
	{
		return $this->url;
	} 

	public function url($secure = false)
	{
		return asset($this->url, (boolean) $secure);
	} 

	public function cdn()
	{
		return $this->cdn;
	} 

	public function toHeader()
	{
	 	return false;
	} 

	public function name()
	{
		return property_exists($this, 'name') ? $this->name : str_slug(class_basename($this));
	}

	abstract public function toHtml() : string;
}
