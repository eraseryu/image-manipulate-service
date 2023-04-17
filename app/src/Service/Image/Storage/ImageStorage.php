<?php

namespace App\Service\Image\Storage;

use App\Service\Image\Exception\StorageException;
use App\Service\Image\Image;

interface ImageStorage
{
    /**
     * @throws StorageException
     */
    public function put(Image $image): void;

    public function get(string $imageName): ?Image;
}
