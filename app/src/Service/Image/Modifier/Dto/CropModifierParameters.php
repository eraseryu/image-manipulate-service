<?php

namespace App\Service\Image\Modifier\Dto;

class CropModifierParameters implements ModifierParameters
{
    public readonly int $x;
    public readonly int $y;
    public readonly int $width;
    public readonly int $height;

    public function __construct(array $args)
    {
        $this->x = $args[0];
        $this->y = $args[1];
        $this->width = $args[2];
        $this->height = $args[3];
    }
}
