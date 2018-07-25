<?php

namespace kjoekjoe\crudgen;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use kjoekjoe\crudgen\resources\CrudGenerator;

class CrudGenServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (is_dir(app_path().'/Modules/')) {
            $modules = config("modules.enable") ?: array_map('class_basename', $this->files->directories(app_path().'/Modules/'));
            foreach ($modules as $module) {
                // Allow routes to be cached
                if (!$this->app->routesAreCached()) {
                    $route_files = [
                        app_path() . '/Modules/' . $module . '/routes/web.php',
                    ];
                    foreach ($route_files as $route_file) {
                        if ($this->files->exists($route_file)) {
                            include $route_file;
                        }
                    }
                }

                $views  = app_path().'/Modules/'.$module.'/Views';

                if ($this->files->isDirectory($views)) {
                    $this->loadViewsFrom($views, $module);
                }
            }

        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->files = new Filesystem;
        $this->registerMakeCommand();
    }

    protected function registerMakeCommand()
    {
        $this->commands('modules.make');

        $bind_method = method_exists($this->app, 'bindShared') ? 'bindShared' : 'singleton';

        $this->app->{$bind_method}('modules.make', function ($app) {
            return new resources\CrudGenerator($this->files);
        });
    }

}
