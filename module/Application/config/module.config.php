<?php

declare(strict_types=1);

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

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
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
            'application/login/index' => __DIR__ . '/../view/application/login/index.phtml',
            'application/lophoc/index' => __DIR__ . '/../view/application/index/index.phtml',
            'application/lophoc/edit' => __DIR__ . '/../view/application/index/edit.phtml',
            'application/lophoc/add' => __DIR__ . '/../view/application/index/add.phtml',
            'application/lophoc/delete' => __DIR__ . '/../view/application/index/delete.phtml',
            'application/hocvien/index' => __DIR__ . '/../view/application/hoc-vien/index.phtml',
            'application/hocvien/edit' => __DIR__ . '/../view/application/hoc-vien/edit.phtml',
            'application/hocvien/add' => __DIR__ . '/../view/application/hoc-vien/add.phtml',
            'application/hocvien/delete' => __DIR__ . '/../view/application/hoc-vien/delete.phtml',
            'application/dangky/index' => __DIR__ . '/../view/application/dang-ky/index.phtml',
            'application/dangky/edit' => __DIR__ . '/../view/application/dang-ky/edit.phtml',
            'application/dangky/add' => __DIR__ . '/../view/application/dang-ky/add.phtml',
            'application/dangky/delete' => __DIR__ . '/../view/application/dang-ky/delete.phtml',
            'application/monhoc/index' => __DIR__ . '/../view/application/mon-hoc/index.phtml',
            'application/monhoc/edit' => __DIR__ . '/../view/application/mon-hoc/edit.phtml',
            'application/monhoc/add' => __DIR__ . '/../view/application/mon-hoc/add.phtml',
            'application/monhoc/delete' => __DIR__ . '/../view/application/mon-hoc/delete.phtml'
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'factories' => [
            SessionManager::class
        ],
    ],
];