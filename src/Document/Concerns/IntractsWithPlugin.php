<?php 
namespace Core\Document\Concerns;

use HtmlPlugin;
use Core\Document\Plugin;

trait IntractsWithPlugin
{ 
	use IntractsWithAsset;

	/**
	 * Listt of plugins.
	 * 
	 * @var array
	 */
	protected $plugins = [];     


	public function pushPlugin(string $plugin, string $version = null, int $order = null)
	{  
		if($plugin = HtmlPlugin::get($plugin, is_null($version) ? '*' : $version)) {
			$order 	= is_null($order) ? count($this->plugins) : $order;

			$this->loadRequirements($plugin, $order);

			$this->plugins[] = compact('plugin', 'order'); 
		} 

		return $this;
	} 

	public function pushPlugins(array $plugins, int $order = null)
	{
		$order 	= is_null($order) ? count($this->plugins) : $order;

		foreach ($plugins as $plugin => $version) {
			if(is_numeric($plugin)) {
				$plugin = $version;
				$version = '*';
			}  

			$this->pushPlugin($plugin, $version, $order);
		}

		return $this;
	}

	protected function loadRequirements(Plugin $plugin, int $order)
	{
		$this->pushPlugins((array) $plugin->plugins(), $order - 1);

		return $this;  
	}

	public function plugins()
	{
		return collect($this->plugins)->sortBy('order');
	} 

	public function setPlugins(array $plugins)
	{
		$this->plugins = $plugins;

		return $this;
	}  

	public function assets()
	{
		return $this->pluginAssets()->merge($this->sheets())->unique(function($asset) {
			return $asset->toHtml();
		});
	}

	public function pluginAssets()
	{ 
		return $this->plugins()->pluck('plugin')->map(function($plugin) {
			return $plugin->assets();
		})->flatten();
	}

	public function headerAssets()
	{
		return $this->assets()->filter(function($asset) {
			return $asset->toHeader();
		});
	}

	public function footerAssets()
	{ 
		return $this->assets()->filter(function($asset) {
			return ! $asset->toHeader();
		});
	}
}
