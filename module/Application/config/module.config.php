<?php

declare(strict_types=1);

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\Db\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\Session;
use Application\Util;
use Application\Helper;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'lopHoc' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/lophoc[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'monHoc' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/monhoc[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\MonHocController::class,
                        'action' => 'index',
                    ],
                ]
            ],
            'hocVien' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/hocvien[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\HocVienController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'dangKy' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/dangky[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\DangKyController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'application' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],

            'register' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/register',
                    'defaults' => [
                        'controller' => Controller\RegisterController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'login' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/login[/:action]',
                    'defaults' => [
                        'controller' => Controller\LoginController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/logout',
                    'defaults' => [
                        'controller' => Controller\LogoutController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            Model\LopTable::class => function ($container) {
                $tableGateway = $container->get(LopTableGateway::class);
                return new Model\LopTable($tableGateway);
            },
            LopTableGateway::class => function ($container) {
                $dbAdapter = $container->get(AdapterInterface::class);
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Model\LopEntity());
                return new TableGateway('lophoc', $dbAdapter, null, $resultSetPrototype);
            },
            MonHocTableGateway::class => function ($container) {
                $dbAdapter = $container->get(AdapterInterface::class);
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Model\MonHocEntity());
                return new TableGateway('monhoc', $dbAdapter, null, $resultSetPrototype);
            },
            Model\MonHocTable::class => function ($sm) {
                $tableGateway = $sm->get(MonHocTableGateway::class);
                return new Model\MonHocTable($tableGateway);
            },
            HocVienTableGateway::class => function ($container) {
                $dbAdapter = $container->get(AdapterInterface::class);
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Model\HocVienEntity());
                return new TableGateway('sinhvien', $dbAdapter, null, $resultSetPrototype);
            },
            Model\HocVienTable::class => function ($container) {
                $tableGateway = $container->get(HocVienTableGateway::class);
                return new Model\HocVienTable($tableGateway);
            },
            DangKyTableGateway::class => function ($container) {
                $dbAdapter = $container->get(AdapterInterface::class);
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Model\DangKyEntity());
                return new TableGateway('dkhoc', $dbAdapter, null, $resultSetPrototype);
            },
            Model\DangKyTable::class => function ($container) {
                $tableGateway = $container->get(DangKyTableGateway::class);
                return new Model\DangKyTable($tableGateway);
            },

            // Authentication
            UserTableGateway::class => function ($container) {
                $dbAdapter = $container->get(AdapterInterface::class);
                $resultSetPrototype = new ResultSet();

                //pass base url via cnstructor to the User class
                $resultSetPrototype->setArrayObjectPrototype(new Model\UserEntity());
                return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
            },
            Model\UserTable::class => function ($container) {
                $tableGateway = $container->get(UserTableGateway::class);
                $table = new Model\UserTable($tableGateway);

                return $table;
            },
            Util\Authentication::class => function ($container) {
                $auth = new Util\Authentication(
                    $container->get(Adapter\Adapter::class)
                );
                return $auth;
            },
            Helper\Password::class => InvokableFactory::class,

            SessionManager::class => function ($container) {
                $config = $container->get('config');
                $session = $config['session'];
                $sessionConfig = new $session['config']['class']();
                $sessionConfig->setOptions($session['config']['options']);
                $sessionManager = new Session\SessionManager(
                    $sessionConfig,
                    new $session['storage'](),
                    null
                );
                Session\Container::setDefaultManager($sessionManager);

                return $sessionManager;
            },
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => function ($container) {
                return new Controller\IndexController(
                    $container->get(Model\LopTable::class),
                    $container->get(Model\MonHocTable::class)
                );
            },
            Controller\MonHocController::class => function ($container) {
                return new Controller\MonHocController(
                    $container->get(Model\MonHocTable::class)
                );
            },
            Controller\HocVienController::class => function ($container) {
                return new Controller\HocVienController(
                    $container->get(Model\HocVienTable::class)
                );
            },
            Controller\DangKyController::class => function ($container) {
                return new Controller\DangKyController(
                    $container->get(Model\DangKyTable::class)
                );
            },
            Controller\LoginController::class => function ($sm) {
                return new Controller\LoginController(
                    $sm->get(Util\Authentication::class)
                );
            },
            Controller\RegisterController::class => function ($container) {
                return new Controller\RegisterController(
                    $container->get(Model\UserTable::class),
                    $container->get(Util\Authentication::class),
                    $container->get(Helper\Password::class)
                );
            },
            Controller\LoginController::class => function ($container) {
                return new Controller\LoginController(
                    $container->get(Util\Authentication::class)
                );
            },
            Controller\LogoutController::class => InvokableFactory::class,
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
