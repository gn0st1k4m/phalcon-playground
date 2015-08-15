<?php

namespace Phpg\Application\Factory;

use Phalcon\Mvc\Router as MvcRouter;

class Router
{
    /**
     * @param array $routes
     * @return MvcRouter
     */
    public static function createFrom(array $routes)
    {
        $router = new MvcRouter(false);
        $router->setUriSource(MvcRouter::URI_SOURCE_SERVER_REQUEST_URI);

        foreach ($routes as $route) {
            $router->add(
                $route['pattern'],
                isset($route['paths']) ? $route['paths'] : null,
                isset($route['httpMethods']) ? $route['httpMethods'] : null
            );
        }

        return $router;
    }
}
