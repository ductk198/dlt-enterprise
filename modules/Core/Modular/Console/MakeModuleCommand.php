<?php

namespace Modules\Core\Modular\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Facades\DB;

class MakeModuleCommand extends Command
{

	protected $name = 'make:module';
    protected $filesystem;
	protected $description = 'Khoi tao mot modul';

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        
        $this->filesystem = $filesystem;
    }

    /**
	 * Author: Duclt
     * Date: 17/01/2017
     * Idea: Thuc thi mot lenh cmd tao modul
	 * @return void
	 */
	public function fire()
	{
        $return = false;
        $pathmigration = $this->getModuleName();
        $path = base_path();

		// Kiem tra 1 modules co ton tai trong database hay khong
        $modules = DB::table('system_module')->get()->toArray();
        $count=1;
        foreach($modules as $module){
            $count++;
            if($module->code == $this->getModuleName()){
               $return = true; break;     
            }
        }
		if($return) {
			return $this->error('Module '.$this->getModuleName().' da ton tai!');
        }else {
            $layoutname = $this->ask('Nhap vao ten layout cua modules (Backend/Frontend)?');
            if(!$this->filesystem->isDirectory($path.'/Modules/'.$layoutname)){
                return $this->error('Layout '.$layoutname.' chua ton tai trong he thong!');
            }
            $pathmigration = $layoutname.'\\'.$this->getModuleName();
            $path .= '/Modules/'.$layoutname.'/'.$this->getModuleName();
            $this->filesystem->makeDirectory($path, 0777, true, true);
        }

		// Tao folder mo hinh MVC
		$this->genRoutes($path,$layoutname);
		$this->genViews($path,$layoutname);

		//$this->genProviders();
		$this->genTranslations($path,$layoutname);
        $this->genControllers($path,$layoutname);
        $this->genModels($path,$layoutname);

        //$this->genPolicy();
        $this->genRequests($pathmigration);
        $this->genJavascipt($pathmigration,$layoutname);
        $this->genXml($pathmigration);
        // Cap nhat vao trong database
        DB::table('system_module')->insert(
            [
                'code' => strtolower($this->getModuleName())
                ,'name' => $this->getModuleName()
                ,'status' => 1
                ,'order' => $count
                ,'layout' =>$layoutname
            ]
        );
		$this->info('Da tao thanh cong module: '.$this->getModuleName());
	}

	public function genRoutes($path,$layoutname)
	{
        $stub  = file_get_contents(__DIR__.'/stubs/routes.stub');
        $find = array("{{MODULE_NAME}}", "{{LAYOUT_NAME}}");
        $replace   = array($this->getModuleName(), $layoutname);
        $stub = str_replace($find, $replace, $stub);
        //$this->line($stub);
		//build and put example file into directory
		$this->filesystem->put(str_replace('\\', '/', $path."/routes.php"), $stub);
	}

    public function genViews($path,$layoutname)
    {
        $path = $path."/Views";

        if(! $this->filesystem->isDirectory($path))
            $this->filesystem->makeDirectory($path);

        $stub  = file_get_contents(__DIR__.'/stubs/view.stub');
        $find = array("{{MODULE_NAME}}", "{{LAYOUT_NAME}}","{{MODULE_NAME_LOWER}}");
        $replace   = array($this->getModuleName(), $layoutname,strtolower($this->getModuleName()));
        $stub = str_replace($find, $replace, $stub);
        //build and put example file into directory
        $this->filesystem->put($path."/index.blade.php", $stub);
    }

    public function genProviders()
    {
        $path = base_path()."/Modules/".$this->getModuleName()."/Providers";

        if(! $this->filesystem->isDirectory($path))
            $this->filesystem->makeDirectory($path);

        $stub = str_replace('{{MODULE_NAME}}', $this->getModuleName(), file_get_contents(__DIR__.'/stubs/provider.stub'));

        //build and put example file into directory
        $this->filesystem->put($path."/".$this->getModuleName()."ServiceProvider.php", $stub);
    }

    public function genTranslations($path)
    {
        $path = $path."/Lang/vn";

        if(! $this->filesystem->isDirectory($path))
            $this->filesystem->makeDirectory($path, 0777, true, true);

        //build and put example file into directory
        $this->filesystem->put($path."/index.php", file_get_contents(__DIR__."/stubs/translation.stub"));
    }

	public function genControllers($path,$layoutname)
	{
        $path = $path."/Controllers";
        if(! $this->filesystem->isDirectory($path))
            $this->filesystem->makeDirectory($path, 0777, true, true);
        $stub  = file_get_contents(__DIR__.'/stubs/controller.stub');
        $find = array("{{MODULE_NAME}}", "{{LAYOUT_NAME}}");
        $replace   = array($this->getModuleName(), $layoutname);
        $stub = str_replace($find, $replace, $stub);
        //$this->line($stub);
        //build and put example file into directory
        $this->filesystem->put(str_replace('\\', '/', $path."/".$this->getModuleName()."Controller.php"), $stub);
	}

    public function genModels($path,$layoutname)
    {
        $path = $path."/Models";
        if(! $this->filesystem->isDirectory($path))
            $this->filesystem->makeDirectory($path, 0777, true, true);
        $stub  = file_get_contents(__DIR__.'/stubs/model.stub');
        $find = array("{{MODULE_NAME}}", "{{LAYOUT_NAME}}");
        $replace   = array($this->getModuleName(), $layoutname);
        $stub = str_replace($find, $replace, $stub);
        //$this->line($stub);
        //build and put example file into directory
        $this->filesystem->put(str_replace('\\', '/', $path."/".$this->getModuleName()."Model.php"), $stub);
    }

    public function genPolicy()
    {
        \Artisan::call('module:policy', [
            'path' => $this->getModuleName().'@ExamplePolicy',
        ]);
    }

	public function genRequests($pathmigration)
    {
        \Artisan::call('module:request', [
            'path' => $pathmigration.'@'.$this->getModuleName(),
        ]);
    }

	/**
	 * Get value of name input argument
	 *
	 * @return array|string
	 */
	public function getModuleName()
	{
		return ucfirst($this->argument('name'));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['name', InputArgument::REQUIRED, 'Module name.'],
		];
	}

    public function genJavascipt($pathmigration,$layoutname)
    {
        $path = base_path()."/public/js/".$pathmigration;
        if(!$this->filesystem->isDirectory($path))
            $this->filesystem->makeDirectory($path, 0777, true, true);

        $stub  = file_get_contents(__DIR__.'/stubs/javascript.stub');
        $find = array("{{MODULE_NAME}}", "{{LAYOUT_NAME}}");
        $replace   = array($this->getModuleName(), $layoutname);
        $stub = str_replace($find, $replace, $stub);
        $this->filesystem->put($path."/JS_".$this->getModuleName().".js", $stub);

    }
    public function genXml($pathmigration)
    {
        $path = base_path()."/xml/".$pathmigration;

        if(!$this->filesystem->isDirectory($path))
            $this->filesystem->makeDirectory($path, 0777, true, true);


        $stub = str_replace('{{MODULE_NAME}}', $this->getModuleName(), file_get_contents(__DIR__.'/stubs/xml.stub'));

        //build and put example file into directory
        $this->filesystem->put($path."/".$this->getModuleName().".xml", $stub);
    }
}
