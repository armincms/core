<?php 
namespace Core\Layout;
   
use Core\Contracts\Extensible;

class Layout implements Extensible
{ 
    protected $name; 
    protected $directory;  
    protected $composer;   
    protected $viewFile;   

    public function __construct(string $name = null, string $directory = null)
    {
        $this->name = $name;
        $this->directory = $directory;  
    }  
     
    /**
     * Name of pacakge.
     * 
     * @return string
     */
    public function name()
    {
        return $this->name;   
    }

    /**
     * Display name of package.
     * 
     * @return string
     */
    public function label() 
    {
        return $this->meta('label');
    }

    /**
     * Description of package.
     * 
     * @return string
     */
    public function description()
    {
        return $this->meta('description');
    }

    /**
     * Current version of package.
     * 
     * @return string
     */
    public function version()
    {
        return $this->meta('version', '0.1.0');
    }

    /**
     * Plugin author fullname.
     * 
     * @return string
     */
    public function author()
    {
        return $this->meta('author.name');
    }

    /**
     * Plugin author email.
     * 
     * @return string
     */
    public function email()
    {
        return $this->meta('author.email');
    } 

    public function directory()
    {
        return $this->directory;   
    }   
 

    public function group()
    {
        return (array) $this->meta('group');
    }

    public function css() : StyleSheet
    { 
        $path   = "$this->directory/{$this->name()}.css" ; 
        $uri    = "layouts/{$this->name()}/{$this->name()}.css"; 
 
        return new StyleSheet($path, $uri);
    }  

    public function image()
    {
        return $this->name().'.jpg';
    } 

    public function meta(string $key, $default = null)
    {
        if(! $this->loadedComposer()) {
            $this->loadComposer();
        }

        return data_get($this->composer, $key, $default); 
    }

    public function loadedComposer()
    {
        return isset($this->composer);
    }

    public function loadComposer()
    {
        $path = $this->directory().'/composer.json';

        try {
            $this->composer = json_decode(\File::get($path));
        } catch (\Exception $e) {
            $this->composer = [];
        }

        return $this;
    }

    public function plugins()
    {
        return collect($this->meta('require'))->mapWithKeys(function($verison, $require) { 
            if(starts_with($require, 'armin-cms/plugin-')) {
                $plugin = str_after($require, 'armin-cms/plugin-');

                return [$plugin => $verison];
            }

            return [];
        })->filter()->all();
    }

    public function display(array $data = [], array $config = [])
    { 
        $hint = layout_hint_key($this->name());
        $viewName = "{$hint}::{$this->name()}";

        return tap(view($viewName), function($view) use ($data, $config) {
           $view->getEngine()->mergeData($data)->mergeConfig($config); 
        });    
    }  
}