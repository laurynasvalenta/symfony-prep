<?php

declare(strict_types=1);

namespace App\Service\Loader;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RoutesLoader extends Loader
{
    public function load(mixed $resource, ?string $type = null): mixed
    {
        $routes = new RouteCollection();

        $path = '/topic4/custom-loaded-route';
        $defaults = [
            '_controller' => 'App\Controller\Routing\RoutingController::customLoadedRoute',
        ];
        $route = new Route($path, $defaults);

        $routes->add('customLoadedRoute', $route);

        return $routes;
    }

    public function supports(mixed $resource, ?string $type = null): bool
    {
        return 'custom_loader' === $type;
    }
}
