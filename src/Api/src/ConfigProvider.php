<?php
declare(strict_types=1);

namespace Api;


use Api\Handler\Ping;
use Api\Handler\Student\ListHandler;
use Api\Handler\StudentAdd;
use Api\Handler\StudentList;
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
                ListHandler::class => ReflectionBasedAbstractFactory::class,
            ],
            'params' => [],
        ];
    }
}
