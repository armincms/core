<?php 
namespace Core\Crud\Concerns;


trait HasTextEditor
{
	private $editorId = null;

    public function textEditor($name='image', $trans=false, $label=[], $value=null, $options=[], $attrs=[], $wrapper_attrs=[], $help=null)
    {  
 
    	$attrs['data-tinymce'] = json_encode($this->options($options)); 
    	$attrs['style'] = 'height : 0; position: absolute';
    	$attrs['class'] = 'hidden';

    	$this->field(
    		'textarea', $name, $trans, $label, [], $value, $attrs, $wrapper_attrs, $help
    	);

    	$this->pushScript('tinymce', '/admin/rtl/js/tinymce/tinymce.min.js', true);
    	foreach (language() as $language) {
    		$this->pushScript(
	    		'tinymce-' . $name, $this->initializer($options, $name)
	    	);
    	}
    	

    	return $this;
    }

    public function getEditorId($name)
    {
    	if(! isset($this->editorId)) {
    		$this->editorId = md5($name);
    	}

    	return $this->editorId; 
    }

    public function initializer($options, $name)
    { 
    	return view('admin-crud::components.tinymce-scripts', compact('options'));
    	
    }

    public function options($options = [])
    {
    	$options = is_array($options) ? $options : func_get_args();

    	return $this->getDefaults($options)->merge(collect($options));
    }

    public function getDefaults($options)
    {
    	$options['min_height'] 	= 200;
    	$options['max_height'] 	= 600;
    	$options['plugins'] = $this->mergePlugins(array_get($options, 'plugins', []));
    	$options['menubar'] =  false;
    	$options['convert_urls'] =  false;
		$options['toolbar_items_size']  =  'small'; 
		$options['toolbar']  = $this->toolbars(array_get($options, 'toolbar', []));

		return collect($options);
    }

    public function mergePlugins($plugins)
    {
    	if(empty($plugins)) {
    		return $this->defaultPlugins();
    	}
    	if(isste($plugins['only'])) {
    		return collect($plugins['only']);
    	}
    	if(isset($plugins['except'])) {

    		return $this->defaultPlugins()->filter(function($plugin) use ($plugins) {
    			return in_array($plugin, $plugins['except']);
    		});
    	}

 		return $this->defaultPlugins()->merge((array) $plugins);
    }

    public function defaultPlugins()
    {
    	return [
		    "advlist", "autolink", "autosave", "link", "lists", "charmap", "print",
		    "preview", "hr", "anchor", "pagebreak", "searchreplace", "wordcount",
		    "visualblocks", "visualchars", "code", "fullscreen", "insertdatetime",
		    "media", "nonbreaking", "table", "contextmenu", "directionality", 
		    "emoticons", "template", "textcolor", "paste", "textcolor", 
		    "colorpicker", "textpattern", "image", "imagetools"
	  	];
    }

    public function toolbars($keys)
    {
    	$tools = [];

    	$this->defaultToolbars()->filter(function($key) use ($keys) {
    		if(isset($keys['only'])) {
    			return in_array($key, $keys['only']);
    		}
    		if(isset($keys['except'])) {
    			return ! in_array($key, $keys['except']);
    		}

    		return true;
    	})->map(function($key, $tool) use (&$tools) {
    		data_set($tools, $key, $tool);
    	});

    	$length = count($tools); 

    	return collect($tools)->mapWithKeys(function($tools, $key) use (&$length) {
    		if(! is_numeric($key)) {  
    			return [$length++ =>  $key];
    		}
   			if(is_string($tools)) {
   				return [$key => $tools];
   			} 

   			return [$key => $this->toolsToolbar($tools)];
   		})->values()->toArray(); 
    }

    public function toolsToolbar($tools)
    {
    	$string = '';

    	foreach ($tools as $key => $tool) { 
    		if(is_array($tool)) {
				$string .= (empty($string) ? '' : '| ') . $this->toolsToolbar($tool);
			} else {
				$string .= "{$tool} ";
			}
    	}

   		return preg_replace('/\s+/', ' ', $string);
    }

    public function defaultToolbars()
    { 
    	return collect([ 
    		"styleselect"	 => "0.0.0",
    		"formatselect"   => "0.0.1",  
    		"fontselect"     => "0.0.2",  
    		"fontsizeselect" => "0.0.3", 
    		"image"          => "0.1.0",    
    		"media"          => "0.1.1",    
			"uploader"       => "0.1.2",
			"pagebreak"      => "0.1.3",  
    		"preview"        => "0.1.4",  
			"fullscreen"     => "0.1.5",   
			"forecolor"      => "1.0.0",  
			"backcolor"      => "1.0.1", 
			"ltr"            => "1.0.2",  
			"rtl"            => "1.0.3",  
			"bold"           => "1.1.0",  
			"italic"         => "1.1.1",  
			"underline"      => "1.1.2",  
			"strikethrough " => "1.1.3", 
			"alignleft"      => "1.2.0",  
			"aligncenter"    => "1.2.1",  
			"alignright"     => "1.2.2",  
			"alignjustify"   => "1.2.3",    
    		"cut"            => "1.3.0",  
    		"copy"           => "1.3.1",  
    		"paste"          => "1.3.2",  
			"table"          => "1.3.3",
    		"outdent"        => "2.0.2",  
    		"indent"         => "2.0.3", 
    		"undo"           => "2.1.0",  
    		"redo"           => "2.1.1",   
    		"blockquote"     => "2.0.4", 
    		"bullist"        => "2.0.0",  
    		"numlist"        => "2.0.1",  
    		"link"           => "2.1.0",  
    		"unlink"         => "2.1.1",  
    		"anchor"         => "2.1.2",    
    		"code"           => "2.1.3",  
    		"insertdatetime" => "2.1.4",   
			"print"          => "2.2.0",  
			"template"       => "2.2.1",  
			"hr"             => "2.2.2",  
			"removeformat"   => "2.2.3",  
			"subscript"      => "2.2.4",  
			"superscript"    => "2.2.5",  
			"charmap"        => "2.2.6",    
			"visualchars"    => "2.2.7",  
			"visualblocks"   => "2.2.8",  
			"nonbreaking"    => "2.2.9",   
			"restoredraft"   => "2.2.10",
    		"searchreplace"  => "2.2.11",
    	]); 
    } 
}