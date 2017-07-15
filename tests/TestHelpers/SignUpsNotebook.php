<?php

namespace ColbyGatte\Chunky\Tests\TestHelpers;

use ColbyGatte\Chunky\Notebook;

class SignUpsNotebook extends Notebook
{
    /**
     * @return string Location of the directory.
     */
    function directoryLocation()
    {
        return realpath(__DIR__.'/../test-data');
    }
}