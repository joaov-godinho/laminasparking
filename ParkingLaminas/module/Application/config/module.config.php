<?php

declare(strict_types=1);

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\Db\Adapter\Adapter;
use Application\Controller\IndexController;
use Application\Controller\Factory\IndexControllerFactory;
use Application\Factory\AdapterServiceFactory;


return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/application/parking',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'register' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/register',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'register',
                    ],
                ],
            ],
            'login' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/login',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
            'user-panel' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/user-panel',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action'     => 'userPanel',
                    ],
                ],
            ],
            'logout' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
            'reserva' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/reserva',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'reserva',
                    ],
                ],
            ],
            'admin-panel' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/admin',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'adminPanel',
                    ],
                ],
            ],
            'admin-edit-user' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/admin/edit-user/:id',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'editUser',
                    ],
                    'constraints' => [
                        'id' => '\d+', // Apenas números inteiros como ID
                    ],
                ],
            ],
            'admin-delete-user' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/admin/delete-user/:id',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'deleteUser',
                    ],
                    'constraints' => [
                        'id' => '\d+', // Apenas números inteiros como ID
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            Adapter::class => AdapterServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
            Controller\IndexController::class => IndexControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
