# Module CRUD generator WIP
[![Laravel](https://img.shields.io/badge/laravel-5-orange.svg)](http://laravel.com)

This package gives you the ability to use Laravel 5 with module system including a CRUD template.
You can simply drop or generate modules with their own controllers, models, views, translations and a routes file into the `app/Modules` folder and go on working with them.

## Documentation

* [Installation](#installation)
* [Getting started](#getting-started)
* [Migration Generate](#migration-generate)


<a name="installation"></a>
## Installation
( WIP: BUGS CAN BE PRESENT )

The best way to install this package is through your terminal via Composer.

Run the following command from your projects root
```
composer require kjoekjoe/crudgen dev-master
```
Once this operation is complete, simply add the service provider to your project's `config/app.php` and you're done.

#### Service Provider
```
kjoekjoe\crudgen\CrudGenServiceProvider::class,
```
#### Composer.json
```
    "autoload": {
        "classmap": [
            "database/seeds",
            "database/factories"
        ],
        "psr-4": {
            "App\\": "app/",
            "kjoekjoe\\crudgen\\": "vendor/kjoekjoe/crudgen/src/"
        }
    },
```

<a name="getting-started"></a>
## Getting started

The built in Artisan command `php artisan crud:generate name {--i=} {--s=} {--u}` generates a ready to use module in the `app/Modules` folder and a migration in database folder.


This is how the generated module would look like:
```
laravel-project/
    app/
    └── Modules/
        └── FooBar/
            ├── Controllers/
            │   └── FooBarController.php
            ├── Models/
            │   └── FooBar.php
            ├── Views/
            │   └── index.blade.php
            │   └── create.blade.php
            │   └── show.blade.php
            │   └── edit.blade.php
            ├── routes
                └── web.php
                
```

<a name="migration-generate"></a>
## Migration Generate

if you want to generate the migration strings/integers or you want to have a uuid field you can use the options:
```
{--i=} == $table->integer('')
{--s=} == $table->string('')
{--u} == $table->uuid('uuid')     
```
for example:
```
php artisan crud:generate name --i=level,health,mana --s=name,surname --u   
```
will generate this:
```
$table->uuid('uuid');
$table->integer("level");
$table->integer("health");
$table->integer("mana");
$table->string("name");
$table->string("surname");
```

