<?php

namespace ColbyGatte\Chunky\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function tearDown()
    {
        $removeFiles = __DIR__ .'/data/*';
    
        exec("rm -rf $removeFiles");
    
        parent::tearDown();
    }
}