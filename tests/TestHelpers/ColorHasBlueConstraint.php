<?php

namespace ColbyGatte\Chunky\Tests\TestHelpers;

use ColbyGatte\Chunky\ConstraintInterface;
use ColbyGatte\Chunky\Page;
use ColbyGatte\Chunky\Entry;

class ColorHasBlueConstraint implements ConstraintInterface
{
    public function passesTest(Entry $chunk)
    {
        $result = (
            ($color = $chunk->getTag('color')) && preg_match('/blue/i', $color)
        );
        
        return $result;
    }
    
    public function setPage(Page $chunks)
    {
    }
}