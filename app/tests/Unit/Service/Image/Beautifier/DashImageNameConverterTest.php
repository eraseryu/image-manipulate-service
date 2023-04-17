<?php

namespace App\Tests\Unit\Service\Image\Beautifier;

use App\Common\RandomDataGenerator;
use App\Service\Image\Beautifier\DashImageNameConverter;
use App\Service\Image\Image;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class DashImageNameConverterTest extends TestCase
{
    use ProphecyTrait;

    private readonly DashImageNameConverter $service;
    private readonly RandomDataGenerator|ObjectProphecy $randomDataGenerator;

    public function setUp(): void
    {
        $this->randomDataGenerator = $this->prophesize(RandomDataGenerator::class);
        $this->service = new DashImageNameConverter(
            $this->randomDataGenerator->reveal(),
        );
    }

    /**
     * @dataProvider input
     */
    public function testConvertToBeautified(array $input, string $referencingName = null)
    {
        /** @var Image $image */
        $image = $input[0];
        $this->randomDataGenerator->generateIdByString($image->getName() . $input[1] . $image->getSizeInBytes())
            ->shouldBeCalledOnce()
            ->willReturn('dummyRandomString');
        $assertingName = $this->service->convertToBeautified($image, $input[1]);

        $this->assertSame($referencingName, $assertingName);
    }

    public function input(): array
    {
        $image1 = new Image('test.png');
        $image1->setBlob('qwerty123');

        $image2 = new Image('sample.jpg');
        $image2->setBlob('qwerty123');

        return [
            [[$image1, 'resize/10,200'], 'test-dummyRandomString.png'],
            [[$image2,'resize/10,10/crop/10,10,100,200'], 'sample-dummyRandomString.jpg'],
        ];
    }
}
