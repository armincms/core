<?php 
namespace Core\HttpSite\Contracts;  

interface SiteRequest 
{
	public function site();
	public function component();
	public function relativeUrl($decode = null); 
    public function template();
}
