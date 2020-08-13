<?php 
namespace Core\Module;
 
use File;
use Storage; 

class CssWriter  
{ 
	protected $css = [];
	protected $customCss = [];
	protected $modules;

	public function __construct(ModuleCollection $modules)
	{
		$this->modules = $modules->modules();
	}

	public function __destruct()
	{
		$this->write();
	}

    function write()
    {     
        foreach ($this->modules as $module) { 
            $instance = $module->getModule();
            $this->cssGroup = 'main';   

            switch ($instance->getConfig('_theme.background')) {
                case 'color': 
                case 'gradient':
                    $this->gradientCss(
                        "div.module{$instance->id}", $instance->getConfig('_theme')
                    );
                    break;
                case 'image':
                    $this->imageCss(
                        "div#module{$instance->id}", $instance->getConfig('_theme')
                    );
                    break;
                
                default:
                    // code...
                    break;
            }    

            if($class = theme_class($instance->getConfig('_class'))) { 
                $css = array_get($class, 'css');

                if(! empty($css)) { 
                    $this->customCss[$instance->getConfig('_class')]['targets'][] = "#module{$instance->id}";
                    $this->customCss[$instance->getConfig('_class')]['css'] = $css; 
                }
            } 

            foreach (config('armin.template.responsive') as $responsive => $setting) {
                $this->cssGroup = $responsive;

                $this->offsetCss(
                    'padding', "div.module{$instance->id}", $instance->getConfig("{$responsive}.padding")
                ); 
                $this->offsetCss(
                    'margin', "div#module{$instance->id}", $instance->getConfig("{$responsive}.margin")
                );  
            }
        }

        $this->writeToFile();
    }

    public function writeToFile()
    { 
        $this->clean();

        $this->pushModuleAssets(); 
        $this->pushBuildedCss();
        $this->pushCustomCss();

        $this->publish(); 
    }

    public function pushModuleAssets()
    {
        $this->modules->map(function($module) {
            return collect($module->assets())->push($module->layout()->css())->all();  
        })->flatten()->unique(function($asset) {
            return $asset->path();
        })->each(function($asset) { 
            $path = $asset->path(); 

            if(File::exists($path)) { 
                $this->appendTo(
                    minify_css(File::get($path)), 'main'
                );
            } 
        });
    } 

    public function pushBuildedCss()
    { 
        foreach ($this->css as $group => $css){ 
            foreach ($css as $key => $positions) {
                $key = $this->originalCssKey($key); 

                $this->appendTo(
                    $this->addToClass($this->className((array) $positions), $key), $group
                ); 
            }   
        }  
    }

    public function pushCustomCss()
    { 
        
        foreach ($this->customCss as $value) { 
            $this->appendTo($this->customCss($value['targets'], $value['css']), 'main'); 
        } 
    }

    public function customCss($classes, $style)
    {     
    	$css = preg_replace_callback('/(}|{)([^@{}%()\\\\\/]+){/', function($matches) use ($classes) {
    		$replaced = [];

    		foreach ($classes as $name) {
    			$replaced[] = $name . str_replace(',', ",{$name}", $matches[2]);
    		} 
    		
    		return $matches[1].implode(',', $replaced)."{";

    	}, $style);

    	if(starts_with($style, '{')) {
    		$css = implode(',', (array) $classes) . $css;
    	}  
        
        return "\n/*CUSTOM CLASS*/\n{$css}/*END CUSTOM CLASS*/\n"; 
    }

    function offsetCss($type = 'padding', $class, $data)
    {
        foreach (['top', 'right', 'left', 'bottom'] as $position) {
            $size = array_get($data, $position);

            if(is_numeric($size)) {
                $size = (int) $size;

                $this->pushCss("[{$class}]{$type}-{$position}:{$size}px!important");
            } 
        } 
    }

	public function colorCss($class, $data)
	{  
		if (isset($data['id']) && $color = $this->getColor($data['id'])) {
			$percent = $this->makePercent((int) array_get($data, 'percent', 100)); 

			$this->pushCss("[ $class]background:rgba({$color['rgb']},{$percent})");
		}  
	} 

	public function gradientCss($class, $data)
	{  
		$color 	= array_get($data, 'color');
		$percent 	= $this->makePercent(array_get($data, 'percent', 100));

		$gradient 	= array_get($data, 'gradient');
		$gradientPercent 	= $this->makePercent(array_get($data, 'gradient_percent', $percent)); 

		if(empty($color)) return;

		$this->colorCss($class, ['id' => $color, 'percent' => array_get($data, 'percent', 100)]);   

		if(empty($gradient)) return;

		$css  = array_get($data, 'repeating', 0) == 0? "" : "repeating-";
		$css .= (boolean) array_get($data, 'linear', false) ? 'linear' : 'radia'; 
		$css .= "-gradient(";
		if(array_get($data, 'type', 'linear') == 'linear') { 
			$css .= (int) array_get($data, 'degree', 45);
			$css .= "deg,";
		}
		$css .= "rgba(";
			$css .= $this->getColor($color)['rgb'];
		$css .= ",{$percent}),"; 
		$css .= "rgba(";
			$css .= $this->getColor($gradient)['rgb'];
		$css .= ",{$gradientPercent})"; 
		$css .= ") fixed;";

		$webkitCss = '';
		foreach (['-webkit-', '-o-', '-moz-'] as $prefix) {  
			$webkitCss .= "background:{$prefix}{$css}";  
		}     

		$this->pushCss("[ {$class}] {$webkitCss}");
	}

