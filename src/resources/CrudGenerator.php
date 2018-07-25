<?php

namespace kjoekjoe\crudgen\resources;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;

class CrudGenerator extends Command
{
    protected $signature = 'crud:generate
        {name : Class (singular) for example User}
        {--i= : Array for integers in your migration Example (price,level,exp)}
        {--s= : Array for Strings in your migration Example (name,email,surname)}
        {--u : use UUID in migration}
        ';

    protected $description = 'Create CRUD operations';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Generating module');

        $name = $this->argument('name');
        $integer = $this->option('i');
        $string = $this->option('s');
        $uuid = $this->option('u');

        if (substr($name, -1) != 's') {
            $name = $name . 's';
        }

        $migrationOptions = collect(['integers' => $integer, 'strings' => $string, 'uuid' => $uuid]);
        $tables = $this->makeTables($migrationOptions['integers'], $migrationOptions['strings'], $migrationOptions['uuid']);

        $this->info('Generating Migration');

        $this->migration($name, $tables);

        $this->controller($name);
        $this->model($name);
        $this->request($name);
        $this->routes($name);
        $this->views($name);

        $this->info('Generating Completed');

    }

    protected function controller($name)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}'
            ],
            [
                $name,
                strtolower(str_plural($name)),
                strtolower($name)
            ],
            $this->getStub('Controller')
        );

        if(!file_exists($path = base_path("app/Modules/{$name}/Controllers")))
            mkdir($path, 0777, true);

        file_put_contents(base_path("app/Modules/{$name}/Controllers/{$name}Controller.php"), $controllerTemplate);
    }

    protected function model($name)
    {
        $modelTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameSingularLowerCase}}'
            ],
            [
                $name,
                strtolower($name)
            ],
            $this->getStub('Model')
        );
        if(!file_exists($path = base_path("app/Modules/{$name}/Models")))
            mkdir($path, 0777, true);

        file_put_contents(base_path("app/Modules/{$name}/Models/{$name}.php"), $modelTemplate);
    }

    protected function makeTables($integer, $string, $uuid){
        if($integer){
            $integers = explode(',',$integer);
            $makeIntegerTables = array_map(function($val) { return '$table->integer('.'"' . strtolower($val) . '"'.');'; }, $integers);
        }else{
            $makeIntegerTables = null;
        }
        if($string){
            $strings = explode(',',$string);
            $makeStringTables = array_map(function($val) { return '$table->string('.'"' . strtolower($val) . '"'.');'; }, $strings);
        }else{
            $makeStringTables = null;
        }
        if($uuid){
            $makeUuidTable = '$table->uuid("uuid");';
        }else{
            $makeUuidTable = null;
        }

        return collect(['integers' => $makeIntegerTables, 'strings' => $makeStringTables, 'uuid' => $makeUuidTable]);

    }

    protected function migration($name, $tables)
    {

        if($tables['integers']){
            $implodedInteger = implode(PHP_EOL,$tables['integers']);
        }else{
            $implodedInteger = null;
        }
        if($tables['strings']){
            $implodedString = implode(PHP_EOL,$tables['strings']);
        }else{
            $implodedString = null;
        }
        $migrationTemplate = str_replace(
            [
                '{{name}}',
                '{{integers}}',
                '{{strings}}',
                '{{uuid}}',
            ],
            [
                $name,
                $implodedInteger,
                $implodedString,
                $tables['uuid'],
            ],
            $this->getStub('Migration')
        );

        $now = Carbon::now();

        file_put_contents(base_path("database/migrations/{$now->format("Y_m_d_his")}_create_{$name}_table.php"), $migrationTemplate);

    }

    protected function routes($name)
    {
        $routesTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameSingularLowerCase}}'
            ],
            [
                $name,
                strtolower($name)
            ],
            $this->getStub('Routes')
        );
        if(!file_exists($path = base_path("app/Modules/{$name}/routes")))
            mkdir($path, 0777, true);

        file_put_contents(base_path("app/Modules/{$name}/routes/web.php"), $routesTemplate);
    }

    protected function request($name)
    {
        $requestTemplate = str_replace(
            ['{{modelName}}'],
            [$name],
            $this->getStub('Request')
        );

        if(!file_exists($path = base_path("app/Modules/{$name}/Requests")))
            mkdir($path, 0777, true);

        file_put_contents(base_path("app/Modules/{$name}/Requests/{$name}Request.php"), $requestTemplate);
    }

    protected function views($name)
    {
        $viewIndexTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{indexRoute}}',
                '{{storeRoute}}',
                '{{createRoute}}',
                '{{showRouteStart}}',
                '{{deleteRouteStart}}',
                '{{editRouteStart}}',
                '{{updateRouteStart}}',
            ],
            [
                $name,
                strtolower(str_plural($name)),
                strtolower($name),
                strtolower("{{route('{$name}.index')}}"),
                strtolower("{{route('{$name}.store')}}"),
                strtolower("{{route('{$name}.create')}}"),
                strtolower("{{route('{$name}.show'"),
                strtolower("{{route('{$name}.destroy'"),
                strtolower("{{route('{$name}.edit'"),
                strtolower("{{route('{$name}.update'"),
            ],
            $this->getView('index')
        );
        $viewShowTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{indexRoute}}',
                '{{storeRoute}}',
                '{{createRoute}}',
                '{{showRouteStart}}',
                '{{deleteRouteStart}}',
                '{{editRouteStart}}',
                '{{updateRouteStart}}',
            ],
            [
                $name,
                strtolower(str_plural($name)),
                strtolower($name),
                strtolower("{{route('{$name}.index')}}"),
                strtolower("{{route('{$name}.store')}}"),
                strtolower("{{route('{$name}.create')}}"),
                strtolower("{{route('{$name}.show'"),
                strtolower("{{route('{$name}.destroy'"),
                strtolower("{{route('{$name}.edit'"),
                strtolower("{{route('{$name}.update'"),
            ],
            $this->getView('show')
        );
        $viewEditTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{indexRoute}}',
                '{{storeRoute}}',
                '{{createRoute}}',
                '{{showRouteStart}}',
                '{{deleteRouteStart}}',
                '{{editRouteStart}}',
                '{{updateRouteStart}}',
            ],
            [
                $name,
                strtolower(str_plural($name)),
                strtolower($name),
                strtolower("{{route('{$name}.index')}}"),
                strtolower("{{route('{$name}.store')}}"),
                strtolower("{{route('{$name}.create')}}"),
                strtolower("{{route('{$name}.show'"),
                strtolower("{{route('{$name}.destroy'"),
                strtolower("{{route('{$name}.edit'"),
                strtolower("{{route('{$name}.update'"),
            ],
            $this->getView('edit')
        );
        $viewCreateTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{indexRoute}}',
                '{{storeRoute}}',
                '{{createRoute}}',
                '{{showRouteStart}}',
                '{{deleteRouteStart}}',
                '{{editRouteStart}}',
                '{{updateRouteStart}}',
            ],
            [
                $name,
                strtolower(str_plural($name)),
                strtolower($name),
                strtolower("{{route('{$name}.index')}}"),
                strtolower("{{route('{$name}.store')}}"),
                strtolower("{{route('{$name}.create')}}"),
                strtolower("{{route('{$name}.show'"),
                strtolower("{{route('{$name}.destroy'"),
                strtolower("{{route('{$name}.edit'"),
                strtolower("{{route('{$name}.update'"),
            ],
            $this->getView('create')
        );

        $layoutsTemplate = $this->getView('app');

        if(!file_exists($path = base_path("resources/views/layouts")))
            mkdir($path, 0777, true);
        if(!file_exists($path = base_path("app/Modules/{$name}/Views")))
            mkdir($path, 0777, true);

        file_put_contents(base_path("resources/views/layouts/app.blade.php"), $layoutsTemplate);
        file_put_contents(base_path("app/Modules/{$name}/Views/index.blade.php"), $viewIndexTemplate);
        file_put_contents(base_path("app/Modules/{$name}/Views/show.blade.php"), $viewShowTemplate);
        file_put_contents(base_path("app/Modules/{$name}/Views/edit.blade.php"), $viewEditTemplate);
        file_put_contents(base_path("app/Modules/{$name}/Views/create.blade.php"), $viewCreateTemplate);
    }

    protected function getStub($type)
    {
        return file_get_contents(__DIR__.'\\stubs\\'.$type.'.stub');
    }

    protected function getView($type)
    {
        return file_get_contents(__DIR__.'\\stubs\\views\\'.$type.'.blade.php');
    }
}
