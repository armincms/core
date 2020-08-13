<?php 
namespace Core\Crud\Concerns;


trait SearchEngineOptimizeTrait
{
	private $metas = null;

	public function metaTitle()
	{
		return $this->getMeta('title')?: $this->getResourceTitle(); 
	}

	public function metaDescription()
	{
		return $this->getMeta('description')?: $this->getResourceDescription();
	}  

	public function getMetas()
	{
		return ['title' => $this->metaTitle(), 'description' => $this->metaDescription()];
	} 

	public function getMeta($key)
	{
		$metaColumn = isset($this->metaColumn) ? $this->metaColumn : 'seo';
		$metas = $this->$metaColumn;


		if(is_string($metas)) {
			$metas = json_decode($metas);
		}  

		return array_get($metas, $key);
	} 

	public function getResourceTitle()
	{
		return array_get($this, $this->getColumnName('title'));
	}

	public function getResourceDescription()
	{
		return array_get($this, $this->getColumnName('description'));
	}

	private function getColumnName($key)
	{
		$property = camel_case("{$key}Column");

		return isset($this->$property)? $property : $key;
	}
}