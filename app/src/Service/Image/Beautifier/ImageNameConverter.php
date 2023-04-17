<?php

namespace App\Service\Image\Beautifier;

use App\Service\Image\Image;

interface ImageNameConverter
{
    public function convertToBeautified(Image $image, string $modifiersParamsString): string;
}
