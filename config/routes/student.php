<?php
declare(strict_types=1);

/** @var \Zend\Expressive\Application $app */

$app->get(
    '/student/list',
    \Api\Handler\Student\StudentListHandler::class,
    'student.list'
);

$app->post(
    '/student/add',
    \Api\Handler\Student\StudentAddHandler::class,
    'student.add'
);
