<?php 
namespace Core\HttpSite\Concerns; 

use Illuminate\Database\Eloquent\Model;

trait IntractsWithResource
{
	protected $resource = null;
	
    public function resourceId()
    {
    	return optional($this->resource)->id;
    }

    public function resource(Model $resource = null)
    {
    	if(is_null($resource)) {
    		return $this->resource;
    	}

    	$this->resource = $resource;

    	return $this;
    }

    public function data(string $key, $default = null)
    {
        return data_get($this->resource, $key, $default);
    }
}