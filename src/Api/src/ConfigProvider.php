<?php
declare(strict_types=1);

namespace Api;


use Api\Handler\Department\DepartmentAddHandler;
use Api\Handler\Ping;
use Api\Handler\Program\ProgramAddHandler;
use Api\Handler\Department\DepartmentListHandler;
use Api\Handler\Program\ProgramListHandler;
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

                // Department
                DepartmentListHandler::class => ReflectionBasedAbstractFactory::class,
                DepartmentAddHandler::class => ReflectionBasedAbstractFactory::class,

                // Program
                ProgramListHandler::class => ReflectionBasedAbstractFactory::class,
                ProgramAddHandler::class => ReflectionBasedAbstractFactory::class,
            ],
            'params' => [],
        ];
    }
}
