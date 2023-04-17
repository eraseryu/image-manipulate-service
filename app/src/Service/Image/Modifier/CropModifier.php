<?php

namespace App\Service\Image\Modifier;

use App\Service\Image\Image;
use App\Service\Image\Modifier\Dto\CropModifierParameters;

class CropModifier implements Modifier
{
    public function __construct(protected readonly CropModifierParameters $modifierParameters)
    {
    }

    public function process(Image $image): Image
    {
        $imagick = new \Imagick();
        $imagick->readImageBlob($image->getBlob());

        $parameters = $this->modifierParameters;
        $imagick->cropImage($parameters->width, $parameters->height, $parameters->x, $parameters->y);

        $blob = $imagick->getImageBlob();
        $imagick->destroy();

        return $image->setBlob($blob);
    }

    public static function getParametersFqn(): string
    {
        return CropModifierParameters::class;
    }
}
