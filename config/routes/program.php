<?php
declare(strict_types=1);

/** @var \Zend\Expressive\Application $app */

$app->get(
    '/program/list',
    \Api\Handler\Program\ProgramListHandler::class,
    'program.list'
);

$app->post(
    '/program/add',
    \Api\Handler\Program\ProgramAddHandler::class,
    'program.add'
);
