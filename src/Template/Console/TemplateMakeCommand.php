<?php 
namespace Armincms\Template\Console;

use Core\Layout\Console\LayoutMakeCommand;
use Symfony\Component\Console\Input\InputOption;
use File;

class TemplateMakeCommand extends LayoutMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:template';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new template.';
 
    
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Template';


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

        $name = $this->getNameInput();

        $this->files->put(
            $this->rootPath("{$name}/custom.css") , ''
        );
        $this->files->put(
            $this->rootPath("{$name}/custom.min.css") , ''
        ); 

        $this->files->put(
            $this->rootPath("{$name}/{$name}.css") , $this->getDefaultCss()
        ); 

        $composerPath = $this->rootPath("{$name}/composer.json");
        $composer = array_merge( 
            json_decode(\File::get($composerPath), true),
            ['require' => ["armin-cms/plugin-bootstrap" .($this->isRtl() ? '-rtl' : '') => ".*"]]
        ); 

        File::put(
            $composerPath, str_replace('\/', '/', json_encode($composer, JSON_PRETTY_PRINT)) 
        ); 
    }  


    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name, $type = 'doc.blade.php')
    { 
        return $this->rootPath("{$name}/{$name}.{$type}");
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    { 
        return __DIR__.'/stubs/template.stub';
    }  

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return template_path();
    } 

    public function rootPath($name)
    {
        return template_path($name);
    } 

    public function isRtl()
    {   
        return $this->option('rtl');
    }

    public function getDefaultCss()
    {
        $dir = __DIR__.'/stubs';
        
        return File::get("{$dir}/main.css") .PHP_EOL. File::get("{$dir}/responsive.css");
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['rtl', null, InputOption::VALUE_NONE, 'The cache tags you would like to clear'],
        ];
    }
}
