<?php 
namespace Core\Document;

use Core\Plugin\Plugin as BasePlugin;

abstract class Plugin extends BasePlugin
{  

	/**
	 * Home Page Url of plugin.
	 * 
	 * @var String
	 */
	protected $homePageUrl = '';  
	 

	public function homePageUrl()
	{ 
		return  $this->homePageUrl;
	} 

	public function assets()
	{
		return [
		];
	}   

	public function plugins()
	{ 
		return [];
	}
}
