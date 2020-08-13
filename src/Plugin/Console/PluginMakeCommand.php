<?php 
namespace Core\Plugin\Console;

use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Console\GeneratorCommand;

class PluginMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:plugin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Plugin class';
 
    
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Plugin';


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

        $this->call('extension:composer', [
            'type'=> 'plugin',
            'name'  => $this->getNameInput(),
        ]); 
    }  

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    { 
        return __DIR__.'/stubs/plugin.stub';
    } 


    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return 'Plugin';
    }

    public function getNamespacse($name)
    {
        return $name;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {  
        $directory = $this->pluginSlug($this->pluginName($name)); 
 
        return plugin_path($directory).'/'.studly_case($directory).'.php'; 
    }

    public function pluginName($name)
    {
        return \Illuminate\Support\Str::replaceFirst($this->rootNamespace(), '', $name);
    }

    public function pluginSlug($name)
    {
        $snakeName  = snake_case($this->pluginName($name), '-');
        $slugName   = str_slug($snakeName);

        return trim($slugName, '-');
    }

    public function rootPath($name)
    {
        return extension_path();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the layout'],
        ];
    }  


    /**
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $stub = str_replace(
            ['DummyNamespace', 'DummyRootNamespace',  'DummySlug'],
            [$this->getNamespace($name), $this->rootNamespace(), $this->pluginSlug($name)],
            $stub
        );

        return $this;
    }


    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        return str_replace('DummyClass', studly_case($class), $stub);
    }

    public function addIndex()
    { 
        $path = plugin_path(
            $this->pluginSlug($this->getNameInput())
        );

        $this->files->put("{$path}/index.php", '<?php exit("Access Denied ..!"); ?>');
    }
}
