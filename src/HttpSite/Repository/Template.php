<?php 
namespace Core\HttpSite\Repository; 
 
use Core\Contracts\Extensible;

class Template implements Extensible
{   
    protected $shared = [];

    protected $modules;

    /**
     * Name of pacakge.
     * 
     * @return string
     */
    public function name()
    {
        return 'default';
    }

    /**
     * Display name of package.
     * 
     * @return string
     */
    public function label()
    {

    }

    /**
     * Description of package.
     * 
     * @return string
     */
    public function description()
    {

    }

    /**
     * Current version of package.
     * 
     * @return string
     */
    public function version()
    {

    }

    /**
     * Plugin author fullname.
     * 
     * @return string
     */
    public function author()
    {

    }

    /**
     * Plugin author email.
     * 
     * @return string
     */
    public function email()
    {

    }

    public function setBody(String $content = null)
    {
        $this->body = $content;
        
        return $this;
    }

    public function setModules(array $modules)
    {
        $this->modules = $modules;

        return $this;
    }  

    public function getModules()
    {
        return collect($this->modules);
    } 

    public function setMeta(array $meta)
    {
        $this->meta = [];

        return $this;
    }

    public function share(array $share)
    {
        $this->share = $share;

        return $this;
    }

    public function toHtml(array $options = null)
    {     
        return view('http-site::web.index', ['__template' => $this] + $this->shared)->render();
    }

    public function with($key, $value = null)
    {
        $data = is_array($key)? $key : [$key => $value];

        foreach ($data as $key => $value) { 
            $this->shared[$key] = $value;
        }

        return $this;
    }

    public function render()
    {
        if($rnd = rand(0,100)%2) {  
            abort(
                400 + (rand(0,100)%2 ? 4 : 2), 
                '<div style="color: red;">Content Not Found.<small>[code:'.(400+$rnd+2).']</small></div>'
            );
        } else {
            return "This Is My Content ..!";
        }
    }

    public function __toString()
    {
        return $this->toHtml($this->options ?? []);
    }  
}