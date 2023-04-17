<?php

namespace App\Service\Image\Modifier;

use App\Service\Image\Image;

interface Modifier
{
    public function process(Image $image): Image;

    public static function getParametersFqn(): string;
}
