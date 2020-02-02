<?php
declare(strict_types=1);

/** @var \Zend\Expressive\Application $app */

$app->post(
    '/student/add',
    \Api\Handler\StudentAdd::class,
    'student.add'
);

$app->get(
    '/student/list',
    \Api\Handler\StudentList::class,
    'student.list'
);