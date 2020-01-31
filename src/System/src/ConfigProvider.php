<?php
declare(strict_types=1);

namespace System;


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
            ],
        ];
    }
}
