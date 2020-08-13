<?php
namespace Core\HttpSite\Http\Controllers; 
 
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Core\HttpSite\Contracts\SiteRequest; 
use Core\Document\Document;
use Exception;

class ComponentController extends SiteController
{    
    public function getContent(SiteRequest $request, Document $document)
    {  
        try {  
            abort_if(is_null($component = $request->component()), 404, 'Not Found Component'); 
            
            return (string) $component->toHtml($request, $document);  
        } catch (Exception $exception) {   
            $this->logError($exception);

            return $this->errorResponse($exception, $document);
        }     
    } 

    protected function logError(Exception $e)
    {
        \Log::log('info', $e);
    }

    protected function errorResponse(Exception $exception, Document $document)
    {  
        $code = $exception->getCode();

        if($code == 0 && method_exists($exception, 'getStatusCode')) {
            $code = $exception->getStatusCode();
        }

        if(! is_superadmin() || $code < 500) { 
            $this->status = $this->getStatusCode($exception);

            return tap($exception->getMessage(), function($message) use ($document) { 
                $document->title($message);
                $document->description($message);
                $document->meta('title', $message); 
                $document->meta('description', $message); 
                $document->meta('keywords', $message);
            });   
        } 
        
        throw $exception;
    }

    public function getStatusCode(Exception $exception)
    { 
        if($exception instanceof ModelNotFoundException || $exception instanceof HttpException) { 
            return 404;
        } 

        return (int) $exception->getCode()?: 500;
    }
}