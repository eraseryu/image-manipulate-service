<?php

namespace App\Service\Image\Beautifier;

use App\Common\RandomDataGenerator;
use App\Service\Image\Image;

class DashImageNameConverter implements ImageNameConverter
{
    private const SLUG_PART_DELIMITER = '-';

    public function __construct(private readonly RandomDataGenerator $randomDataGenerator)
    {
    }

    public function convertToBeautified(Image $image, string $modifiersParamsString): string
    {
        $imageName = strtolower($image->getName());
        $basename = pathinfo($imageName, PATHINFO_FILENAME);
        $extensions = pathinfo($imageName, PATHINFO_EXTENSION);
        $slug = $basename . self::SLUG_PART_DELIMITER .
            $this->randomDataGenerator->generateIdByString($image->getName() . $modifiersParamsString . $image->getSizeInBytes()) .
            ".$extensions";

        return $slug;
    }
}
