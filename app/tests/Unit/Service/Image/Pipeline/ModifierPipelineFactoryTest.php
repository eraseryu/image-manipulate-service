<?php

namespace App\Tests\Unit\Service\Image\Pipeline;

use App\Service\Image\Exception\PipelineException;
use App\Service\Image\Modifier\CropModifier;
use App\Service\Image\Modifier\ResizeModifier;
use App\Service\Image\Pipeline\ModifierPipelineFactory;
use App\Service\Image\Pipeline\Pipeline;
use PHPUnit\Framework\TestCase;

class ModifierPipelineFactoryTest extends TestCase
{

    public function testCreateByArguments()
    {
        $factory = new ModifierPipelineFactory();
        $modifiersParamsString = 'crop/10,10,200,200/resize/50,50';
        $pipeline = $factory->createByModifiersParamsString($modifiersParamsString);

        $this->assertInstanceOf(Pipeline::class, $pipeline);

        $modifiers = $pipeline->getModifiers();
        $this->assertCount(2, $modifiers);

        $cropModifier = $modifiers[0];
        $this->assertInstanceOf(CropModifier::class, $cropModifier);

        $resizeModifier = $modifiers[1];
        $this->assertInstanceOf(ResizeModifier::class, $resizeModifier);

    }

    public function testCreateByArguments_invalidModifier_exception()
    {
        $modifiersParamsString = 'invalid/10,10,200,200/incorrect/50,50';
        $factory = new ModifierPipelineFactory();
        $this->expectException(PipelineException::class);
        $factory->createByModifiersParamsString($modifiersParamsString);
    }
}
