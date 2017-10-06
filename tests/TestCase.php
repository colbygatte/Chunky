<?php

namespace ColbyGatte\Chunky\Tests;

use Faker\Factory as FakerFactory;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected $faker;

    protected function setUp()
    {
        parent::setUp();

        $this->faker = FakerFactory::create();
    }

    protected function tearDown()
    {
        $removeFiles = __DIR__.'/data/*';

        exec("rm -rf $removeFiles");

        parent::tearDown();
    }
}