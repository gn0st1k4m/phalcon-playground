<?php

return array(
    'routes'    => array(
        array(
            'pattern'     => '/:controller/:action',
            'paths'       => array(
                'controller' => 1,
                'action'     => 2,
            ),
            'httpMethods' => null,
        ),
        array(
            'pattern'     => '/admin/:controller/:action',
            'paths'       => array(
                'namespace'  => 'Phpg\Application\Controller\Admin',
                'controller' => 1,
                'action'     => 2,
            ),
            'httpMethods' => null,
        ),
    ),
    'injectors' => array(
        'Phpg\Application\Injector\Logger',
    ),
    'loggers'   => array(
        array(
            'adapter' => 'Phalcon\Logger\Adapter\File',
            'name'    => './data/log/error.log',
        ),
    ),
);
