<?php 
namespace Core\Crud\Console;
 

class ResourceModelMakeCommand extends GeneratorCommand
{ 
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:resource-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Resource Crud Model.';
 
    
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource Crud Model';


    protected $stub = 'model'; 
    protected $multilingualStub = 'multilingual-model';  

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

        $this->makeMigration(snake_case($this->getNameInput()));

        if($this->option('multilingual')) { 
            sleep(1);
            
            $this->call('make:resource-model-translation', [
                'name'      => $this->getNameInput(),
                'path'      => $this->argument('path'),
                'namespace' => $this->argument('namespace'),
                '-rp'       => $this->option('realpath'),
            ]);
        }   
    }   

    public function makeMigration($name)
    {  
        $table = str_plural($name);
        $path = $this->resourcePath('/database/migrations');

        $this->makeDirectory($path.'/s');

        $this->call('make:migration', [
            'name'=> "create_{$table}_table",
            '--path' => trim(str_after($path, base_path()), '\\'),
            '--create'=> $table,
        ]);

        if($this->option('multilingual')) { 
            $this->call('make:migration', [
                'name'=> "create_{$name}_translations_table",
                '--path' => trim(str_after($path, base_path()), '\\'),
                '--create'=> "{$name}_translations",
            ]); 
        }
        
    }     
}
