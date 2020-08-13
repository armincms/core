<?php 
namespace Core\Imager; 

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Closure;
use File;

class Imager 
{ 
	use Macroable;

	/**
	 * Schema repository.
	 * 
	 * @var SchemaRepository
	 */
	protected $schema;

	/**
	 * Original image.
	 * 
	 * @var \Intervention\Image\Image
	 */
	protected $image;

	/**
	 * List of customized images.
	 * 
	 * @var \Illuminate\Support\Collection
	 */
	protected $customized;

	/**
	 * List of stored customized images.
	 * 
	 * @var array
	 */
	protected $stored = [];

	public function __construct(SchemaRepository $schema)
	{
		$this->schema = $schema;
	}

    /**
     * Build imager of recieved image.
     *
     * @param mixed $image  
     * @return $this
     */ 
	public function make($image)
	{
		$this->image = Image::make($image);

		return $this;
	} 

    /**
     * Build custom images of original image for given schemas.
     *
     * @param string|array $schema  
     * @return $this
     */
	public function customize($schema)
	{ 
		$schemas = is_array($schema) ? $schema : func_get_args();

		$this->customized = Collection::make($schemas)->mapWithKeys(function($schema) {
			if($this->schema->has($schema)) {
				$method = $this->schema->get("{$schema}.resize", 'compress'); 

				if(! method_exists($this, $method)) { 
					$method = 'compress';
				} 

				return [$schema => $this->$method($schema)]; 
			} 

			return [$schema => null];
		})->filter(); 

		return $this; 
	} 

	/**
	 * Get all customized image.
	 *  
	 * @return \Illuminate\Support\Collection
	 */
	public function images()
	{
		return Collection::make($this->stored);
	}

	/**
	 * Push new customized image.
	 * 
	 * @param  \Intervention\Image\Image $image 
	 * @param  string $schema 
	 * @param  string $path   
	 * @return $this
	 */
	protected function pushStored($image, $schema, $path)
	{
		$name = $image->basename;
		$this->stored[$schema] = compact('image', 'path', 'name'); 

		return $this; 
	}

	/**
	 * All customized images store in custom location.
	 * 
	 * @return $this
	 */
	public function storeAs(string $path, $options = [])
	{ 
		$this->customized->each(function($image, $schema) use ($path) { 
			$extension 	= $this->getExtension($schema, $image->extension);
			$name = $this->makeCustomImageName($schema, $image->filename, $extension); 
			$path = "{$path}/{$name}"; 

			$this->ensureDirectory($path);

			$this->pushStored($image->save($path, $this->quality($schema)), $schema, $path); 
		}); 

		return $this;
	}

	/**
	 * All customized images store in original image position.
	 * 
	 * @param array $options 
	 * @return $this
	 */
	public function store($options = [])
	{ 
		$this->customized->each(function($image, $schema) {
			$extension 	= $this->getExtension($schema, $image->extension);
			$name = $this->makeCustomImageName($schema, $image->filename, $extension);  
			$path = "{$image->dirname}/{$name}"; 

			$this->ensureDirectory($path);

			$this->pushStored($image->save($path, $this->quality($schema)), $schema, $path); 
		}); 

		return $this;
	}

	/**
	 * Get extension of schema.
	 * 
	 * @param  string $schema  
	 * @param  string $default 
	 * @return string $extension         
	 */
	protected function getExtension($schema, $default)
	{ 
		$extension = $this->config($schema, 'extension', $default); 

		return $this->isValidExtension($extension)? $extension : $default;
	}

	/**
	 * Validate extension.
	 * 
	 * @param  string $extension   
	 * @return boolean          
	 */
	protected function isValidExtension($extension)
	{
		return in_array($extension, (array) config('armin.imager.schema.accepted'));
	}

	/**
	 * Build name for custom image.
	 * 
	 * @param  string $schema    
	 * @param  string $filename  
	 * @param  string $extension 
	 * @return string            
	 */
	protected function makeCustomImageName($schema, $filename, $extension)
	{
		return "{$schema}-{$filename}.{$extension}";  
	}

	/**
	 * Check existance of directory.
	 * 
	 * @param  string $path 
	 * @return $this       
	 */
	protected function ensureDirectory($path)
	{
		$directory = dirname($path); 

		if(! File::exists($directory)) {
			File::makeDirectory($directory, 0755, true, true);
		}

		return $this;
	}

	/**
	 * Images store quality
	 * 
	 * @param  string $schema 
	 * @return int|integer         
	 */
	public function quality(string $schema)
	{
		$compress = (int) ($this->config($schema, 'compress') ?: 100);

		return 100 - $compress ?: $this->default('quality');
	} 

    /**
     * Build new image of originla image without any change.
     *
     * @param string $schema  
     * @return \Intervention\Image\Image
     */ 
	public function compress($schema)
	{
		return $this->image;
	}

    /**
     * Build new resized image of originla image.
     *
     * @param string $schema  
     * @return \Intervention\Image\Image
     */ 
	public function resize($schema)
	{
		$width 	= (int) $this->config($schema, 'width');
		$height = (int) $this->config($schema, 'height'); 

		if($width < 1) {   
			return $this->image()->heighten($height ?: $this->default('height'));
		} 

		if ($height < 1) { 
			return $this->image()->widen($width ?: $this->default('width'));
		} 
 
		return $this->image()->resize($width, $height); 
	} 

