<?php 
namespace Core\Layout;

use Core\Document\Asset;


class StyleSheet extends Asset 
{     
	public function __construct($path, $uri)
	{
		$this->path = $path;
		$this->uri  = $uri;
	} 

	public function url($secure = false)
	{
		return asset($this->uri, (boolean) $secure);
	}  

	public function toHeader()
	{
	 	return true;
	}

	public function toHtml() : string
	{
		$url = $this->url(request()->secure());

		return "<link rel='stylesheet' type='text/css' href='{$url}'>";
	}
}
