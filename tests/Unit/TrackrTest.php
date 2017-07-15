<?php

namespace ColbyGatte\Chunky\Tests\Unit;

use ColbyGatte\Chunky\Tests\TestCase;
use ColbyGatte\Chunky\Tests\TestHelpers\SignUpsChunkyDirectory;
use ColbyGatte\Chunky\Tests\TestHelpers\TestWriteChunkyDirectory;
use ColbyGatte\Chunky\TrackrRules;
use Faker\Factory as FakerFactory;

class TrackrTest extends TestCase
{
    protected $faker;
    
    public function testing_yo()
    {
        $chunkyDirectory = new TestWriteChunkyDirectory();
        
        $writer = $chunkyDirectory->newLogFile();
        
        for ($i = 0; $i < 1000; $i++) {
            $e = $writer->writeChunk(
                $this->faker->uuid,
                [
                    'color' => $this->faker->colorName,
                    'priority' => $this->faker->numberBetween(0, 10),
                    'email' => $this->faker->email,
                    'city' => $this->faker->city,
                    'country' => $this->faker->country,
                ]
            );
        }
        
        $count = 0;
        
        $fh = fopen($chunkyDirectory->getLatestFile(), 'r');
        
        while (false !== ($row = fgetcsv($fh))) {
            $count++;
        }
        
        $this->assertEquals(1000, $count);
    }
    
    /** @test */
    public function can_get_latest()
    {
        $trackr = new SignUpsChunkyDirectory;
        
        $this->assertEquals('1500077646', $trackr->getLatestTime());
    }
    
    protected function setUp()
    {
        parent::setUp();
        
        $this->faker = FakerFactory::create();
    }
    
    protected function tearDown()
    {
        parent::tearDown();
    }
}