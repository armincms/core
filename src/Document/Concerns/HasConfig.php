<?php 
namespace Core\Document\Concerns;
 

trait HasConfig
{ 
    use HasAdditionalData;

    /**
     * Merge or retrive an config.
     * 
     * @param  string|array|null $key     
     * @param  mixed $default 
     * @return mixed          
     */
    public function config($key = null, $default = null)
    {  
        if(is_array($key)) {
            return $this->mergeConfig($key);
        } 

        return $this->data($this->getConfigKey($key), $default); 
    }  

    /**
     * Make valid config key.
     * 
     * @param  string $key 
     * @return string      
     */
    public function getConfigKey(string $key)
    {
        return 'config.'. str_after($key, 'config.');
    }

    /**
     * Mereg array of configs into current configs.
     * 
     * @param  array  $config 
     * @return $this         
     */
    public function mergeConfig(array $config)
    {
        $this->with(
            'config', array_merge((array) $this->data('config', []), $config)
        );  

        return $this;
    } 
}
