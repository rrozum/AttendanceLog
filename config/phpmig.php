<?php
use Phpmig\Adapter;
use Pimple\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

$config = require __DIR__ . '/config.php';
$container = new Container();

$container['config'] = $config;

$container['db'] = function ($c) {
    $capsule = new Capsule();
    $capsule->addConnection($c['config']['database']['log_app'], 'log_app');
    $capsule->getDatabaseManager()->setDefaultConnection('log_app');
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

$container['phpmig.adapter'] = function($c) {
    return new Adapter\Illuminate\Database($c['db'], 'migrations');
};
$container['phpmig.migrations_path'] = __DIR__ . '/../migrations';

return $container;
