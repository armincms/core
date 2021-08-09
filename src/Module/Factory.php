<?php 
namespace Core\Module;

use Core\Module\ModuleInstance as Instance;
use Core\Module\Repositories\Repository;

class Factory
{
	/**
	 * Moduel repository.
	 * 
	 * @var \Core\Moduel\Repositories\Repository
	 */
	protected $modules;

	public function __construct(Repository $modules)
	{
		$this->modules = $modules;
	}

	public function make(Instance $instance)
	{
		$module = $this->module($instance->module);

		return is_object($module) ? $module->setModule($instance) : new $module($instance);
	}

	public function module(string $name)
	{
		return $this->modules->find($name);
	}
}
