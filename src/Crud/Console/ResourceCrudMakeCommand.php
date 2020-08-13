<?php 
namespace Core\Crud\Console; 

class ResourceCrudMakeCommand extends GeneratorCommand
{ 

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:resource-crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Resource Crud .';
 
    
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource Crud';


    protected $fileName = 'Controller';
    protected $stub = 'resource'; 
    protected $multilingualStub = 'multilingual-resource';


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    { 
        if (parent::handle() === false) {
            return;
        }    

        $this->makeResourceModel();
        $this->makeResourceTransformer();
        $this->makeResourceForm(); 
    }  

    public function makeResourceModel()
    {
        $this->call('make:resource-model', [ 
            'name'      => $this->getNameInput(),
            'path'      => $this->argument('path'),
            'namespace' => $this->argument('namespace'),
            '-m'        => $this->option('multilingual'),
            '-rp'       => $this->option('realpath'),
        ]);
    }

    public function makeResourceTransformer()
    { 
        $this->call('make:resource-transformer', [ 
            'name'      => $this->getNameInput(),
            'path'      => $this->argument('path'),
            'namespace' => $this->argument('namespace'),
            '-m'        => $this->option('multilingual'),
            '-rp'       => $this->option('realpath'),
        ]);
    }

    public function makeResourceForm()
    {
        $this->call('make:resource-form', [ 
            'name'      => $this->getNameInput(),
            'path'      => $this->argument('path'),
            'namespace' => $this->argument('namespace'), 
            '-m'        => $this->option('multilingual'),
            '-rp'       => $this->option('realpath'),
        ]); 
    } 

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    { 
        return "{$rootNamespace}\Http\Controllers"; 
    } 
}
