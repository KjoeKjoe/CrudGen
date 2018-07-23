<?php

namespace kjoekjoe\crudgen\resources;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;

class CrudGenerator extends Command
{
    protected $signature = 'crud:generate
        {name : Class (singular) for example User}
        ';

    protected $description = 'Create CRUD operations';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Generating module and migration');

        $name = $this->argument('name');

        $this->controller($name);
        $this->model($name);
        $this->request($name);
        $this->routes($name);
        $this->views($name);
        $this->migration($name);

        $this->info('Generating Module Completed');

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
            ['{{modelName}}'],
            [$name],
            $this->getStub('Model')
        );
        if(!file_exists($path = base_path("app/Modules/{$name}")))
            mkdir($path, 0777, true);

        file_put_contents(base_path("app/Modules/{$name}/{$name}.php"), $modelTemplate);
    }

    protected function migration($name)
    {
        if (substr($name, -1) == 's') {
            Artisan::call('make:migration', ['name' => 'create' . $name . '_table']);
        }else{
            Artisan::call('make:migration', ['name' => 'create' . $name . 's_table']);
        }
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
        if(!file_exists($path = base_path("app/Modules/{$name}/Routes")))
            mkdir($path, 0777, true);

        file_put_contents(base_path("app/Modules/{$name}/Routes/web.php"), $routesTemplate);
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
                '{{modelNameSingularLowerCase}}'
            ],
            [
                $name,
                strtolower(str_plural($name)),
                strtolower($name)
            ],
            $this->getView('index')
        );
        $viewShowTemplate = str_replace(
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
            $this->getView('show')
        );
        $viewEditTemplate = str_replace(
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
            $this->getView('edit')
        );
        $viewCreateTemplate = str_replace(
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
            $this->getView('create')
        );

        if(!file_exists($path = base_path("app/Modules/{$name}/Views")))
            mkdir($path, 0777, true);

        file_put_contents(base_path("app/Modules/{$name}/Views/index.blade.php"), $viewIndexTemplate);
        file_put_contents(base_path("app/Modules/{$name}/Views/show.blade.php"), $viewShowTemplate);
        file_put_contents(base_path("app/Modules/{$name}/Views/edit.blade.php"), $viewEditTemplate);
        file_put_contents(base_path("app/Modules/{$name}/Views/create.blade.php"), $viewCreateTemplate);
    }

    protected function getStub($type)
    {
        return file_get_contents(__DIR__.'/stubs/'.$type.'.stub');
    }

    protected function getView($type)
    {
        return file_get_contents(__DIR__.'/stubs/'.$type.'.stub');
    }
}
