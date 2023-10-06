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
    /**
     * Database Adapter(s)
     */
    'service_manager' => [
        'factories' => [
            /**
             * Adapter One - this factory will use the default 'db' connection
             */
            'Laminas\Db\Adapter\Adapter' => 'Laminas\Db\Adapter\AdapterServiceFactory',
            /**
             * Adapter Two - use the second connection
             */
            'Application\Db\AdapterTwo' => function ($sm) {
                $config = $sm->get('Config');
                return new \Laminas\Db\Adapter\Adapter($config['db_two']);
            },
        ],
    ],
];