<?php 
namespace Core\Document\Concerns;

use Core\Document\Asset;

trait IntractsWithAsset
{ 
	/**
	 * Listt of sheets.
	 * 
	 * @var array
	 */
	protected $sheets = [];     


	public function pushSheet(Asset $asset, int $order = 999)
	{  
		$this->sheets[] = compact('asset', 'order'); 

		return $this;
	} 

	public function sheets()
	{
		return collect($this->sheets)->sortBy('order')->pluck('asset');
	}  

	public function headerSheets()
	{
		return $this->sheets()->filter(function($asset) {
			return $asset->toHeader();
		});
	}  

	public function footerSheets()
   	{
		return $this->sheets()->filter(function($asset) {
			return ! $asset->toHeader();
		});
   	}   
}
