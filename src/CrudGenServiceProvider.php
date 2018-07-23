<?php

namespace kjoekjoe\crudgen;

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
        if ($this->app->runningInConsole()) {
            $this->commands([
                CrudGenerator::class,
            ]);
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
