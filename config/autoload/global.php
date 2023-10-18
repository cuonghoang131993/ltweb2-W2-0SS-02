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
    //     'database' => 'if0_35251498_qldangky_w2_oss_02',
    //     'username' => 'if0_35251498',
    //     'password' => 'atRC24bDdRA7hjR',
    //     'hostname' => 'sql208.infinityfree.com'
    // ],
    'session' => [
        'config' => [
            'class' => \Laminas\Session\Config\SessionConfig::class,
            'options' => [
                'name' => 'session_w2_oss_02',
                'cookie_httponly'=> true,
                'cookie_lifetime' => 300
            ],
        ],
        'storage' => \Laminas\Session\Storage\SessionArrayStorage::class,
        'validators' => [
            \Laminas\Session\Validator\RemoteAddr::class,
            \Laminas\Session\Validator\HttpUserAgent::class,
        ]
    ],
];
