<?php 
namespace Core\HttpSite\Http\Requests; 


class FallbackRequest extends SiteRequest
{ 
	public function site()
	{ 
		if($site = parent::site())
		{ 
		 	return $site;
		}

        return app('armin.site')->last(function($site, $name) {     
            return $site->isFallback();
        }); 
	}
}
