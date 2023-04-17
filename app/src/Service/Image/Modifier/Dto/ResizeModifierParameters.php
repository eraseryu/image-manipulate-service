<?php

namespace App\Service\Image\Modifier\Dto;

class ResizeModifierParameters implements ModifierParameters
{
    public readonly int $width;
    public readonly int $height;

    public function __construct(array $args)
    {
        $this->width = $args[0];
        $this->height = $args[1];
    }
}
