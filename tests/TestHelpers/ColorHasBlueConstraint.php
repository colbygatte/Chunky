<?php

namespace ColbyGatte\Chunky\Tests\TestHelpers;

use ColbyGatte\Chunky\ConstraintInterface;
use ColbyGatte\Chunky\Chunks;
use ColbyGatte\Chunky\Chunk;

class ColorHasBlueConstraint implements ConstraintInterface
{
    public function passesTest(Chunk $chunk)
    {
        $result = (
            ($color = $chunk->getTag('color')) && preg_match('/blue/i', $color)
        );
        
        return $result;
    }
    
    public function setChunks(Chunks $chunks)
    {
    }
}