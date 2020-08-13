<?php
namespace Core\HttpSite\Http\Controllers; 
   
use Core\HttpSite\Contracts\SiteRequest; 
use Core\Document\Document;

class WebsiteController extends SiteController
{  
    public function getContent(SiteRequest $request, Document $document)
    {  
    	$site = $request->site();
    	$description = $this->getDescription($site);
    	$title 	= $this->getTitle($site);
    	$tags 	= $this->getTags($site); 

        $document->title($title);
        $document->description($description);
        $document->meta('title', $title); 
        $document->meta('description', $description); 
        $document->meta('keywords', $tags); 

        return '';
    }

    public function getTitle($site)
    {
    	if($title = trim($site->title())) {
    		return $title;
    	} 
    	
    	return option('_site_title', collect())->get(app()->getLocale());
    }

    public function getDescription($site)
    { 
    	if($description = trim($site->description())) {
    		return $description;
    	}
    	
    	return option('_site_description', collect())->get(app()->getLocale());
    }

    public function getTags($site)
    {  
    	return option('_site_tags', collect())->get(app()->getLocale());
    }
}