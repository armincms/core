<?php
namespace Core\HttpSite\Http\Controllers; 
  
use Core\HttpSite\Http\Requests\FallbackRequest;  
use Core\HttpSite\Contracts\SiteRequest;
use Core\Document\Document;

class FallbackController extends SiteController
{      
    public function getContent(SiteRequest $request, Document $document)
    {    
    	throw new \Exception('Route Mismatched...!', 404);
    }
}