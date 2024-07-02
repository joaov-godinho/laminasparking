<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return [
    'db' => [
        'driver' => 'Pdo_Mysql',
        'database' => 'db_parkinglaminas',
        'username' => 'root',
        'password' => 'admin',
        'hostname' => 'localhost',
    ],
    'session' => [
        'config' => [
            'class' => Laminas\Session\Config\SessionConfig::class,
            'options' => [
                'name' => 'myapp',
                'cookie_lifetime' => 60*60*1, // 1 hora
                'gc_maxlifetime' => 60*60*24*30, // 30 dias
            ],
        ],
        'storage' => Laminas\Session\Storage\SessionArrayStorage::class,
        'validators' => [
            Laminas\Session\Validator\RemoteAddr::class,
            Laminas\Session\Validator\HttpUserAgent::class,
        ],
    ],
];
