<?php 
namespace Core\Crud\Console; 

class ResourceFormMakeCommand extends ResourceTransformerMakeCommand
{ 

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:resource-form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Resource Crud Form.';
 
    
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource Crud Form';
    
    protected $fileName = 'Form';
    protected $stub = 'form';  
    protected $multilingualStub = 'form';
    protected $directory = 'Forms';    
 
}
