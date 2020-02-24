<?php
declare(strict_types=1);

namespace Api;


use Api\Handler\Attendance\AttendanceAddHandler;
use Api\Handler\Attendance\AttendanceListHandler;
use Api\Handler\Course\CourseAddHandler;
use Api\Handler\Course\CourseListHandler;
use Api\Handler\Department\DepartmentAddHandler;
use Api\Handler\Ping;
use Api\Handler\Program\ProgramAddHandler;
use Api\Handler\Department\DepartmentListHandler;
use Api\Handler\Program\ProgramListHandler;
use Api\Handler\School\SchoolAddHandler;
use Api\Handler\School\SchoolListHandler;
use Api\Handler\Student\StudentAddHandler;
use Api\Handler\Student\StudentListHandler;
use System\AbstractFactory\ReflectionBasedAbstractFactory;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    protected function getDependencies(): array
    {
        return [
            'factories' => [
                Ping::class => ReflectionBasedAbstractFactory::class,

                // Student
                StudentListHandler::class => ReflectionBasedAbstractFactory::class,
                StudentAddHandler::class => ReflectionBasedAbstractFactory::class,

                // Department
                DepartmentListHandler::class => ReflectionBasedAbstractFactory::class,
                DepartmentAddHandler::class => ReflectionBasedAbstractFactory::class,

                // Program
                ProgramListHandler::class => ReflectionBasedAbstractFactory::class,
                ProgramAddHandler::class => ReflectionBasedAbstractFactory::class,

                // Course
                CourseListHandler::class => ReflectionBasedAbstractFactory::class,
                CourseAddHandler::class => ReflectionBasedAbstractFactory::class,

                // Attendance
                AttendanceListHandler::class => ReflectionBasedAbstractFactory::class,
                AttendanceAddHandler::class => ReflectionBasedAbstractFactory::class,

                // School
                SchoolListHandler::class => ReflectionBasedAbstractFactory::class,
                SchoolAddHandler::class => ReflectionBasedAbstractFactory::class,
            ],
            'params' => [],
        ];
    }
}
