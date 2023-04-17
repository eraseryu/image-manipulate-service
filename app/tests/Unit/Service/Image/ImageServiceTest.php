<?php

namespace App\Tests\Unit\Service\Image;

use App\Service\Image\Beautifier\ImageNameConverter;
use App\Service\Image\Exception\ImageException;
use App\Service\Image\Exception\ImageNotFoundException;
use App\Service\Image\Exception\PipelineException;
use App\Service\Image\Exception\StorageException;
use App\Service\Image\Image;
use App\Service\Image\ImageService;
use App\Service\Image\Pipeline\ModifierPipelineFactory;
use App\Service\Image\Pipeline\Pipeline;
use App\Service\Image\Storage\ImageStorage;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class ImageServiceTest extends TestCase
{
    use ProphecyTrait;

    private readonly ImageService $service;
    private readonly ImageStorage|ObjectProphecy $imageStorage;
    private readonly ImageNameConverter|ObjectProphecy $imageNameConverter;
    private readonly ModifierPipelineFactory|ObjectProphecy $modifierPipelineFactory;
    private readonly Pipeline|ObjectProphecy $pipeline;

    public function setUp(): void
    {
        $this->modifierPipelineFactory = $this->prophesize(ModifierPipelineFactory::class);
        $this->imageStorage = $this->prophesize(ImageStorage::class);
        $this->imageNameConverter = $this->prophesize(ImageNameConverter::class);
        $this->service = new ImageService(
            $this->modifierPipelineFactory->reveal(),
            $this->imageStorage->reveal(),
            $this->imageNameConverter->reveal(),
        );

        $this->pipeline = $this->prophesize(Pipeline::class);
    }

    public function testProcessAndGetBeautifiedName_pipelineException_exception()
    {
        $imageName = 'test.jpg';
        $modifiersParamsString = 'crop/10,10,200,200/resize/50,50';

        $beautifiedName = 'beautified-dummy.jpg';
        $image = new Image($imageName);
        $image->setBlob('qwerty123');


        $this->imageNameConverter->convertToBeautified($image, $modifiersParamsString)
            ->shouldBeCalledOnce()
            ->willReturn($beautifiedName);

        $this->imageStorage->get($beautifiedName)
            ->shouldBeCalledOnce()
            ->willReturn(null);

        $this->imageStorage->get($imageName)
            ->shouldBeCalledOnce()
            ->willReturn($image);

        $this->modifierPipelineFactory->createByModifiersParamsString($modifiersParamsString)
            ->shouldBeCalledOnce()
            ->willThrow(PipelineException::class);

        $this->imageStorage->put(Argument::type(Image::class))->shouldNotBeCalled();

        $this->expectException(ImageException::class);
        $this->service->processAndGetBeautifiedName($imageName, $modifiersParamsString);
    }

    public function testProcessAndGetBeautifiedName_alreadyProcessedImage_image()
    {
        $imageName = 'test.jpg';
        $modifiersParamsString = 'crop/10,10,200,200/resize/50,50';

        $image = new Image($imageName);
        $image->setBlob('qwerty123');

        $beautifiedName = 'beautified-dummy.jpg';
        $alreadyProcessedImage = new Image($beautifiedName);

        $this->imageStorage->get($imageName)->shouldBeCalledOnce()
            ->willReturn($image);
        $this->imageStorage->get($beautifiedName)->shouldBeCalledOnce()
            ->willReturn($alreadyProcessedImage);
        $this->imageStorage->put(Argument::type(Image::class))->shouldNotBeCalled();

        $this->imageNameConverter->convertToBeautified($image, $modifiersParamsString)
            ->shouldBeCalledOnce()
            ->willReturn($beautifiedName);


        $assertingBeautifiedName = $this->service->processAndGetBeautifiedName($imageName, $modifiersParamsString);
        $this->assertSame($beautifiedName, $assertingBeautifiedName);
    }

    public function testProcessAndGetBeautifiedName_storageException_exception()
    {
        $imageName = 'test.jpg';
        $modifiersParamsString = 'crop/10,10,200,200/resize/50,50';

        $image = new Image($imageName);
        $image->setBlob('qwerty123');

        $this->modifierPipelineFactory->createByModifiersParamsString($modifiersParamsString)
            ->shouldBeCalledOnce()
            ->willReturn($this->pipeline);

        $beautifiedName = 'beautified-dummy.jpg';
        $this->imageNameConverter->convertToBeautified($image, $modifiersParamsString)
            ->shouldBeCalledOnce()
            ->willReturn($beautifiedName);
        $this->imageStorage->get($beautifiedName)->shouldBeCalledOnce()
            ->willReturn(null);

        $this->imageStorage->get($imageName)
            ->shouldBeCalledOnce()
            ->willReturn($image);
        $processedImage = new Image($imageName);
        $this->pipeline->process($image)
            ->shouldBeCalledOnce()
            ->willReturn($processedImage);
        $this->imageStorage->put(Argument::type(Image::class))->shouldBeCalledOnce()
            ->willThrow(StorageException::class);

        $this->expectException(ImageException::class);
        $this->service->processAndGetBeautifiedName($imageName, $modifiersParamsString);
    }

    public function testProcessAndGetBeautifiedName_complete_beautifiedName()
    {
        $imageName = 'test.jpg';
        $modifiersParamsString = 'crop/10,10,200,200/resize/50,50';

        $image = new Image($imageName);
        $image->setBlob('qwerty123');

        $this->modifierPipelineFactory->createByModifiersParamsString($modifiersParamsString)
            ->shouldBeCalledOnce()
            ->willReturn($this->pipeline);

        $beautifiedName = 'beautified-dummy.jpg';
        $this->imageNameConverter->convertToBeautified($image, $modifiersParamsString)
            ->shouldBeCalledOnce()
            ->willReturn($beautifiedName);
        $this->imageStorage->get($beautifiedName)->shouldBeCalledOnce()
            ->willReturn(null);

        $this->imageStorage->get($imageName)
            ->shouldBeCalledOnce()
            ->willReturn($image);
        $processedImage = new Image($imageName);
        $this->pipeline->process($image)
            ->shouldBeCalledOnce()
            ->willReturn($processedImage);
        $this->imageStorage->put(Argument::type(Image::class))->shouldBeCalledOnce();

        $assertingBeautifiedName = $this->service->processAndGetBeautifiedName($imageName, $modifiersParamsString);
        $this->assertSame($beautifiedName, $assertingBeautifiedName);
    }
}
