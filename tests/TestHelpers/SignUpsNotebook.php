<?php

namespace ColbyGatte\Chunky\Tests\TestHelpers;

use ColbyGatte\Chunky\ChunkyDirectory;

class SignUpsChunkyDirectory extends ChunkyDirectory
{
    /**
     * @return string Location of the directory.
     */
    function directoryLocation()
    {
        return __DIR__.'/../test-data';
    }
}