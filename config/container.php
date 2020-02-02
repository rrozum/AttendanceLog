<?php

declare(strict_types=1);

use Zend\ServiceManager\ServiceManager;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Events\Dispatcher;

// Load configuration
$config = require __DIR__ . '/config.php';

$dependencies = $config['dependencies'];
$dependencies['services']['config'] = $config;

// Build container
$container = new ServiceManager($dependencies);

$capsule = new Manager();
$capsule->addConnection($config['database']['log_app'], 'log_app');
$capsule->getDatabaseManager()->setDefaultConnection('log_app');
$capsule->setEventDispatcher(new Dispatcher());
$capsule->bootEloquent();
$container->setService(Manager::class, $capsule);

return $container;
