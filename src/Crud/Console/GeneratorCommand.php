<?php 
namespace Core\Crud\Console;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Console\GeneratorCommand as LaravelGeneratorCommand;
use Illuminate\Support\Str;

abstract class GeneratorCommand extends LaravelGeneratorCommand
{ 
	protected $directory = '/';
	protected $fileName = '';

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {    
        return $this->rootPath($name).str_after($name, $this->rootNamespace())."{$this->fileName}.php";
    }  

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {  
        return $this->argument('namespace');  
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

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    { 
        $stub = $this->hasOption('multilingual') && $this->option('multilingual')
                        ? $this->multilingualStub : $this->stub; 

        return __DIR__."/stubs/{$stub}.stub";
    } 
 
    public function rootPath($name)
    {
        return $this->resourcePath($this->directory);
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
        $stub = str_replace('DummySlug', snake_case($this->getNameInput(), '-'), $stub);

        return parent::replaceNamespace($stub, $name);
    }
    
    public function resourcePath($path = null)
    {
        $path = $this->argument('path').($path ? "/{$path}" : '');

        if($this->hasOption('realpath') && $this->option('realpath')) {
            return $path; 
        } 

        return base_path($path); 
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the resource.'], 
            ['path', InputArgument::REQUIRED, 'The path of the resource.'], 
            ['namespace', InputArgument::REQUIRED, 'The root namespace of the resource.'], 
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
            ['multilingual', 'm', InputOption::VALUE_NONE, 'Make multilingual resource.'],
            ['realpath', 'rp', InputOption::VALUE_NONE, 'Resource path is real or relaive.'],
        ];
    }  
}
