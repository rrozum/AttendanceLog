<?php
declare(strict_types=1);

/** @var \Zend\Expressive\Application $app */

$app->any(
    '/ping',
    \Api\Handler\Ping::class,
    'ping'
);
