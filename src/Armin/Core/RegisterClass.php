<?php

namespace Armin\Core;
  
use Illuminate\Foundation\AliasLoader;
use Armin\Models\Menu;
use Armin\Models\Setting; 

class RegisterClass 
{ 
    private static $relations = [];
    /**
     * [$providers auto register service providers]
     * @var array
     */
    protected static $providers = [];
 
    /**
     * [$shareables global shared items]
     * @var array
     */
    protected static $shareables = [];

    /**
     * [$admin_menus menu of admin panel page]
     * @var array
     */
    protected static $admin_menus = [];

    /**
     * [provider auto register service provider]
     * @param  array $values 
     * @return array $providers     
     */
    public function provider($providers=null, $priority=99)
    {
        if ($providers != null) { 
            $providers = is_array($providers)? $providers : [$providers => $priority];

            foreach ($providers as $provider => $order) {
                static::$providers[$provider] = isset($order) ? $order : $priority;
            } 
        } 

        asort(static::$providers);

        return static::$providers;
    }

    /**
     * [alias auto register facade]
     * @param  array $values 
     * @return array $alias     
     */
    public function alias($name, $facade, $class)
    { 
        $loader = AliasLoader::getInstance();   

        app()->singleton($name, $class);

        $loader->alias($name, $facade);  
    }

    /**
     * [share for global access to variable in views]
     * @param  array $values 
     * @return array $shareables       
     */
    public function share($values=null)
    {
        if (is_null($values)) {
            return static::$shareables;
        }

        if (! is_array($values)) {
            $args = (array) func_get_args();
 
            while ($key = array_shift($args)) {
                static::$shareables[$key] = is_array($args) ? array_shift($args) : $args;
            } 

        } else {
            static::$shareables = array_merge(static::$shareables, $values);
        } 

        return static::$shareables;
    }

    /**
     * [adminMenu register menu for admin panel]
     * @param  string $slug  
     * @param  string $title 
     * @param  string $url   
     * @param  string $icon  
     * @return array  $admin_menus      
     */
    public function adminMenu($slug=null, $title=null, $url='#!', $parent=null, $level=99, $icon=null, $status=1)
    {
        if (isset($slug)) {

            $menu = compact('slug', 'title', 'url', 'parent', 'level','icon', 'status'); 
            static::$admin_menus[] = $menu; 
            

            // if ($admin_menus = Setting::where(['key' => '_admin_menu_custom'])->first()) {
            //     $menus = json_decode($admin_menus->params, true);
            //     $menus[] = $menu; 
            //     $admin_menus->params = json_encode($menus);
            //     $admin_menus->update();
            // }

            return; 
        } 
        try {
            $custom = Setting::firstOrCreate(['key' => '_admin_menu_custom']); 
            
        } catch (\Exception $e) {
            $custom = collect(); 
        }

        

        foreach (static::$admin_menus as $key => $menu) {  
            $slug = $menu['slug'];

            if (isset($custom->params)) {
                $params = json_decode($custom->params, true);

                if (isset($params[$slug])) { 
                    static::$admin_menus[$key]['parent'] = empty($params[$slug]['parent']) 
                                                                ? null : $params[$slug]['parent'];
                    static::$admin_menus[$key]['status'] = $params[$slug]['status'];
                    static::$admin_menus[$key]['level']  = $params[$slug]['level']; 
                } else {
                    static::$admin_menus[$key]['status'] = 0; 
                }   
            } 
        }
 
        return collect(static::$admin_menus)->sortBy('level');
    }

// end of RegisterClass
}
