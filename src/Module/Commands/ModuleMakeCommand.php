<?php 
namespace Core\Module\Commands;
 
use Core\Layout\Console\LayoutMakeCommand;  
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class ModuleMakeCommand extends GeneratorCommand
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

        $name = $this->getNameInput();

        $this->files->put(
            $this->modulePath($name).DS."view.document.blade.php", 
            '{{ $__module->get("title") ?: $__module->get("id") }}'
        );

        $this->files->copy(
            __DIR__.'/stubs/logo.jpg', $this->modulePath($name).DS."{$name}.jpg");

        $this->call('extension:composer', [
            'type'  => 'module',
            'name'  => $this->getNameInput(),
        ]);   

        $path = $this->modulePath($name).DS.$this->className($name)."Form.php"; 

        $this->files->put(
            $path, $this->buildFormClass($this->qualifyClass($name))
        ); 
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
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildFormClass($name)
    {
        $stub = $this->files->get($this->getFormStub());

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
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
        $slug = $this->directoryName($name);
        $hint = module_hint_key($slug); 

        return str_replace(['DummyHint', 'DummySlug'], [$hint, $slug], $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/module.stub';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getFormStub()
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
        return studly_case($name);
    }

}
