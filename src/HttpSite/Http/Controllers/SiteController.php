<?php
namespace Core\HttpSite\Http\Controllers; 
 
use Illuminate\Routing\Controller; 
use Core\HttpSite\Contracts\TemplateRepository;
use Core\HttpSite\Contracts\Resourceable;
use Core\HttpSite\Events\PreparingSiteEvent; 
use Core\HttpSite\Events\PreparingSiteModulesEvent; 
use Core\HttpSite\Events\PreparingSiteMetasEvent; 
use Core\HttpSite\Events\PreparingSiteContentEvent;  
use Core\HttpSite\Events\PreparingSiteTemplateEvent;  
use Core\HttpSite\Contracts\SiteRequest;
use Core\HttpSite\Http\Requests\SiteRequest as Request;
use Core\Document\Document;


abstract class SiteController extends Controller
{     
    protected   $modules;      
    protected   $status = 200;      

    public function __construct(SiteRequest $request)
    {
        $this->request= $request;
        $this->middleware(\Spatie\ResponseCache\Middlewares\CacheResponse::class);  
    } 

    /**
     * Show site home page.
     *
     * @param  int  $id
     * @return View
     */
    public function __invoke()
    {
        if($site = $this->request->site()) { 
            event( new PreparingSiteEvent($site, $component = $this->request->component()) ); 
 
 
            $document = $this->resolveDocument(); 
            $document->locale(app()->getLocale());
            $document->with('site', $site);
            $document->with('component', $component);
 
            $content = $this->getContent($this->request, $document);
 
            event( new PreparingSiteContentEvent($content, $site, $component) ); 

            $document->setContent($content); 
            
            $document->setModules(
                $this->hasError() ? [] : $this->getModules($this->request, $site, $component)
            );

            $document->setTemplate($template = $this->request->template());  
            
            event( new PreparingSiteTemplateEvent($template, $site, $component) ); 
            
            $document->direction($template->setting('direction'));  

            return $document->toResponse($this->status); 
        }  

        throw new \Exception('Mismatch Site.', 404);
    }    

    public function resolveDocument()
    {
        return view()->getEngineResolver()->resolve('document');
    }

    protected function getModules(SiteRequest $request, $site, $component = null)
    { 
        $modules = $this->filterModules(app('module.instance')->activeModules(), $site, $component);

        event( new PreparingSiteModulesEvent($modules , $site, $component) );

        return $modules;      
    } 

    protected function filterModules($modules, $site, $component = null)
    { 
        return $modules->filter(function($module) use ($site, $component) {
            $group  = 'site';
            $item   = $site->name();

            if(isset($component)) {
                $group  = $component->name();
                $item   = $component instanceof Resourceable ? $component->resourceId() : null;
            }  

            return $module->locatedAt($group, $item, app()->getLocale(), $site->template());
        })->modules()->all();
    }

    public function hasError()
    {
        return (int) $this->status !== 200;
    }

    abstract public function getContent(SiteRequest $request, Document $document);
}