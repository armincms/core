<?php 
namespace Core\Document;
 
use Armincms\Template\Contracts\Template;
use Core\Document\Contracts\HtmlMetaBuilder;
use Core\Document\Concerns\IntractsWithPlugin; 
use Core\Document\Concerns\IntractsWithModule;
use Core\Document\Concerns\IntractsWithTemplate;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Arrayable; 
use HtmlMeta;
use HtmlPlugin;

class HtmlDocument extends Document implements Htmlable, Arrayable
{     
	use IntractsWithPlugin, IntractsWithModule, IntractsWithTemplate;

	/**
	 * Document Direction.
	 * 
	 * @var string
	 */
	protected $direction = 'rtl';  

	/**
	 * List Of Html Meta.
	 * 
	 * @var string
	 */
	protected $metas = []; 

	public function direction(String $direction = null)
	{  
		if(is_null($direction)) {
			return $this->direction;
		}

		$this->direction = $direction;

		return $this;
	}  

	public function meta(String $key, String $value = null, array $options = null)
	{
		$this->metas[] = HtmlMeta::meta($key, $value, $options);

		return $this;
	}

	public function getMetaString()
	{
		return collect($this->metas)->filter()->implode('');
	}

	public function toArray()
	{
		return [
			'title'       => $this->title,
			'description' => $this->description,
			'locale'      => $this->locale,
			'charset'     => $this->charset,
			'content'     => $this->toHtml(),
		];
	}

	public function toHtml()
	{   
		$this->loadTemplatePlugins();
		$this->loadTemplateStyleSheets();
		$this->loadModulePlugins();
		$this->loadModulesStyleSheet();

		return $this->template->setBody($this->content)->setModules($this->modules())->toHtml(); 
	} 

	public function render()
	{ 
		return $this->toHtml();
	}
}
