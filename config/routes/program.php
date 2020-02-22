<?php
declare(strict_types=1);

/** @var \Zend\Expressive\Application $app */

$app->get(
    '/program/list',
    \Api\Handler\Program\ProgramListHandler::class,
    'program.list'
);
