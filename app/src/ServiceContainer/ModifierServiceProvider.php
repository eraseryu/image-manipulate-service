<?php

namespace App\ServiceContainer;

use App\Service\Image\Pipeline\ModifierPipelineFactory;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ModifierServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container[ModifierPipelineFactory::class] = fn(Container $container) => new ModifierPipelineFactory();
    }
}
