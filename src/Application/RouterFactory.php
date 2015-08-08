<?php

namespace Phpg\Application;

use Phalcon\Mvc\Router;

class RouterFactory
{
    /**
     * @param array $routes
     * @return Router
     */
    public static function createFrom(array $routes)
    {
        $router = new Router(false);
        $router->setUriSource(Router::URI_SOURCE_SERVER_REQUEST_URI);
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
