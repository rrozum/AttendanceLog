<?php
declare(strict_types=1);

namespace Api;


use Api\Handler\Ping;
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
            ],
            'params' => [],
        ];
    }
}
