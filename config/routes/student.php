<?php
declare(strict_types=1);

/** @var \Zend\Expressive\Application $app */

$app->get(
    '/student/list',
    \Api\Handler\Student\ListHandler::class,
    'student.list'
);
