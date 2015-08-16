<?php

return [
    'routes'   => [
        'default' => [
            'pattern'     => '/:controller/:action',
            'paths'       => [
                'controller' => 1,
                'action'     => 2,
            ],
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
