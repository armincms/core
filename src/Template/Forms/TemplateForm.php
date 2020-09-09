<?php 
namespace Core\Template\Forms; 

use Core\Crud\Forms\ResourceForm; 
use Core\Crud\Concerns\HasSelectable;
use File;

class TemplateForm extends ResourceForm  
{  
	use HasSelectable;

	protected $uploadPath = 'blogs';

	protected $name = 'blog';

	public function build()
	{           
		$css = File::exists($this->path("custom")) ? File::get($this->path("custom")) : null;

		$this 
			->selectable('plugins[]', 'template::title.plugins', $this->availabelPlugins(), $this->loadedPlugins(), [], [
				'multiple' => true
			]) 
			->field('textarea', 'css', false, 'css', null, $css, [
				'style' => 'text-align:left;direction:ltr;font-family:tahoma;'
			])
			->pushScript('codemirror-js', '/admin/rtl/js/codemirror/lib/codemirror.js', true)  
            ->pushScript('css-js', '/admin/rtl/js/codemirror/mode/css/css.js', true)
            ->pushScript('javascript-js', '/admin/rtl/js/codemirror/mode/javascript/javascript.js', true)
            ->pushScript('scroll-js', '/admin/rtl/js/codemirror/addon/scroll/simplescrollbars.js', true)
            ->pushScript('editor', ' 
				var editor = CodeMirror.fromTextArea(document.getElementById("css"), {
				lineNumbers: true,
				mode: "css",
				theme: "monokai",
				selectionPointer: true, 
				}).setSize(null, 500);')
            ->pushStyle('codemirror-css', '/admin/rtl/js/codemirror/lib/codemirror.css', true)
            ->pushStyle('codemirror-theme', '/admin/rtl/js/codemirror/theme/monokai.css', true)
            ->pushStyle('scroll-css', '/admin/rtl/js/codemirror/addon/scroll/simplescrollbars.css', true);
	}   

	public function availabelPlugins()
	{
		return armin_plugins()->map(function($plugin) {
			return [
				'id' => $plugin->name(),
				'label' => $plugin->label(),
			];
		})->values()->all();
	}  

	public function transformCss($css)
	{  
        File::put($this->path("custom"), $css);
        File::put($this->path("custom.min"), minify_css($css));

        return $css;
		
	}

	public function transformPlugins($plugins)
	{  
		$template = $this->getModel()->name();



		option()->{"_{$template}_plugins"} = collect($plugins)->filter()->values()->all();  
	}

	public function loadedPlugins()
	{
		$template = $this->getModel()->name();

		return collect(option("_{$template}_plugins"))->all();
	}

	public function path($file)
	{
		$name = $this->model->name();

		return template_path("{$name}/{$file}.css");
	}
 

	public function generalMap()
	{ 
		return ['css'];
	}
	public function translateMap()
	{
		return [];
	}
	public function relationMap()
	{
		return [];	
	}  

}
