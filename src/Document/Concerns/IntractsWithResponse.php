<?php 
namespace Core\Document\Concerns;


trait IntractsWithResponse
{

	public function toResponse($status = 200, array $headers = [])
	{  
		return response($this->toHtml(), $status, $headers);
	}

	public function toJsonResponse($status = 200, array $headers = [])
	{  
		return response()->json($this->toArray(), $status, $headers);
	} 
}
