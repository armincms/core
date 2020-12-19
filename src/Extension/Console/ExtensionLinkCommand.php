<?php
namespace Core\Extension\Console; 

use Illuminate\Console\Command; 
use Symfony\Component\Console\Input\InputArgument;

class ExtensionLinkCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $name = 'extension:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a symbolic link from "public/extension" to "extensions" subdirectories';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {    
        collect((array) $this->getExtensions())->each([$this, 'linkPath']);
    }

    public function linkPath($extension)
    {
        $path = $this->getPath($extension);

        if (file_exists(public_path($path))) {
            $this->error("The \"public/{$path}\" directory already exists.");
        } else {
            $this->laravel->make('files')->link(
                extension_path($path), public_path($path)
            );  

            $this->info("The [public/{$path}] directory has been linked.");  
        } 
    }

    public function getExtensions()
    {
        return $this->argument('extension') ?: ['plugin', 'module', 'template', 'layout'];
    }

    public function getPath($name)
    {  
        return str_plural( str_slug($name) );
    }


    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['extension', InputArgument::OPTIONAL, 'Type of the extension'],
        ];
    }  
}
