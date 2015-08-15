<?php

return array(
    'routes'   => array(
        'default' => array(
            'pattern'     => '/:controller/:action',
            'paths'       => array(
                'controller' => 1,
                'action'     => 2,
            ),
            'httpMethods' => null,
        ),
        'admin'   => array(
            'pattern'     => '/admin/:controller/:action',
            'paths'       => array(
                'namespace'  => 'Phpg\Application\Controller\Admin',
                'controller' => 1,
                'action'     => 2,
            ),
            'httpMethods' => null,
        ),
    ),
    'services' => array(
        'logger' => 'Phpg\Application\Service\Logger',
    ),
    'loggers'  => array(
        'file' => array(
            'adapter' => 'Phalcon\Logger\Adapter\File',
            'name'    => './data/log/error.log',
        ),
    ),
);
