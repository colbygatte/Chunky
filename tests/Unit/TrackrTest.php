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
        $trackr = new TestWriteChunkyDirectory();
        
        $entries = $trackr->newLogFile()->setTimestamp(time());
        
        $time = time();
        
        for ($i = 0; $i < 1000; $i++) {
            $e = $entries->makeChunk()
                ->setChunk($this->faker->uuid)
                ->setTag([
                    'color' => $this->faker->colorName,
                    'priority' => $this->faker->numberBetween(0, 10),
                    'email' => $this->faker->email,
                    'city' => $this->faker->city,
                    'country' => $this->faker->country,
                ]);
            
            $entries->writeChunk($e);
        }
        
        $count = 0;
        
        $fh = fopen($entries->getChunkyDirectory()->getLatestFile(), 'r');
        
        while(false !== ($row = fgetcsv($fh))) {
            $count++;
        }
        
        $this->assertEquals(1001, $count);
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