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
    'db' => [
        'driver' => 'Pdo',
        'adapters' => [
            mysqlAdapter::class => [
                'driver' => 'Pdo',
                'dsn' => 'mysql:dbname=qldangky_w2_oss_02;host=localhost;charset=utf8',
                'username' => 'root',
                'password' => 'root'
            ],
        ],
    ],
];