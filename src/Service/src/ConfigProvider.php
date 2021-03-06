<?php
declare(strict_types=1);

namespace Service;

use Service\Student\ListService;
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
                ListService::class => ReflectionBasedAbstractFactory::class,
            ],
        ];
    }
}
