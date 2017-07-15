<?php

namespace ColbyGatte\Chunky\Tests\TestHelpers;

use ColbyGatte\Chunky\Notebook;

class TestWriteNotebook extends Notebook
{
    /**
     * Location of the directory.
     *
     * @return string
     */
    function directoryLocation()
    {
        return realpath(__DIR__.'/../data');
    }
}