<?php
namespace Core\HttpSite\Http\Controllers; 
  
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Core\HttpSite\Http\Requests\FallbackRequest;  
use Core\HttpSite\Contracts\SiteRequest;
use Core\Document\Document;

class FallbackController extends SiteController
{      
    public function getContent(SiteRequest $request, Document $document)
    {     
    	return 'The requested URL [URL] was not found on this server';
    	throw new NotFoundHttpException('The requested URL [URL] was not found on this server');
    }
}