    /**
     * Build new croped image of originla image.
     *
     * @param string $schema  
     * @return \Intervention\Image\Image
     */ 
	public function crop($schema)
	{ 
		$width 	= (int) $this->config($schema, 'width');
		$height = (int) $this->config($schema, 'height'); 

		$image = $this->getResizedForCropping($schema);

		return $image->resizeCanvas(
            $width, 
            $height, 
            $this->config('position', 'center'), 
            false, 
            '#' .trim($this->config($schema, 'background', 'fff'), '#')
        ); 
	}

    /**
     * Make resized image for cropping.
     *
     * @param string $schema  
     * @return \Intervention\Image\Image
     */ 
	protected function getResizedForCropping($schema)
	{
		$dimensions = $this->croppingDimensions($schema);

		foreach ($dimensions as $dimension => $value) { 
			$this->setConfig($schema, $dimension, $value);
		} 

		return $this->resize($schema); 
	} 

    /**
     * Remove unnecessary dimension for cropping.
     *
     * @param string $schema  
     * @return array $dimensions
     */ 
    protected function croppingDimensions($schema)
    {   
    	$width 	= $this->config($schema, 'width')?: $this->default('width');
    	$height	= $this->config($schema, 'height')?: $this->default('height');
        $ratio  = $width / $height; 
        $upsize = (boolean) $this->config($schema, 'upsize', false);
        $dimensions = compact('width', 'height');

        if($ratio < 1) {
            // Vertical template
        	// When request crop size is verticaly 
        	return $this->hTov(
        		$this->needCutting($ratio, $upsize, 'vertical'), $dimensions
        	);
        }

        if($ratio > 1) {
            // Horizontal template
        	// When request crop size is horizontal 
        	return $this->vToh(
        		$this->needCutting($ratio, $upsize, 'horizontal'), $dimensions
        	);
        }  

        // squar image
        return $dimensions;
    }

    /**
     * Test if image need upsize for crop.
     *
     * @param integer $ration 
     * @param boolean $upsize 
     * @param string  $direction 
     * @return boolean
     */ 
    protected function needCutting($ratio, $upsize, $direction)
    {
    	if($this->imageIsHorizontal()) {
	    	if($direction == 'vertical') {
            	// horizontal image
	    		return ($upsize && $this->imageRatio() > $ratio) 
								|| (! $upsize && $this->imageRatio() <= $ratio);
	    	}

	    	if($direction == 'horizontal') {
        		// vertical image
	    		return ($upsize && $this->imageRatio() < $ratio) 
								|| (! $upsize && $this->imageRatio() > $ratio); 
	    	}  
    	}

    	return (boolean) $upsize;
    }   

    /**
     * Calculate ration of original image.
     * 
     * @return integer
     */ 
    protected function imageRatio()
    {
    	return intval($this->original()->width() / $this->original()->height());
    }

    /**
     * If original image in horizontal dimension.
     * 
     * @return boolean
     */ 
    protected function imageIsHorizontal()
    { 
    	return $this->imageRatio() >= 1;
    }

    /**
     * If original image in verical dimension.
     * 
     * @return boolean
     */
    protected function imageIsVertical()
    { 
    	return ! $this->imageIsHorizontal();
    } 

    /**
     * Get dimension for convert horizontal to vertical.
     * 
     * @param  boolean $upsize    
     * @param  array $dimensions 
     * @return array $dimension             
     */
    protected function hTOv($upsize, array $dimensions)
    { 
    	$nullable = $upsize == true ? 'width' : 'height';

    	$dimensions[$nullable] = null;

    	return $dimensions; 
    }

    /**
     * Get dimension for convert vertical to horizontal.
     * 
     * @param  boolean $upsize    
     * @param  array $dimensions 
     * @return array $dimension             
     */
    protected function vTOh($upsize, $dimensions)
    {     
    	$nullable = $upsize == true ? 'height' : 'width';

    	$dimensions[$nullable] = null;

    	return $dimensions;  
    } 
 
	/**
	 * Retrieve clone of original image.
	 * 
	 * @return \Intervention\Image\Image
	 */
	public function image()
	{
		return clone $this->image;
	}

	/**
	 * Retrieve original image.
	 * 
	 * @return FileSystem
	 */
	public function original()
	{
		return $this->image;
	} 

	/**
	 * Get default config.
	 * 
	 * @param  string $key 
	 * @return mixed      
	 */
	public function default($key)
	{
		return array_get([
			'height' 	=> 450,
			'width' 	=> 720,
			'quality' 	=> 75, 
		], $key);
	}

	/**
	 * Get schema config.
	 * 
	 * @param  string $schea 
	 * @param  string $key 
	 * @param  string $default 
	 * @return mixed      
	 */
	public function config($schema, $key = null, $default = null)
	{
		$key = $schema . (is_null($key) ? '' : ".{$key}");

		return $this->schema->get($key, $default); 
	}


	/**
	 * Set schema config.
	 * 
	 * @param  string $scheam 
	 * @param  string $key 
	 * @param  string $default 
	 * @return $this      
	 */
	public function setConfig($schema, $key, $value = null)
	{
		$this->schema->set($key, $value);

		return $this;
	}
}
