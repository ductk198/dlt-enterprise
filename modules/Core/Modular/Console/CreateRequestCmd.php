<?php

namespace Modules\Core\Modular\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Filesystem\Filesystem;

class CreateRequestCmd extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:request {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Request class in a given module';

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * CreateModelCmd constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->filesystem = new Filesystem();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $moduleName = explode('@', $this->argument('path'))[0];
        $className = explode('@', $this->argument('path'))[1];

        $modulePath = base_path()."/Modules/".$moduleName;
        $filePath = $modulePath."/Requests/".$className.".php";

        // check if module exists
        if(!is_dir($modulePath))
            return $this->error('Module '.$moduleName.' does not exists!');

        // check if file exists
        if(is_file($filePath))
            return $this->error("File just Exists");

        if(! $this->filesystem->isDirectory($modulePath.'/Requests'))
            $this->filesystem->makeDirectory($modulePath.'/Requests', 0777, true, true);

        $stub = str_replace('{{MOD_NAME}}', $moduleName, file_get_contents(__DIR__.'/stubs/request.stub'));
        $stub = str_replace('{{REQUEST_NAME}}', $className, $stub);

        //build and put example file into directory
        $this->filesystem->put($filePath, $stub);

        $this->info($className . ' class created successfully.');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['path', InputArgument::REQUIRED, 'Path to save new request class with format: ModuleName@RequestClassName'],
        ];
    }
}
