<?php 
namespace Core\Extension\Console;

use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Console\GeneratorCommand;

class ComposerMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'extension:composer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new extension composer.';
 
    
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Composer';
  

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function qualifyClass($name)
    {
        $snakeName  = snake_case($name);
        $slugName   = str_slug($snakeName);

        return trim($slugName, '-'); 
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    { 
        return __DIR__.'/stubs/composer.stub';
    } 


    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return extension_path();
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    { 
        $type = str_plural(trim($this->argument('type')));

        return extension_path("{$type}/{$name}/composer.json");
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
            ['DummyExtension', 'DummyName'],
            [$this->argument('type'), $name],
            $stub
        );

        return $this;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the extension'],
            ['type', InputArgument::REQUIRED, 'The type of the extension'],
        ];
    }  
}
