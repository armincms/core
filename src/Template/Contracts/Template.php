<?php 
namespace Armincms\Template\Contracts;  
 
interface Template 
{
	public function setBody(String $content = null);
	 
	// public function modules(ModuleCollection $modules);
	public function toHtml(array $options = null); 
}
