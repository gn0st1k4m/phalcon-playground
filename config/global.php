<?php

return array(
    'application' => array(
        'routes'     => array(
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
        'dispatcher' => array(
            'defaultNamespace' => 'Phpg\Application\Controller',
        ),
        'viewsDir'   => './view/',
    ),
);
