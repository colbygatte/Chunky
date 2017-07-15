<?php

namespace ColbyGatte\Chunky\Tests\Unit;

use ColbyGatte\Chunky\Search;
use ColbyGatte\Chunky\Tests\TestCase;
use ColbyGatte\Chunky\Tests\TestHelpers\ColorHasBlueConstraint;
use ColbyGatte\Chunky\Tests\TestHelpers\SignUpsChunkyDirectory;
use ColbyGatte\Chunky\Tests\TestHelpers\BlueChunkSearch;

class ConstraintTest extends TestCase
{
    /** @test */
    public function can_use_constraint()
    {
        $entries = (new SignUpsChunkyDirectory())->loadAllChunks();
        
        $entries = (new BlueChunkSearch())->searchOn($entries);
        
        $this->assertCount(454, $entries->getChunks());
    }
    
    protected function tearDown()
    {
    }
}