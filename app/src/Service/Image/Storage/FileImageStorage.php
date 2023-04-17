<?php

namespace App\Service\Image\Storage;

use App\Service\Image\Exception\StorageException;
use App\Service\Image\Image;

class FileImageStorage implements ImageStorage
{
    private const IMAGE_DIR = '/app/data/images/';

    /**
     * @inheritDoc
     */
    public function put(Image $image): void
    {
        $fileFullPath = self::IMAGE_DIR . $image->getName();
        $result = file_put_contents($fileFullPath, $image->getBlob());

        if (!$result) {
            throw new StorageException();
        }
    }

    public function get(string $imageName): ?Image
    {
        $fileFullPath = self::IMAGE_DIR . $imageName;

        $imageBlob = @file_get_contents($fileFullPath);
        if (!$imageBlob) {
            return null;
        }

        return (new Image($imageName))->setBlob($imageBlob);
    }
}
