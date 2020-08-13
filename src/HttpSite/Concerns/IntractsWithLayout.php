<?php 
namespace Core\HttpSite\Concerns;

use Core\Document\Document;

trait IntractsWithLayout
{ 

    public function layout(Document $document, string $layoutKey)
    {
        return tap(the_layout($layoutKey), function($layout) use ($document) {
            $this->loadLayoutPlugins($layout, $document); 
        }); 
    }

    public function loadLayoutPlugins($layout, $document)
    { 
    	$plugins = (array) $layout->plugins();

    	foreach ($plugins as $plugin => $version) { 
    		$document->pushPlugin($plugin, $version);
    	} 

    	$document->pushSheet($layout->css());

    	return $this;
    }

    public function firstLayout(Document $document, $layout)
    {
        $layouts = func_get_args();

        array_shift($layouts);

        $layout = call_user_func_array('layout_first', $layouts);

        return tap($layout, function($layout) use ($document) {
            $this->loadLayoutPlugins($layout, $document); 
        });  
    }
}