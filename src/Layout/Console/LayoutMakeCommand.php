<?php 
namespace Core\Layout\Console;

use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Console\GeneratorCommand;

class LayoutMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:layout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new layout class';
 
    
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Layout';


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

        $this->files->put(
            $this->getPath($name = $this->getNameInput(), 'css'), ''
        );

        $this->addIndex($this->rootPath("{$name}/index.php"));

        $this->call('extension:composer', [
            'type'  => $this->typeSlug(),
            'name'  => $this->getNameInput(),
        ]); 

        $group = $this->argument('group');
        $label = $name;

        $composer = array_merge(
            (array) json_decode(file_get_contents($this->rootPath("{$name}/composer.json")), true),
            compact('group', 'label')
        );
 
        $this->files->put(
            $this->rootPath("{$name}/composer.json"), json_encode($composer, JSON_PRETTY_PRINT)
        ); 
    } 

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function qualifyClass($name)
    {
        return str_slug($name); 
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    { 
        return __DIR__.'/stubs/' .$this->typeSlug(). '.stub';
    } 


    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return layout_path();
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name, $type = 'document.blade.php')
    { 
        return $this->rootPath("{$name}/{$name}.{$type}");
    }

    public function rootPath($name)
    {
        return layout_path($name);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the ' . $this->typeSlug()],
            ['group', InputArgument::OPTIONAL, 'The name of the ' . $this->typeSlug(), '*'],
        ];
    }  

    public function typeSlug()
    {
        return str_slug($this->type);
    }

    public function addIndex($path)
    { 
        $this->files->put($path, '<?php exit("Access Denied ..!"); ?>');
    } 

}
