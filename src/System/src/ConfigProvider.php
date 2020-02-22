<?php
declare(strict_types=1);

namespace System;


use Zend\ProblemDetails\ProblemDetailsMiddleware as VendorProblemDetailsMiddleware;

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
                // Vendor middleware
                VendorProblemDetailsMiddleware::class => Middleware\ProblemDetailsMiddlewareFactory::class,
            ],
        ];
    }
}
