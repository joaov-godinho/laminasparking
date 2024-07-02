<?php

namespace Application\Factory;

use Laminas\Db\Adapter\Adapter;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class AdapterServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $dbParams = $config['db'];

        return new Adapter([
            'driver' => 'Pdo_Mysql',
            'database' => $dbParams['database'],
            'username' => $dbParams['password'],
            'password' => $dbParams['password'],
            'hostname' => $dbParams['hostname'],
        ]);
    }
}
