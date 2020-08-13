<?php 
namespace Core\Plugin; 

use Core\Contracts\Extensible;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
 
abstract class Plugin extends EventServiceProvider implements Extensible
{
	/**
	 * Name of Plugin.
	 * 
	 * @var string
	 */
    protected $name; 

	/**
	 * Display name of Plugin.
	 * 
	 * @var string
	 */
    protected $label;  

	/**
	 * Description of Plugin.
	 * 
	 * @var string
	 */
    protected $description;  

	/**
	 * Version of Plugin.
	 * 
	 * @var string
	 */
    protected $version = '0.1.0'; 

	/**
	 * Fullname of plugin author.
	 * 
	 * @var string
	 */
    protected $author = 'Esmaiel Zareh';  

	/**
	 * Email of plugin author.
	 * 
	 * @var string
	 */
    protected $email = 'zarehesmaiel@gmail.com';   



    /**
     * @return string
     */
    public function name()
    {
    	if(empty($this->name)) {
    		return str_slug(class_basename($this));
    	}

        return $this->name;
    }

    /**
     * @return string
     */
    public function label()
    {
    	if(empty($this->label)) {
    		return title_case($this->name());
    	}

        return $this->label;
    }

    /**
     * @return string
     */
    public function description()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function version()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function author()
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function email()
    {
        return $this->email;
    }
}