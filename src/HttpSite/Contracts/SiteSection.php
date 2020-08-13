<?php 
namespace Core\HttpSite\Contracts;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;

interface SiteSection
{
	// public function name(String $name): self; 
	public function route(String $route, array $where = null): self;   
	// public function toHtml(): String;
}
