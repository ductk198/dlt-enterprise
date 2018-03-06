<?php

namespace Modules\Core\Modular\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Filesystem\Filesystem;

class CreateControllerCmd extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:controller {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Controller class in a given module';

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * CreateControllerCmd constructor.
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
        $filePath = $modulePath."/Controllers/".$className.".php";

        // check if module exists
        if(!is_dir($modulePath))
            return $this->error('[ERROR] Module '.$moduleName.' does not exists!');

        // check if file exists
        if(is_file($filePath))
            return $this->error("File just Exists");

        if(! $this->filesystem->isDirectory($modulePath.'/Controllers'))
            $this->filesystem->makeDirectory($modulePath.'/Controllers', 0777, true, true);

        $stub = str_replace('{{MODULE_NAME}}', $moduleName, file_get_contents(__DIR__.'/stubs/controller.stub'));
        $stub = str_replace('{{CONTROLLER_NAME}}', $className, $stub);

        //build and put example file into directory
        $this->filesystem->put($filePath, $stub);

        $this->info($className . ' class da tao thanh cong!');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['path', InputArgument::REQUIRED, 'Path to save new controller class with format: ModuleName@ControllerClassName'],
        ];
    }
}
