<?php  
namespace Core\HttpSite\Http\Requests; 

use Core\Template\Contracts\Repository;
use Core\HttpSite\RouteHelper;  
use Core\HttpSite\UrlHelper;  
use Core\HttpSite\Site;  

trait InteractsWithSite
{  
    protected $template;
    protected $site;

    public function site()
    {  
        if(! isset($this->site)) {
            $this->site = $this->findSite();
        }

        return $this->site;
    } 

    protected function findSite()
    { 
        $sites = $this->domainSites();

        if($sites->count() > 1) {
            $sites = $this->filterSitesByUri($sites);
        }

        $homes = $sites->filter->isHome();  

        return $homes->count() ? $homes->first() : $sites->first(); 
    }

    protected function domainSites()
    {
        $domain     = $this->getDomain();
        $directory  = $this->getDirectory(); 

        return app('armin.site')->collect()->filter(function($site, $name) use ($domain, $directory) {
            $domain = $site->domains()->first(function($siteDomain, $locale) use ($domain) {  
                return UrlHelper::assertEqualDomain($siteDomain, $domain);
            });  

            // if not set domain for site
            if($site->domains()->count() && is_null($domain)) {
                return false;
            }

            return UrlHelper::trimSlash($site->directory()) === $directory;
        }); 
    } 

    public function filterSitesByUri($sites)
    {
        return $sites->filter(function($site) {
            $uri = $this->getUri();

            return empty($uri) || $site->findComponentByRoute($uri);
        });
    }

    public function component()
    {  
        return $this->site()->findComponentByRoute($this->getUri());
    } 

    public function getUri()
    {
        $uri = UrlHelper::trimSlash($this->route()->uri());

        return UrlHelper::trimSlash(str_after($uri, $this->getDirectory()), $this->getDirectory());
    }

    public function relativeUrl($decode = false)
    { 
        $pattern = RouteHelper::routeToPattern($this->getUri());

        preg_match("/$pattern$/", $this->decodedPath(), $matches);

        $url = $matches[0] ?? '/';
 
        return $decode ? urldecode($url) : urlencode($url);
    }

    protected function getDomain()
    {
        return $this->getHost()?: UrlHelper::defaultHost();
    }

    public function getDirectory()
    {
        return UrlHelper::trimSlash($this->route()->getPrefix());
    }  

    public function template()
    {         
        return tap($this->retrieveTemplate(), function($template) {
            if($template = app('template.repository')->template($template)) { 
                $namespace = template_hint_key($template->name());
                $directory = $template->directory();

                app('view')->getFinder()->addNamespace($namespace, $directory); 
                app('translator')->getLoader()->addNamespace($namespace, $directory); 
            } else { 
                throw new \Exception('Not Found Template');
            }
        });  
    }  

    protected function retrieveTemplate()
    { 
        return app('template')->make($this->site()->template ?: default_template()); 
    } 
}