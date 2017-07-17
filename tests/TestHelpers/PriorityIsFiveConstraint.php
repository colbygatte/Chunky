<?php

namespace ColbyGatte\Chunky\Tests\TestHelpers;

use ColbyGatte\Chunky\ConstraintInterface;

class PriorityIsFiveConstraint extends ConstraintInterface
{
    /**
     * @param \ColbyGatte\Chunky\Entry $chunk
     *
     * @return bool
     */
    public function passesTest()
    {
        return $this->tagIsEqual('priority', 5);
    }
    
    public function shortName()
    {
        return 'priority';
    }
}