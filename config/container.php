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
$capsule->addConnection($config['database']['attendance'], 'attendance');
$capsule->getDatabaseManager()->setDefaultConnection('attendance');
$capsule->setEventDispatcher(new Dispatcher());
$capsule->bootEloquent();
$container->setService(Manager::class, $capsule);

return $container;
