<?php

namespace App\ServiceContainer;

use App\Http\Controller\ImageController;
use App\Http\Router\RoutesConfiguration;
use Pecee\SimpleRouter\SimpleRouter;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class RouterServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        /** @var SimpleRouter $router */
        $router = $container[SimpleRouter::class];
        /** @var ImageController $imageController */
        $imageController = $container[ImageController::class];

        $container[RoutesConfiguration::class] = fn(Container $container) => new RoutesConfiguration(
            $imageController,
            $router
        );
    }
}
