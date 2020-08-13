<?php 
namespace Core\Module;

use Core\Document\Asset;

class StyleSheet extends Asset
{  
    public function toHeader()
    {
    	return true;
    }

	public function toHtml() : string
	{
		return "<link rel='stylesheet' id='modules-css' type='text/css' href='/modules/stylesheet.min.css'>";  
	} 
}
