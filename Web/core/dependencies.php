<?php

$container = $app->getContainer();

// db
$container['db'] = function($c) {
    return new Medoo\Medoo([
        'database_type' => 'mysql',
        'database_name' => getenv('DB_NAME'),
               'server' => getenv('DB_HOST'),
             'username' => getenv('DB_USERNAME'),
             'password' => getenv('DB_PASSWORD'),
              'charset' => 'utf8',
    ]);
};

// view renderer
$container['template'] = function ($c) {
    if (getenv('DEBUG') == 'TRUE') {
        $loader = new Twig_Loader_Filesystem('../app/Template');
        $debug  = true;
    } else {
        $loader = new Twig_Loader_Filesystem('../app/View');
        $debug  = false;
    }

    return new Twig_Environment($loader, array(
                   'debug' => $debug,
                   'cache' => CACHPATH,
             'auto_reload' => true,
        'strict_variables' => $debug
    ));
};