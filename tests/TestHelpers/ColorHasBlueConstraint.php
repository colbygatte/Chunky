<?php

namespace ColbyGatte\Chunky\Tests\TestHelpers;

use ColbyGatte\Chunky\ConstraintInterface;
use ColbyGatte\Chunky\Page;
use ColbyGatte\Chunky\Entry;

class ColorHasBlueConstraint extends ConstraintInterface
{
    public function passesTest()
    {
        return $this->tagPregMatches('color', '/blue/i');;
    }
}