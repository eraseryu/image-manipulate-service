<?php

namespace App\Service\Image;

use App\Service\Image\Beautifier\ImageNameConverter;
use App\Service\Image\Exception\ImageException;
use App\Service\Image\Exception\ImageNotFoundException;
use App\Service\Image\Exception\PipelineException;
use App\Service\Image\Exception\StorageException;
use App\Service\Image\Pipeline\ModifierPipelineFactory;
use App\Service\Image\Storage\ImageStorage;

class ImageService
{
    public function __construct(
        private readonly ModifierPipelineFactory $modifierPipelineFactory,
        private readonly ImageStorage $imageStorage,
        private readonly ImageNameConverter $imageNameConverter,
    ) {
    }

    /**
     * @throws ImageException|ImageNotFoundException
     */
    public function processAndGetBeautifiedName(
        string $imageName,
        string $modifiersParamsString
    ): string {

        $image = $this->imageStorage->get($imageName);
        if (!$image) {
            throw new ImageNotFoundException();
        }

        $beautifiedName = $this->imageNameConverter->convertToBeautified($image, $modifiersParamsString);
        $alreadyProcessedImage = $this->imageStorage->get($beautifiedName);
        if ($alreadyProcessedImage) {
            return $beautifiedName;
        }

        try {
            $pipeline = $this->modifierPipelineFactory->createByModifiersParamsString($modifiersParamsString);
        } catch (PipelineException) {
            throw new ImageException();
        }
        $processedImage = $pipeline->process($image);
        $newImageToStore = (new Image($beautifiedName))->setBlob($processedImage->getBlob());
        try {
            $this->imageStorage->put($newImageToStore);
        } catch (StorageException) {
            throw new ImageException();
        }

        return $beautifiedName;
    }

    /**
     * @throws ImageNotFoundException
     */
    public function getBySlug(string $imageName): Image
    {
        $image = $this->imageStorage->get($imageName);
        if (!$image) {
            throw new ImageNotFoundException;
        }

        return $image;
    }
}
