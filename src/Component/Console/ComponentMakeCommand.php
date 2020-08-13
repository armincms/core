<?php 
namespace Core\Component\Console;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class ComponentMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:component';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Resource-Component instance';
 
    
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Component';


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

        $this->addIndex();
        $this->addComposer($name = $this->getNameInput());

        if($this->hasOption('resource')) { 
            $this->addResource($name);
        }
    }  

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {  
        return $this->componentPath().'/'.$this->componentVendor().'ServiceProvider.php';
    } 

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return 'Component';
    } 
    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function qualifyClass($name)
    {
        return parent::qualifyClass(Str::studly($name));
    } 

    public function componentName()
    {
        return str_slug($this->getNameInput());
    }

    public function componentVendor()
    {
        return studly_case($this->componentName());
    }

    public function componentNamespace()
    {
        return 'Component\\' . $this->componentVendor();
    } 

    public function componentPath()
    {
        return component_path($this->componentName());
    } 

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    { 
        return __DIR__.'/stubs/component.stub';
    } 
 
    public function rootPath($name)
    {
        return component_path($name);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the component.'], 
        ];
    }   

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['resource', 'r', InputOption::VALUE_NONE, 'Make resource for component.'],
            ['multilingual', 'm', InputOption::VALUE_NONE, 'Make multilingual resource.'],
        ];
    }

    public function addComposer($name)
    { 
        $this->call('extension:composer', [
            'type'  => 'component',
            'name'  => $name,
        ]);   
    }

    public function addResource($name)
    { 
        $this->call('make:resource-crud', [
            'name'      => $name,
            'path'      => str_after($this->componentPath(), base_path()),
            'namespace' => $this->componentNamespace(),
            '-m'    => $this->option('multilingual')
        ]);  
    }

    public function addIndex()
    { 
        $this->files->put(
            $this->componentPath()."/index.php", '<?php exit("Access Denied ..!"); ?>'
        );
    } 

}
