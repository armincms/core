<?php 
namespace Core\Crud\Console;
 
use Symfony\Component\Console\Input\InputOption; 

class ResourceModelTranslationMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:resource-model-translation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Resource Crud Model Translation.';
 
    
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource Crud Model Translation';

    protected $stub = 'translation-model';

    protected $fileName = 'Translation';  
    
 
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [ 
            ['realpath', 'rp', InputOption::VALUE_NONE, 'Resource path is real or relaive.'],
        ];
    }    
}
