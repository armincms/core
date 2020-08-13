<?php 
namespace Core\HttpSite\Concerns; 
use Core\Language\Contracts\Multilingual;


trait Visiting
{ 
	public function increaseVisiting()
	{ 
		$this->update([
			'hits' => (int) $this->hits + 1
		]);

		if($this instanceof Multilingual) {
			$translate = $this->translates->firstWhere('language', \App::getLocale());

			if($translate) {
				$translate->update([
					'hits' => (int) $translate->hits + 1
				]);
			}
		}

		return $this;
	}

	public function decreaseVisiting()
	{

		$this->update([
			'hits' => (int) $this->hits - 1 ?: 0
		]);

		if($this instanceof Multilingual) {
			$translate = $this->translates->firstWhere('language', \App::getLocale());

			if($translate) {
				$translate->update([
					'hits' => (int) $translate->hits - 1 ?: 0
				]);
			}
		} 
	}

}