<?php

namespace App\Service\Image\Pipeline;

use App\Service\Image\Exception\PipelineException;
use App\Service\Image\Modifier\CropModifier;
use App\Service\Image\Modifier\Modifier;
use App\Service\Image\Modifier\ResizeModifier;

class ModifierPipelineFactory
{
    public static array $modifiersFqnMap = [
        'resize' => ResizeModifier::class,
        'crop' => CropModifier::class,
    ];

    /**
     * @throws PipelineException
     */
    public function createByModifiersParamsString(string $modifiersParamsString): Pipeline
    {
        $matches = [];
        preg_match_all('/(\w+)\/([\d,]+)/', $modifiersParamsString, $matches, PREG_SET_ORDER);

        $pipeline = new Pipeline();
        foreach ($matches as $modifiersParamsArray) {
            $modifierName = $modifiersParamsArray[1];
            $modifierArgs = $modifiersParamsArray[2];
            if (!array_key_exists($modifierName, static::$modifiersFqnMap)) {
                throw new PipelineException();
            }
            $modifierClassFqn = static::$modifiersFqnMap[$modifierName];

            $modifierArgs = explode(',', $modifierArgs);
            $modifierParametersFqn = $modifierClassFqn::getParametersFqn();
            $modifierParameters = new $modifierParametersFqn($modifierArgs);
            /** @var Modifier $modifier */
            $modifier = new $modifierClassFqn($modifierParameters);
            $pipeline->addModifier($modifier);
        }

        return $pipeline;
    }
}
