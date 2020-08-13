<?php 
namespace Core\Crud\Concerns;

use Core\HttpSite\Contracts\Linkable;
use Core\Language\Contracts\Multilingual;
use Illuminate\Support\HtmlString;

trait HasPermalink
{ 
	public function previewField()
	{ 
		if($this->model instanceof Linkable) {  
			$multilingual = $this->model instanceof Multilingual;

			$url = $this->model->relativeUrl(true);
			$fullUrl = [$this->model->url(true)];

			if($multilingual) {
				$url = [];
				$fullUrl = [];

				foreach (language() as $language) {
					$url[$language->alias] = $this->model->relativeUrl(true, $language->alias);
					$fullUrl["data-url-{$language->alias}"] = $this->model->url(true, $language->alias);
				}
			} 

			$this->field('text', 'url', $multilingual, 'preview', [
				'label' => new HtmlString('<i class="icon-eye preview-resource"></i>'),
				'attributes' => ['role' => 'preview-resource']
			], $url, [
				'disabled' => 'disabled', 
				'class' => 'input-unstyled ltr',
			] + $fullUrl); 
		}

		$this->pushScript('preview-resource', 'jQuery(document).ready(function($){$("label[role=preview-resource]").click(function(event) {var $language=$("[role=translatable-field]").val() || "' .default_locale(). '";$url=$(this).siblings("input:visible:first").data("url-" +$language);window.open($url, "preview");});});');
		
		return $this;
	}
}