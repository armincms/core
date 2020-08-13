<?php 
namespace Core\Document;
  
use Core\Document\Contracts\Document as DocumentInterface;
use Core\Document\Contracts\Renderable;
use Core\Document\Concerns\IntractsAdditionalData; 
use Core\Document\Concerns\IntractsWithResponse; 
use Illuminate\View\Compilers\CompilerInterface;

abstract class Document extends CompilerEngine implements DocumentInterface, Renderable 
{   
	use IntractsWithResponse;

	/**
	 * Contains the character encoding string.
	 * 
	 * @var string
	 */
	protected $charset = 'utf-8';

	/**
	 * Used Language of Docuemnt.
	 * 
	 * @var string
	 */
	protected $locale = 'fa';

	/**
	 * Main Content of Document.
	 * 
	 * @var string
	 */
	protected $content = '';

	/**
	 * Title of Document.
	 * 
	 * @var string
	 */
	protected $title = '';

	/**
	 * Description of Document.
	 * 
	 * @var string
	 */
	protected $description = ''; 

	/**
	 * Stting's of Document.
	 * 
	 * @var string
	 */
	protected $options = [];    
	
	public function charset(String $charset)
	{
		$this->charset = $charset;

		return $this;
	}

	public function getCharset()
	{
		return $this->charset;
	}

	public function locale(String $locale)
	{ 
		$this->locale = $locale;

		return $this;
	} 

	public function getLocale()
	{
		return $this->locale;
	}

	public function content()
	{ 
		return $this->content; 
	} 

	public function setContent(String $content = null)
	{
		$this->content = $content;

		return $this; 
	} 

	public function title(String $title = null)
	{
		if(is_null($title)) {
			return $this->title;
		}

		$this->title = $title;

		return $this; 
	}

	public function description(String $description = null)
	{
		if(is_null($description)) {
			return $this->description;
		}

		$this->description = $description;

		return $this; 
	}   
	
	public function parseOptions($options)
	{
		collect($options)->each(function($option, $key) {
			if(method_exists($this, $key)) {
				call_user_func_array([$this, $key], [$option]);
			} else if ($key == 'content') {
				$this->setContent($option);
			} else {
				$this->setOption($key, $option);
			} 
		});

		return $this;
	}

	public function setOpion(String $key, $value = null)
	{ 
		$this->options[$key] = $value;

		return $this;
	}

	public function getOpion(String $key, $default = null)
	{
		return array_get($this->options, $key, $default);
	}

	abstract public function render();
}
