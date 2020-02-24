<?php
declare(strict_types=1);

/** @var \Zend\Expressive\Application $app */

$app->get(
    '/attendance/list',
    \Api\Handler\Attendance\AttendanceListHandler::class,
    'attendance.list'
);

$app->post(
    '/attendance/add',
    \Api\Handler\Attendance\AttendanceAddHandler::class,
    'attendance.add'
);
