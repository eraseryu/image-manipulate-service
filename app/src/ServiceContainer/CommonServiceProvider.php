<?php

namespace App\ServiceContainer;

use App\Common\RandomDataGenerator;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class CommonServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container[RandomDataGenerator::class] = fn(Container $container) => new RandomDataGenerator();
    }
}
