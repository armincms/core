<?php 
namespace Core\Module; 

use Illuminate\Database\Eloquent\Model; 
use Annisa\Form\AnnisaBuilder; 
use Core\Module\Contracts\Module as ModuleContract;

abstract class Module implements ModuleContract
{
    /**
     * Module instance.
     * 
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $module;

    /**
     * Name of moduel.
     * 
     * @var string
     */
    protected $name = null;

    /**
     * Display name of module.
     * 
     * @var string
     */
    protected $label = null;

    /**
     * Description about moduel.
     * 
     * @var string
     */
    protected $description = null;

    /**
     * Moduel author fullname.
     * 
     * @var string
     */
    protected $author = null;

    /**
     * Module author email.
     * 
     * @var string
     */
    protected $email;

    /**
     * Module Version.
     * 
     * @var string
     */
    protected $version = '0.1.0';

    /**
     * Required plugins for display.
     * 
     * @var array
     */
    protected $plugins = [];  

    /**
     * Required assets for display.
     * 
     * @var array
     */
    protected $assets = [];  

    public function __construct(Model $module = null)
    {
        $this->module = $module;
    }  

    /**
     * Get module instance.
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set module Isntance.
     * 
     * @param \Illuminate\Database\Eloquent\Model $module 
     * @return self
     */
    public function setModule(Model $module)
    {
        $this->module = $module;

        return $this;
    }  

    /**
     * Get module render result.
     * 
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set module render result.
     * 
     * @param string $content 
     * @return self
     */
    protected function setContent(string $content = null)
    {
        $this->content = $content;

        return $this;
    } 

    /**
     * Name of module.
     * 
     * @return string
     */
    public function name() : string
    {
        if(empty($this->name)) {
            return $this->guessTheName();
        }

        return $this->name;
    }

    /**
     * Guess name of the module.
     * 
     * @return string 
     */
    protected function guessTheName() : string
    {  
        return kebab_case(class_basename($this));
    }

    /**
     * Display name of module.
     * 
     * @return string
     */
    public function label() : ?string
    {
        if(! isset($this->label)) {
            return title_case($this->name());
        }

        return $this->label;
    }

    /**
     * Description about module.
     * 
     * @return string
     */
    public function description() : ?string
    {
        return $this->description;
    }

    /**
     * Name of author.
     * 
     * @return string
     */
    public function author() : ?string
    {
        return $this->author;
    }

    /**
     * Email of author.
     * 
     * @return string
     */
    public function email() : ?string
    {
        return $this->email;
    }

    /**
     * Version of module.
     * 
     * @return string
     */
    public function version() : string
    {
        return $this->version;
    }

    /**
     * List of required plugins.
     * 
     * @return string
     */
    public function plugins() : array
    {
        return (array) $this->plugins;
    }

    /**
     * List of required assets.
     * 
     * @return string
     */
    public function assets() : array
    {
        return (array) $this->assets;
    } 

    /**
     * Retrieve data from module instance.
     * 
     * @param  string $key     
     * @param  mixed $default 
     * @return mixed          
     */
    public function get($key, $default = null)
    {
        return data_get($this->module, $key, $default);
    } 

    /**
     * Retrieve module isntance prarams.
     * 
     * @param  string $key     
     * @param  mixed $default 
     * @return mixed          
     */
    public function params($key, $default = null)
    {
        return $this->get("params.{$key}", $default);
    } 

    /**
     * Retrieve module isntance config.
     * 
     * @param  string $key     
     * @param  mixed $default 
     * @return mixed          
     */
    public function config($key, $default = null)
    { 
        return $this->params("_config.{$key}", $default);
    } 

    /**
     * Base layout of module.
     * 
     * @return \Core\Layout\Layout
     */
    public function layout()
    {  
        return the_layout($this->get('layout', 'module')) ?: $this->defaultLayout();  
    }

    /**
     * Default base layout of module.
     * 
     * @return \Core\Layout\Layout
     */
    public function defaultLayout()
    {
        return the_layout('module');
    }

    /**
     * Readmore url.
     * 
     * @return string
     */
    public function readmore()
    {
        return;
    }

    /**
     * Form builder.
     * 
     * @param  \Annisa\Form\AnnisaBuilder $form 
     * @return \Annisa\Form\AnnisaBuilder $form 
     */
    // abstract public function fields(AnnisaBuilder $form) : AnnisaBuilder;

    /**
     * Module renderer.
     *  
     * @return string 
     */
    abstract public function render() : ?string;

    /**
     * Display module data.
     * 
     * @return string
     */
    public function toHtml() : string 
    {
        $this->setContent($this->render());

        return (string) $this->layout()->display(['__module' => $this]); 
    } 
}
