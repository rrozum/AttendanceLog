<?php
declare(strict_types=1);

/** @var \Zend\Expressive\Application $app */

$app->get(
    '/school/list',
    \Api\Handler\School\SchoolListHandler::class,
    'school.list'
);

$app->post(
    '/school/add',
    \Api\Handler\School\SchoolAddHandler::class,
    'school.add'
);
