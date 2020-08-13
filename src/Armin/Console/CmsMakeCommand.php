<?php 
namespace Core\Armin\Console;
 
use Illuminate\Console\Command; 
use FilesystemIterator;
use File;
use ZipArchive;

class CmsMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'armin-cms:build {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an clean copy of cms.'; 


    protected $path = '';
 

   /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->path = $this->argument('path');   

        if($this->ensureDirectory($this->getTargetDirectory())) { 
            if(! $this->confirm('the path is not clean. clean it?')) {
                return;
            } else {
                $this->line("Deleting directory: {$this->path}"); 

                File::deleteDirectory($this->getTargetDirectory());

                $this->info("Deleted directory: {$this->path}");  
            }
        } 

        $this->line('Please Wait For Copy File And Folders ...');
        $this->line(' ');
 
        $this->copyDirectory(base_path('../'), $this->getTargetDirectory());

        $this->info('All Files Copied. Please Clean Install File if Exists .');

        return;
        
        if($this->confirm('Do you want to compress it for you?')) {
            $this->line('Zipping files ...');

            $this->makeZipFile();

            $this->info('Files Zipped.');
        }
 
    }   
 

    protected function ensureDirectory($path)
    {
        return File::exists($path);
    }

    public function getTargetDirectory($path=null)
    {
        return base_path("../copied/{$this->path}". ($path ? DS.$path : ''));
    } 



    /**
     * Copy a directory from one location to another.
     *
     * @param  string  $directory
     * @param  string  $destination
     * @param  int     $options
     * @return bool
     */
    public function copyDirectory($directory, $destination, $options = null, $depth = 0)
    {
        if (! File::isDirectory($directory)) {
            return false;
        }

        $options = $options ?: FilesystemIterator::SKIP_DOTS;

        // If the destination directory does not actually exist, we will go ahead and
        // create it recursively, which just gets the destination prepared to copy
        // the files over. Once we make the directory we'll proceed the copying.
        if (! File::isDirectory($destination)) {
            File::makeDirectory($destination, 0777, true);
        }

        $items = new FilesystemIterator($directory, $options);

        foreach ($items as $item) {
            if($this->ignored($item)) {
                continue;
            }
            // As we spin through items, we will check to see if the current file is actually
            // a directory or a file. When it is actually a directory we will need to call
            // back into this function recursively to keep copying these nested folders.
            $target = $destination.'/'.$item->getBasename();

            if ($item->isDir()) {
                $path = $item->getPathname();

                $depth > 2 || $this->line('Copying Directory: ' . $path);

                if (! $this->copyDirectory($path, $target, $options, $depth + 1)) {
                    return false;
                }

                $depth > 2 || $this->info('Copied Directory: ' . $path);
            }

            // If the current items is just a regular file, we will just copy this to the new
            // location and keep looping. If for some reason the copy fails we'll bail out
            // and return false, so the developer is aware that the copy process failed.
            else {
                if (! File::copy($item->getPathname(), $target)) {
                    return false;
                }
            } 
        }

        return true;
    }

    public function ignored($item)
    {
        $path = $item->getPathname();
        $name = $item->getbasename();

        if($this->ignoredExtension($item->getExtension())) {
            return true;
        } 

        if($this->ignoredDirectory($path, $name)) {
            return true;
        } else if ($this->ignoredFile($path, $name)) {
            return true;
        } 

        return false;
    }

    public function ignoredExtension(string $extension)
    {
        return in_array($extension, ['git', 'phpintel']);
    }

    public function ignoredDirectory($path, $name)
    {  
        $dirname= dirname($path);
        $parent = File::name($dirname); 

        if(str_contains($path, ['core', 'vendor', 'resources'])) {
            return false;
        }  

        if(in_array($parent, $this->getIgnoredSubDirectories())) { 
            return true;
        } 

        return in_array($name, ['node_modules', 'copied']); 
    }

    public function getIgnoredSubDirectories()
    {
        return [
            'components', 'templates', 'modules', 'layouts', 'logs', 'cache', 'files', 
            'sessions', 'views'
        ];
    }

    public function ignoredFile($path, $name)
    { 
        return in_array($name, ['install']);
    }

    public function makeZipFile()
    {
         // create a list of files that should be added to the archive.
        $files = File::directories($this->getTargetDirectory());  

        // define the name of the archive and create a new ZipArchive instance.
        $archiveFile = $this->getTargetDirectory("../{$this->path}.zip");
        $archive = new ZipArchive();

        // check if the archive could be created.
        if ($archive->open($archiveFile, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            // loop trough all the files and add them to the archive.
            foreach ($files as $file) {
                if ($archive->addFile($file, basename($file))) {
                    $this->line('Added '. basename($file));
                    // do something here if addFile succeeded, otherwise this statement is unnecessary and can be ignored.
                    continue;
                } else {
                    throw new Exception("file `{$file}` could not be added to the zip file: " . $archive->getStatusString());
                }
            }

            // close the archive.
            if ($archive->close()) {
                return true;
                // archive is now downloadable ...
                return response()->download($archiveFile, basename($archiveFile))->deleteFileAfterSend(true);
            } else {
                throw new Exception("could not close zip file: " . $archive->getStatusString());
            }
        } else {
          throw new Exception("zip file could not be created: " . $archive->getStatusString());
        }
    }
}
