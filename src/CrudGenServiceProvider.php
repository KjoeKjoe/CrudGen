<?php

namespace kjoekjoe\crudgen;

use Illuminate\Support\ServiceProvider;

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
                        app_path() . '/Modules/' . $module . '/routes.php',
                        app_path() . '/Modules/' . $module . '/routes/web.php',
                        app_path() . '/Modules/' . $module . '/routes/api.php',
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
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }
}
