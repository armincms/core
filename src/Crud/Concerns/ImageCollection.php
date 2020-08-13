<?php 
namespace Core\Crud\Concerns;

use Illuminate\Support\Collection;

class ImageCollection extends Collection
{
	public function masterImage()
	{
		return $this->first(function($image) {
			return (int) $image->get('master', 0);
		}, $this->first());
	} 
}
