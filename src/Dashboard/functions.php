<?php   

if(! function_exists('armin_dropdown')) {
    /**
     * Make dropdown from array of items .
     * 
     * @param  array   $parents  
     * @param  Clusre  $get_child   [need callabel function for find child(s) of item]
     * @param  Clusre  $drawer  [need callabel function for draw an item and its child(s)]
     * @param  integer $depth   [depth of parent]
     * @return string          
     */
    function armin_dropdown(array $parents, $get_child, $drawer, $depth=0)
    {     
        $dropdowned = ''; 

        foreach ($parents as $parent) {   

            if (! is_callable($get_child)) 
                return "Missing Callbak Function For Fetch Childs Of Menu [id: {$parent->id}] In Depth {$depth}.";

            if (! is_callable($drawer)) 
                return "Missing Callbak Function For Draw Menu [id: {$parent->id}] In Depth {$depth}."; 

            // find childs of  mneu
            $childs = call_user_func_array($get_child, [$parent]);

            // print created childs
            ob_start(); 
                echo call_user_func_array('armin_dropdown', [$childs, $get_child, $drawer, $depth+1]);
            $printed = ob_get_clean();
            // recall
            $dropdowned .= call_user_func_array($drawer, [$parent, $printed, $depth, $childs]);  
        }   

        return $dropdowned;
    }
}

if (! function_exists('register_admin_menu')) { 
    /**
     * Register admin bigMenu item.
     *
     * @param  string  	$slug
     * @param  string  	$title
     * @param  integer 	$title  
     * @param  array  	$options 
     * 
     * @return \Lavary\Menu\Item;
     */
    function register_admin_menu($slug=null, $title=null, $order=99, $options=[])
    {   
    	return admin_menus()->add(
    		armin_trans($title), ['id' => $slug] + (array) $options 
    	)->nickName($slug)->data(compact('order')); 
    }
}

if (! function_exists('admin_menus')) { 
    /**
     * Retrieve all admin bigside menus. 
     * 
     * @return \Lavary\Menu\Item;
     */
    function admin_menus()
    {     
    	return \Menu::get('bigMenu'); 
    }
}

if (! function_exists('admin_menu')) { 
    /**
     * Retrieve specific admin bigside menu item. 
     * 
     * @return \Lavary\Menu\Item;
     */
    function admin_menu($slug)
    {   
    	return admin_menus()->get($slug); 
    }
}
