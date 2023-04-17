<?php

namespace App\ServiceContainer;

use Pimple\Container;

class AppServiceProvider extends Container
{
    private array $providers = [
        CommonServiceProvider::class,
        ModifierServiceProvider::class,
        ImageServiceProvider::class,
        HttpServiceProvider::class,
        RouterServiceProvider::class,
    ];

    public function registerAndGetContainer(): self
    {
        foreach ($this->providers as $provider) {
            $this->register(new $provider());
        }

        return $this;
    }
}
