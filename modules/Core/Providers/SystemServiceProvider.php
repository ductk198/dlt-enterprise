<?php

namespace Modules\Core\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Request;
use Illuminate\Support\Facades\View;
use Modules\System\Models\MenuModel;
use Modules\System\Listtype\Helpers\ListtypeHelper;
use DB;

class SystemServiceProvider extends ServiceProvider {

    protected $prefix = '';
    protected $layout = '';
    protected $arrModules = '';
    protected $currentModule = '';
    protected $arrUnit = '';
    protected $middleware = '';

    public function register() {
        
    }

    public function boot() {
        $layouts = config('layout.layouts');
        $path = Request::path();
        $arrPath = explode('/', $path);
        if ($arrPath[0] == 'system') {
            $this->bootSystem('System', 'system');
        } elseif ($arrPath[0] == 'api') {
            $this->bootApi('Api', 'api');
        } else {
            $this->bootFrontend('Frontend', $arrPath[0]);
        }
    }

    public function bootSystem($layout, $url) {
        session_start();
        // Kiem tra quyen dang nhap
        if (Request::is('system/login') || Request::is('system/login/*') || Request::is('system/logout') || Request::is('system/logout/*')) {
            // Load routes
            Route::group([
                'namespace' => 'Modules\System\Login\Controllers',
                'module' => 'login',
                'middleware' => 'web',
                'prefix' => 'system/login'
                    ], function ($router) {
                $this->loadRoutesFrom(base_path() . '/modules/System/Login/routes.php');
            });
            // Load views
            $this->loadViewsFrom(base_path() . '/modules/System/Login/Views', 'Login');
        } else {
            $this->namespace = 'Modules\\' . $layout . '\Controllers';
            $middleware = ['web', 'CheckSystemLogin'];
            $this->middleware = $middleware;
            // Get all Menu
            $arrModules = config('modulesystem');
            $this->arrModules = $arrModules;
            foreach ($arrModules as $urlModule => $arrModule) {
                $urlcheck = $url . '/' . $urlModule;
                if (Request::is($urlcheck) || Request::is($urlcheck . '/*')) {
                    $module = $urlModule;
                    $this->currentModule = $module;
                    view()->composer('*', function ($view) {
                        $view->with('menuItems', $this->arrModules);
                        $view->with('module', $this->currentModule);
                    });
                    $layout = 'System';
                    $this->layout = $layout;
                    $this->modules = $module;
                    $this->prefix = $module;
                    $this->namespace = 'Modules' . "\\" . $layout . "\\" . ucfirst($module) . '\Controllers';
                    // Load routes
                    Route::group([
                        'namespace' => $this->namespace,
                        'middleware' => $this->middleware,
                        'module' => $this->modules,
                        'prefix' => $url . '/' . strtolower($this->prefix)
                            ], function ($router) {
                        $this->loadRoutesFrom(base_path() . '/modules/' . $this->layout . '/' . ucfirst($this->modules) . '/routes.php');
                    });
                    // Load views
                    $this->loadViewsFrom(base_path() . '/modules/' . $this->layout . '/' . $this->modules . '/Views', ucfirst($this->modules));
                    // Translations
                    $this->loadTranslationsFrom(base_path() . '/modules/System/Lang', "System");
                }
            }
        }
    }

    public function bootFrontend($layout, $url) {
        $sql = "select dbo.f_GetValueOfXMLtag(C_XML_DATA,'url_tinh_tp') as C_SHORTCUT from T_DLT_LIST where FK_LISTTYPE = 1 order by C_ORDER";
        $arrTinh = DB::select($sql);
        $check = 0;
        foreach ($arrTinh as $key => $val) {
            if ($val->C_SHORTCUT == $url) {
                $check = 1;
                break;
            }
        }
        if ($url == null || $url == '' || $check == 1 || $url =='Enterprise')
            $url = 'Main';
        $this->currentModule = $url;
        Route::group([
            'namespace' => 'Modules\Frontend\\' . $this->currentModule . '\Controllers',
            'module' => 'Main',
            'prefix' => ''
                ], function ($router) {
            $this->loadRoutesFrom(base_path() . '/modules/Frontend/' . $this->currentModule . '/routes.php');
        });
        // Load views
        $this->loadViewsFrom(base_path() . '/modules/Frontend/' . $this->currentModule . '/Views', $this->currentModule);
    }

    public function bootApi($layout, $url) {
        $this->namespace = 'Modules\\' . $layout . '\Controllers';
        $this->modules = $url;
        $this->prefix = $url;
        // Load routes
        Route::group([
            'namespace' => $this->namespace,
            'module' => $this->modules,
            'middleware' => ['apiAuth'],
            'prefix' => strtolower($this->prefix)
                ], function ($router) {
            $this->loadRoutesFrom(base_path() . '/modules/' . $this->modules . '/routes.php');
        });
        // Load views
        $this->loadViewsFrom(base_path() . '/modules/' . $this->modules . '/Views', ucfirst($this->modules));
        // Translations
        $this->loadTranslationsFrom(base_path() . '/modules/' . $this->modules . '/Lang', ucfirst($this->modules));
    }

}

?>