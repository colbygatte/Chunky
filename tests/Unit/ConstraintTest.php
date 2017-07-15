<?php

namespace ColbyGatte\Chunky\Tests\Unit;

use ColbyGatte\Chunky\Search;
use ColbyGatte\Chunky\Tests\TestCase;
use ColbyGatte\Chunky\Tests\TestHelpers\ColorHasBlueConstraint;
use ColbyGatte\Chunky\Tests\TestHelpers\SignUpsNotebook;
use ColbyGatte\Chunky\Tests\TestHelpers\BlueChunkSearch;

class ConstraintTest extends TestCase
{
    /** @test */
    public function can_use_constraint()
    {
        $entries = (new SignUpsNotebook)->loadAllEntries();
        
        $entries = (new BlueChunkSearch)->searchOn($entries);
        
        $this->assertCount(454, $entries->getEntries());
    }
    
    protected function tearDown()
    {
    }
}