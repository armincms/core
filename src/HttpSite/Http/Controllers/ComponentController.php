<?php
namespace Core\HttpSite\Http\Controllers; 
 
use Illuminate\Database\Eloquent\ModelNotFoundException;
use \Illuminate\Validation\ValidationException;
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
        } catch (ValidationException $exception) {   
            throw $exception; 
        } catch (Exception $exception) {   

            if(optional(app('request')->user())->isDeveloper() && $exception->getCode() >= 500) {  
                throw $exception;
            }

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
        $message = $exception->getMessage().($exception->getCode() < 100 ? '#'.$exception->getCode() : '');
        $this->status = $this->getStatusCode($exception);

        return tap($message, function($message) use ($document) {  
            $document->title($message);
            $document->description($message); 
            $document->keywords('keywords', $message);
        });  
    }

    public function getStatusCode(Exception $exception)
    { 
        if($exception instanceof ModelNotFoundException || $exception instanceof HttpException) { 
            return 404;
        }  

        return (int) $exception->getCode() > 100 ? 500 : 400;
    }
}