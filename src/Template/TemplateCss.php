<?php 
namespace Armincms\Template;

use Core\Layout\StyleSheet;

class TemplateCss extends StyleSheet
{
	protected $template; 

	public function __construct(string $template)
	{
		$this->template = $template; 
	}  

    public function toHeader()
    {
    	return true;
    }

	public function toHtml() : string
	{
		$main = "<link rel='stylesheet' id='{$this->template}-css' type='text/css' href='"
				.$this->cssUrl($this->template)
				."'>";
		$custom = "<link rel='stylesheet' id='{$this->template}-custom-css' type='text/css' href='"
				.$this->cssUrl('custom.min')
				."'>";

		return "{$main}\r\t{$custom}"; 
	}

	public function cssUrl($css = '')
	{
		return "/templates/{$this->template}/{$css}.css";
	}
}
