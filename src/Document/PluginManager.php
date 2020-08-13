<?php 
namespace Core\Document;


class PluginManager 
{
    /**
     * Registered Plugins.
     * 
     * @var array
     */
	protected $plugins = []; 

	public function register(Plugin $plugin)
	{
		$this->plugins[] = $plugin;

		return $this;
	}

	public function all()
	{
		return collect($this->plugins);
	}

	public function versions(String $name)
	{
		return collect($this->plugins)->filter(function($plugin) use ($name) {  
			return $plugin->name() === $name;
		});
	}

	public function get(String $name, String $version)
	{
		return $this->versions($name)->first(function($plugin) use ($version) {  
			return $version === '*' || $this->compareVersion($version, $plugin->version());
		});
	}

	protected function compareVersion($version1, $version2)
	{ 
		return version_compare($version1, $version2);
	}
}
