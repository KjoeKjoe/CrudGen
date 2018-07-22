<?php

namespace kjoekjoe\crudGen;

use Illuminate\Support\ServiceProvider;
use kjoekjoe\crudGen\resources\CrudGenerator;

class CrudGenProvider extends ServiceProvider
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
