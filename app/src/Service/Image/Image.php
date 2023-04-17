<?php

namespace App\Service\Image;

class Image
{
    private ?string $blob = null;

    public function __construct(private readonly string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSizeInBytes(): int
    {
        return strlen($this->blob);
    }

    public function getMime(): string
    {
        $imageSize = getimagesizefromstring($this->blob);

        return $imageSize['mime'];
    }

    public function getBlob(): ?string
    {
        return $this->blob;
    }

    public function setBlob(?string $blob): self
    {
        $this->blob = $blob;

        return $this;
    }

    public function getWidth(): ?int
    {
        if (!$this->blob) {
            return null;
        }

        $imageInfo = imagecreatefromstring($this->getBlob());

        return imagesx($imageInfo);
    }

    public function getHeight(): ?int
    {
        if (!$this->blob) {
            return null;
        }

        $imageInfo = imagecreatefromstring($this->getBlob());

        return imagesy($imageInfo);
    }
}
