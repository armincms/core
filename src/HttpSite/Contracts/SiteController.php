<?php 
namespace Core\HttpSite\Contracts;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;

interface SiteController
{  
	public function setTemplate(String $name);  
	public function getTemplate(); 
	public function setLocale(String $locale);  
	public function getLocale();  
}
