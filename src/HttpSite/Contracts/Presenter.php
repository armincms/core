<?php 
namespace Core\HttpSite\Contracts;

use Illuminate\Http\Request;

interface Presenter
{
	public function present(Request $request, string $identifier = null); 
}
