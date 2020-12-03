<?php 
namespace Core\Crud\Concerns;

use Illuminate\Support\Str;
use Storage; 

trait HasImage
{ 
	private $uploaderImages = [];

	public function imageUploader($name='image', $translatable=false, $label=[], $values=[], $multiple=false, $attributes=[], $wrapper_attributes=[], $help=null)
	{ 
		$this->uploaderImages[] = $this->getDottedKey(
			$this->appendPrefix($name)
		);  

		call_user_func_array([$this, 'field'], [
			'type' => 'imageUploader',
			'name' => $name,
            'translatable' => $translatable,
            'label'     => $label, 
            'values'    => $values ?: $this->getImages($this->getImageDirectory($name)), 
            'multiple'  => $multiple,
            'attributes'=> $attributes,  
            'wrapper_attributes'     => $wrapper_attributes,   
            'help' => $help,
		]); 

		return $this;
	}  

	public function getImageDirectory($name)
	{
		$key = $this->getDottedKey(
			$this->appendPrefix($name)
		);
		
		return optional($this->model)->getOriginal($key);
	}
	
	public function getImages($dir)
	{  
		if(! Storage::disk('armin.image')->has($path = "{$dir}/uploaded.json")) {
			return [];
		}

		return collect(json_decode(Storage::disk('armin.image')->get($path), true))->values(); 
	}  

	public function hasTransformer($key)
	{
		return in_array($key, $this->uploaderImages)?: parent::hasTransformer($key); 
	} 

	protected function callTransformer($key, $value)
	{ 
		if(in_array($key, $this->uploaderImages)) {
			return $this->customImageTransformer(
				array_search($key, $this->uploaderImages), $value
			); 
		}

		return parent::callTransformer($key, $value);
	}

	public function customImageTransformer($index, $value = null)
	{    
		$images = $this->makeFilesCollection($value);

		$name = $this->uploaderImages[$index];  

		$dirPath = $this->getUploadDirectory($name);

		$this->cleanDirectoryBy($dirPath, $images);

		$uploaded = collect($images)->mapWithKeys(function($image) use ($dirPath, $name){  
			$original = $image->get('src');
			$order	= $image->get('order');
			$master	= $image->get('master');

			$images = $this->makeCustomImages($original, $dirPath, $this->schemas($name));  

			return [
				$original => $images->merge(compact('original', 'order', 'master'))->all()
			]; 
		});

		Storage::disk('armin.image')->put(
			"{$dirPath}/uploaded.json", $uploaded->toJson()
		);
		
		return $dirPath; 
	} 

	public function makeCustomImages($src, $dir, $schemas)
	{  
		$file = Storage::disk('armin.image')->path($src);
		$destination = Storage::disk('armin.image')->path($dir); 

		return app('armin.imager')->make($file)->customize($schemas)->storeAs($destination)->images()->map->name;
	}

	public function makeFilesCollection($files= [])
	{
		return  collect($files)->map(function($file) {
			if(is_string($file)) {
				$file = [
					'src' 	=> $file,
					'order' => time(),
					'master'=> 0
				];
			}

			return collect($file);
		});
		
	}

	public function getUploadDirectory($column)
	{ 
		if(isset($this->model) && $path = $this->model->getOriginal($column)) {
			return $path;
		}

		return (isset($this->uploadPath)? $this->uploadPath.'/' : '') .assoc_key(); 
	} 


	public function cleanDirectoryBy($dir, $files = [])
	{
		if(empty($files)) {
			Storage::disk('armin.image')->deleteDirectory($dir);
		} 

		$valids = collect($files)->map(function($file) { 
			return \File::name($file->get('src'));
		}); 

		collect(Storage::disk('armin.image')->files($dir))->map(
			function($file)use($valids) {
				if(! Str::contains($file, $valids->toArray())) {
					Storage::disk('armin.image')->delete($file);
				}   
			}
		);  
	} 

	public function imageTemplate($filedName)
	{
		return app('armin.imager.schema')->global()->keys()->all();
	}

	/**
	 * Image schemas of image field.
	 * 
	 * @param  string $filedName
	 * @return string|array           
	 */
	public function schemas($filedName)
	{
		if(isset($this->schemas)) {
			return $this->schemas;
		}

		if(method_exists($this, 'imageTemplate')) {
			return $this->imageTemplate($filedName);
		}

		return app('armin.imager.schema')->global()->keys()->all();
	}
}
