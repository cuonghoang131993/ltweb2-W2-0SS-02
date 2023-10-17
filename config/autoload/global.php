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
    // ...
    'db' => [
        'driver'   => 'Pdo_Mysql',
        'database' => 'qldangky_w2_oss_02',
        'username' => 'root',
        'password' => '',
        'hostname' => '127.0.0.1'
    ],
    // 'db' => [
    //     'driver'   => 'Pdo_Mysql',
    //     'database' => 'if0_35248443_qldangky_w2_oss_02',
    //     'username' => 'if0_34646631',
    //     'password' => 'GuJec0E8rnb',
    //     'hostname' => 'sql305.infinityfree.com'
    // ],
    'session' => [
        'config' => [
            'class' => \Laminas\Session\Config\SessionConfig::class,
            'options' => [
                'name' => 'session_name',
            ],
        ],
        'storage' => \Laminas\Session\Storage\SessionArrayStorage::class,
        'validators' => [
            \Laminas\Session\Validator\RemoteAddr::class,
            \Laminas\Session\Validator\HttpUserAgent::class,
        ]
    ],
];
