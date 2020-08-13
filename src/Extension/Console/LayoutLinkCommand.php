<?php
namespace Core\Extension\Console; 

use Illuminate\Console\Command;

class LayoutLinkCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'layout:link';

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
        if (file_exists(public_path('layouts'))) {
            $this->error("The \"public/layouts\" directory already exists.");
        } else {
            $this->laravel->make('files')->link(
                layout_path(), public_path('layouts')
            );  

            $this->info("The [public/layouts] directory has been linked.");  
        } 
    }
}
