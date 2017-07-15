<?php

namespace ColbyGatte\Chunky\Tests\TestHelpers;

use ColbyGatte\Chunky\Search;

class BlueChunkSearch extends Search
{
    protected $constraints = [
        ColorHasBlueConstraint::class
    ];
}