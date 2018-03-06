<?php

namespace Modules\Core\Modular;

use Modules\Core\Modular\Console\CreateControllerCmd;
use Modules\Core\Modular\Console\CreateModelCmd;
use Modules\Core\Modular\Console\CreatePolicyCmd;
use Modules\Core\Modular\Console\CreateRequestCmd;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() {
		
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register() {
		$this->registerMakeCommand ();

        //Single commands available
        $this->commands([
            CreateControllerCmd::class,
            CreateModelCmd::class,
            CreateRequestCmd::class,
            CreatePolicyCmd::class,
        ]);
	}

	/**
	 * Register the "make:module" console command.
	 *
	 * @return Console\MakeModuleCommand
	 */
	protected function registerMakeCommand() {
		$this->commands ( 'modules.make' );

		$bind_method = method_exists ( $this->app, 'bindShared' ) ? 'bindShared' : 'singleton';

		$this->app->{$bind_method} ( 'modules.make', function ($app) {
            return new Console\MakeModuleCommand ( new Filesystem() );
		} );
	}
}
