<?php

namespace App\Service\Image\Pipeline;

use App\Service\Image\Image;
use App\Service\Image\Modifier\Modifier;

class Pipeline
{
    /**
     * @var array<Modifier>
     */
    private array $modifiers = [];

    public function process(Image $image): Image
    {
        foreach ($this->modifiers as $modifier) {
            $image = $modifier->process($image);
        }

        return $image;
    }

    public function addModifier(Modifier $modifier): self
    {
        $this->modifiers[] = $modifier;

        return $this;
    }

    public function getModifiers(): array
    {
        return $this->modifiers;
    }
}
