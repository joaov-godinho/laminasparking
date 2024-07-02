<?php

declare(strict_types=1);

namespace Application;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterServiceFactory;
use Application\Controller\Factory\IndexControllerFactory;

class Module
{
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Adapter::class => AdapterServiceFactory::class,
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\IndexController::class => IndexControllerFactory::class,
            ],
        ];
    }
}
