<?php

namespace App\ServiceContainer;

use App\Http\Controller\ImageController;
use App\Http\Template\TemplateBuilder;
use App\Service\Image\ImageService;
use Pecee\SimpleRouter\SimpleRouter;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class HttpServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container[TemplateBuilder::class] = fn(Container $container) => new TemplateBuilder();
        $container[SimpleRouter::class] = fn(Container $container) => new SimpleRouter();

        /** @var ImageService $imageService */
        $imageService = $container[ImageService::class];

        /** @var TemplateBuilder $templateBuilder */
        $templateBuilder = $container[TemplateBuilder::class];

        /** @var SimpleRouter $simpleRouter */
        $simpleRouter = $container[SimpleRouter::class];

        $container[ImageController::class] = fn(Container $container) => new ImageController(
            $imageService,
            $templateBuilder,
            $simpleRouter
        );
    }
}
