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

        return $this->setting('_app_title_'); 
    }

    public function getDescription($site)
    { 
    	if($description = trim($site->description())) {
    		return $description;
    	}
    	

        return $this->setting('_app_description_'); 
    }

    public function getTags($site)
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