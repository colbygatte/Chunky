<?php

namespace ColbyGatte\Chunky\Tests\TestHelpers;

use ColbyGatte\Chunky\ConstraintInterface;

class ColorHasBlueConstraint extends ConstraintInterface
{
    public function passesTest()
    {
        return $this->tagPregMatches('color', '/blue/i');;
    }
}