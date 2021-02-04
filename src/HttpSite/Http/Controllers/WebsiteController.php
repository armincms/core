<?php
namespace Core\HttpSite\Http\Controllers; 
   
use Core\HttpSite\Contracts\SiteRequest; 
use Core\Document\Document;

class WebsiteController extends SiteController
{  
    public function getContent(SiteRequest $request, Document $document)
    {  
    	$site = $request->site();
    	$description = $this->getSiteDescription($site);
    	$title 	= $this->getSiteTitle($site);
    	$tags 	= $this->getSiteKeywords($site); 

        $document->title($title);
        $document->description($description); 
        $document->keywords('keywords', $tags); 

        return '';
    }

    public function getSiteTitle($site)
    {
    	if($title = trim($site->title())) {
    		return $title;
    	} 

        return $this->setting('_app_title_'); 
    }

    public function getSiteDescription($site)
    { 
    	if($description = trim($site->description())) {
    		return $description;
    	}
    	

        return $this->setting('_app_description_'); 
    }

    public function getSiteKeywords($site)
    {  
        return $this->setting('_app_tags_'); 
    }

    public function setting($key)
    {
        if(! isset($this->options)) {
            $this->options = option()->tag('generals');
        } 

        return $this->options[$key] ?? '';
    }
}