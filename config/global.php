<?php

return [
    'dispatcher' => [
        'controllerDefaultNamespace' => 'Phpg\Application\Controller',
        'taskDefaultNamespace'       => 'Phpg\Application\Task',
        'errorForwarding'            => [
            'controller'     => 'error',
            'notFoundAction' => 'notFound',
            'errorAction'    => 'fatal',
        ],
    ],
    'view'       => [
        'templatePath' => './view/',
    ],
    'routes'     => [
        'default' => [
            'pattern' => '/:controller/:action',
            'paths'   => [
                'controller' => 1,
                'action'     => 2,
            ],
        ],
        'admin'   => array(
            'pattern'     => '/admin/:controller/:action',
            'paths'       => array(
                'namespace'  => 'Phpg\Application\Controller\Admin',
                'controller' => 1,
                'action'     => 2,
            ),
            'httpMethods' => null,
        ),
    ],
    'services'   => [
        'logger' => 'Phpg\Application\Service\Logger',
    ],
    'loggers'    => [
        'file' => [
            'adapter' => 'Phalcon\Logger\Adapter\File',
            'name'    => './data/log/error.log',
        ],
    ],
];
