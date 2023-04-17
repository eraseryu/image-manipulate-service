<?php

namespace App\ServiceContainer;

use App\Common\RandomDataGenerator;
use App\Service\Image\Beautifier\DashImageNameConverter;
use App\Service\Image\Beautifier\ImageNameConverter;
use App\Service\Image\ImageService;
use App\Service\Image\Pipeline\ModifierPipelineFactory;
use App\Service\Image\Storage\FileImageStorage;
use App\Service\Image\Storage\ImageStorage;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ImageServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        /** @var RandomDataGenerator $randomDataGenerator */
        $randomDataGenerator = $container[RandomDataGenerator::class];

        $imageNameConverter = new DashImageNameConverter($randomDataGenerator);
        $container[ImageNameConverter::class] = fn(Container $container) => $imageNameConverter;

        $imageStorage = new FileImageStorage();
        $container[ImageStorage::class] = fn(Container $container) => $imageStorage;

        /** @var ModifierPipelineFactory $modifierPipelineFactory */
        $modifierPipelineFactory = $container[ModifierPipelineFactory::class];
        /** @var ImageStorage $imageStorage */
        $imageStorage = $container[ImageStorage::class];
        /** @var ImageNameConverter $imageNameConverter */
        $imageNameConverter = $container[ImageNameConverter::class];

        $imageService = new ImageService(
            $modifierPipelineFactory,
            $imageStorage,
            $imageNameConverter,
        );

        $container[ImageService::class] = fn(Container $container) => $imageService;
    }
}