    function imageCss($class, $data)
    {  
		$this->gradientCss(
			str_replace('#', '.', $class), array_merge($data, ['linear' => 1])
		); 

		if ($image = theme_image(array_get($data, 'image'))) {  
			$css =  $this->animateImage(
						array_get($image, 'src'), 
						(int) array_get($image, 'top'), 
						(int) array_get($image, 'left'),  
						array_get($data, 'size'), 
						array_get($data, 'animation')
					);

			$this->pushCss("[$class]{$css}");
		}     
    }  

	public function getColor($id)
	{
		$themeColor = theme_color($id);

		return [
			'rgb' 	=> array_get($themeColor, 'rgb', '0,0,0'), 
			'hex' 	=> array_get($themeColor, 'code', '#ff'), 
			'name' 	=> array_get($themeColor, 'name', 'white')
		]; 
	} 

	public function getImage($id)
	{  
		return theme_image($id);
	} 

	public function makePercent($number)
	{
		return (float) ((int) $number / 100);
	}

	public function animateImage($url, $top='top', $left='left', $size='cover', $motion='scroll') {
		$calculateTop = $this->getImagePosition($top, 'top');
		$calculateLeft = $this->getImagePosition($left, 'left'); 

		if($top + $left == 100) {
			$calculateLeft = '';
			$calculateTop = 'center';
		} else if(is_numeric($calculateTop) && is_numeric($calculateLeft)) {
			$calculateTop 	.= '%';
			$calculateLeft 	.= '%';
		} else if (! is_numeric($calculateLeft)) {
			$diff = 50 - $top;

			if($top != 100 && $top != 0) {
				$top = $diff > 0 ? $top + $diff : $top - $diff;
			}
			 
			$calculateTop = $this->getImagePosition($top, 'top');
		} else { 
			$diff = 50 - $left;

			if($left != 100 && $left != 0) {
				$left = $diff > 0 ? $left + $diff : $left - $diff;
			}

			$calculateLeft = $this->getImagePosition($left, 'left');
		}

		if($motion == 'smooth') {
			$motion = '';
		}
		
		$css  = "background: url('{$url}') {$calculateLeft} {$calculateTop} no-repeat {$motion};\n\t";  
		$css .= "background-size:{$size}";

		return $css;
	} 

    public function getImagePosition($value, $anchor, $prev= null)
    { 
        // return $value .'%'; 

        switch ($value) {
            case 100:
                return ($anchor == 'left') ? 'right' : 'bottom'; 
            case 0:
                return ($anchor == 'left') ? 'left'  : 'top';  
            case 50: 
                return 'center';   
            default: 
                return $value; 
        }
    }  
	  

    public function pushCss($css)
    { 
        $appended = [];

        if(preg_match('/^\[(.*)\](.*)/', $css, $matches)) {  
            $appended = explode(',', trim($matches[1]));
            $css = $matches[2]; 
        } else {
            $appended[] = '';
        }

        foreach ($appended as $value) {
            
            $styleKey = trim($value);

            $key = (isset($this->cssGroup) ? "{$this->cssGroup}." : '') .$this->normalizeCssKey($css); 

            $classes = array_get($this->css, $key, []); 

            if (! in_array($styleKey, $classes)){
                array_unshift($classes, $styleKey); 
            } 

            array_set($this->css, $key, $classes);
        }

    }

    public function normalizeCssKey($cssKey)
    {
        return str_replace('.', '-dot-', $cssKey);
    }

    public function originalCssKey($cssKey)
    {
        return str_replace('-dot-', '.', $cssKey);
    }  
    
    public function clean()
    { 
        if(File::exists($this->sheetPath())) {
        	File::delete($this->sheetPath());
        } 

        File::put($this->sheetPath(), '');
    }  

    public function sheetPath()
    {
    	return module_path('stylesheet.min.css');
    }

    public function publish()
    {   
        foreach (['main' => []] + config('armin.template.responsive') as $responsive => $setting) {
        	$path = module_path("{$responsive}.css");

            if(File::exists($path)) {
                $content = File::get($path); 

                File::delete($path);

                $css = ($responsive == 'main')
                                ? $content
                                : "@media screen and (min-width:{$setting['min-size']}px){\n\t{$content}\n}";
                
                File::put(
                	$this->sheetPath(), minify_css(File::get($this->sheetPath()).PHP_EOL.$css)
                );  
            }   
        }     
    }

     
    public function appendTo($content, $name)
    {  
    	$path = module_path("{$name}.css");

    	if(! File::exists($path)) {
    		File::put($path, '');
    	}

        File::put($path, File::get($path).PHP_EOL.$content);  
    } 

    public function className($positions)
    { 
        $name = implode(',', $positions);

        return str_replace('#body', 'body', $name);
    }

    public function addToClass($class, $css)
    {
        $css = trim($css, ';');

        return "{$class} {\n\t{$css}; \n}\n";
    }

}