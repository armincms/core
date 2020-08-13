<?php 
namespace Core\Module\Commands; 

class ModuleFormMakeCommand extends ModuleMakeCommand
{ 

    
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module class';
 
    
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Module';

    /**
     * Execute the console command.
     *
     * @return bool|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        if(false === parent::handle()) return; 

        $this->call('make:module', [ 
            'name'  => $this->getNameInput(),
        ]);  

    } 

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name); 

        return $this->replaceHint($stub, $name);
    }

    /**
     * Replace the hint name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceHint($stub, $name)
    { 
        $name = str_replace($this->getNamespace($name).'\\', '', $name);

        $hint = module_hint_key($this->directoryName($name)); 

        return str_replace('DummyHint', $hint, $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/form.stub';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->modulePath($name).DS.$this->className($name).'.php';
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
        $stub = str_replace('DummyClass', $name = $this->className($class), $stub);

        return str_replace('DummyName', armin_slug($name, ['delimiter' => ' ']), $stub);
    }

    public function modulePath(string $name)
    {
        return module_path($this->directoryName($name));
    }

    public function directoryName(string $name)
    {
        return armin_slug($name);
    } 

    public function className(string $name)
    {
        return studly_case($name) . 'Form';
    }

}
