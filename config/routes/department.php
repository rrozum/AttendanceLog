<?php
declare(strict_types=1);

/** @var \Zend\Expressive\Application $app */

$app->get(
    '/department/list',
    \Api\Handler\Department\DepartmentListHandler::class,
    'department.list'
);

$app->post(
    '/department/add',
    \Api\Handler\Department\DepartmentAddHandler::class,
    'department.add'
);
