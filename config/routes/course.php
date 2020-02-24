<?php
declare(strict_types=1);

/** @var \Zend\Expressive\Application $app */

$app->get(
    '/course/list',
    \Api\Handler\Course\CourseListHandler::class,
    'course.list'
);

$app->post(
    '/course/add',
    \Api\Handler\Course\CourseAddHandler::class,
    'course.add'
);
