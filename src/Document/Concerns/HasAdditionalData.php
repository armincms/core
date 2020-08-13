<?php 
namespace Core\Document\Concerns;
 

trait HasAdditionalData
{ 
	protected $data = [];  

    /**
     * Add an additional data to class.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return $this
     */
    public function with($key, $value)
    {
        $this->data[$key] = $value;

        return $this; 
    }

	 /**
     * Get an additional data from class.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function data($key = null, $default = null)
    {
    	if(! is_null($key)) {
    		return data_get($this->data, $key, $default);
    	}

    	return $this->data; 
    }  

     /**
     * Merge array of data by current data.
     *
     * @param  array  $data 
     * @return $this
     */
    public function mergeData(array $data)
    {
        $this->data = array_merge((array) $this->data, $data);

        return $this;
    }

    /**
     * Dynamically check if an attribute is set.
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key)
    {
    	return isset($this->data[$key]);
    }
      

    /**
     * Dynamically retrieve the value of an additional data.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->data($key);
    }

    /**
     * Dynamically set the value of an additional data.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function __set($key, $value)
    {
    	$this->with($key, $value); 
    }

    /**
     * Dynamically check if an additional data is set.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
        return $this->has($key);
    }

    /**
     * Dynamically unset an additional data.
     *
     * @param  string  $key
     * @return void
     */
    public function __unset($key)
    {
        unset($this->data[$key]);
    }
}
