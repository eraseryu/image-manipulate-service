<?php

namespace App\Service\Image\Modifier;

use App\Service\Image\Image;
use App\Service\Image\Modifier\Dto\ResizeModifierParameters;

class ResizeModifier implements Modifier
{
    public function __construct(protected readonly ResizeModifierParameters $modifierParameters)
    {
    }

    public function process(Image $image): Image
    {
        $parameters = $this->modifierParameters;

        $imagick = new \Imagick();
        $imagick->readImageBlob($image->getBlob());

        $imagick->scaleImage($parameters->width, $parameters->height);
        $blob = $imagick->getImageBlob();
        $imagick->destroy();

        return $image->setBlob($blob);
    }

    public static function getParametersFqn(): string
    {
        return ResizeModifierParameters::class;
    }
}
