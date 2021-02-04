<?php 
namespace Core\Document;
 
use Core\Template\Contracts\Template;
use Core\Document\Contracts\HtmlMetaBuilder;
use Core\Document\Concerns\{IntractsWithPlugin, IntractsWithModule, IntractsWithTemplate, InteractsWithMetaData};
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Arrayable; 
use HtmlMeta;
use HtmlPlugin;

class HtmlDocument extends Document implements Htmlable, Arrayable
{     
	use InteractsWithMetaData,  IntractsWithPlugin, IntractsWithModule, IntractsWithTemplate;

	/**
	 * Document Direction.
	 * 
	 * @var string
	 */
	protected $direction = 'rtl';  

	public function direction(string $direction = null)
	{  
		if(is_null($direction)) {
			return $this->direction;
		}

		$this->direction = $direction;

		return $this;
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

		event(new Events\Rendering($this)); 

		return $this->template->setBody($this->content)->setModules($this->modules())->toHtml(); 
	} 

	public function render()
	{ 
		return $this->toHtml();
	}
}
