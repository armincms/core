<?php 
namespace Core\Crud\Concerns;

use Storage; 
use Cache; 

trait HasCustomImage
{    
	public static function bootHasCustomImage()
	{
		self::saved(function($model) { 
			$model->forgetCustomIamges(); 
		}); 
	}

	public function getImages($column = 'image')
	{ 
		return Cache::remember(
			$this->getImagesCacheKey($column), 60, function() use ($column) { 
				return $this->retrieveImages($this->getOriginal($column));
			}
		); 
	} 

	protected function retrieveImages($dir)
	{     	 
		return $this->retrieveMap($dir)->map(function($images) use ($dir) {
			return collect($images)->map(function($path, $name) use ($dir) {
				if(in_array($name, ['order', 'master'])) {
					return (int) $path;
				}  

				return $this->retrieveImage(
					($name !== 'original' ? "{$dir}/" : ""). $path, $name
				);
			}); 
		});
	}

	public function retrieveImage(string $path, string $schema)
	{
		if(! Storage::disk('armin.image')->has($path)) { 
			return $this->retrivePlaceholder($schema);  
		}

		return Storage::disk('armin.image')->url($path);
	}

	public function retrivePlaceholder(string $schema)
	{
		if($config = app('armin.imager.schema')->find($schema)) {
			return array_get($config, 'placeholder');
		} 
	}

	public function retrieveMap($dir)
	{
		$map = [];

		if(Storage::disk('armin.image')->has($path = "{$dir}/uploaded.json")) {
			$map = (array) json_decode(Storage::disk('armin.image')->get($path), true);
		} 

		return new ImageCollection($map);
	}

	protected function getImagesCacheKey($column)
	{
		return str_slug($this->getMorphClass() ."{$this->id}-{$column}");
	}

	public function forgetCustomIamges()
	{
		$columns = isset($this->imageColumns) ? $this->imageColumns : ['image'];

		foreach ($columns as $column) {
			Cache::forget($this->getImagesCacheKey($column));
		}

		return $this;
	}


}