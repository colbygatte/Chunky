<?php

namespace ColbyGatte\Chunky\Tests\TestHelpers;

use ColbyGatte\Chunky\ChunkyDirectory;

class TestWriteChunkyDirectory extends ChunkyDirectory
{
    /**
     * Location of the directory.
     *
     * @return string
     */
    function directoryLocation()
    {
        return __DIR__.'/../data';
    }
}