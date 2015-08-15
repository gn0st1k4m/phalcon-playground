<?php

return [
    'routes'   => [
        'default' => [
            'pattern'     => '/:controller/:action',
            'paths'       => [
                'controller' => 1,
                'action'     => 2,
            ],
            'httpMethods' => null,
        ],
        'admin'   => [
            'pattern'     => '/admin/:controller/:action',
            'paths'       => [
                'namespace'  => 'Phpg\Application\Controller\Admin',
                'controller' => 1,
                'action'     => 2,
            ],
            'httpMethods' => null,
        ],
    ],
    'services' => [
        'logger' => 'Phpg\Application\Service\Logger',
    ],
    'loggers'  => [
        'file' => [
            'adapter' => 'Phalcon\Logger\Adapter\File',
            'name'    => './data/log/error.log',
        ],
    ],
];
