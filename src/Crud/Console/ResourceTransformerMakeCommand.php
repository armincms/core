<?php 
namespace Core\Crud\Console;
 
class ResourceTransformerMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:resource-transformer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Resource Crud Transformer.';
 
    
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource Crud Transformer';

    protected $stub = 'transformer';
    protected $multilingualStub = 'transformer';
 
    protected $fileName = 'Transformer'; 

    protected $directory = 'Tables'; 
 

    /**
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    { 
        if(false === $this->option('multilingual')) {
            $stub = str_replace('Multilingual', '', $stub);
        } 

        return parent::replaceNamespace($stub, $name);
    } 
}
