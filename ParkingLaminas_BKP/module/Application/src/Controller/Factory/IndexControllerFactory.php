<?php

namespace Application\Controller\Factory;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Controller\IndexController;
use Laminas\Db\Adapter\Adapter;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter = $container->get(Adapter::class);
        return new IndexController($dbAdapter);
    }
}
