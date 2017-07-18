<?php

namespace ColbyGatte\Chunky\Tests\Unit;

use ColbyGatte\Chunky\Tests\TestCase;
use ColbyGatte\Chunky\Tests\TestHelpers\SignUpsNotebook;
use ColbyGatte\Chunky\Tests\TestHelpers\TestWriteNotebook;
use ColbyGatte\Chunky\TrackrRules;

class ChunkyTest extends TestCase
{
    public function testing_yo()
    {
        $notebook = new TestWriteNotebook();
        
        $page = $notebook->newPage();
        
        for ($i = 0; $i < 1000; $i++) {
            $entry = $page->makeEntry(
                $this->faker->uuid,
                [
                    'color' => $this->faker->colorName,
                    'priority' => $this->faker->numberBetween(0, 10),
                    'email' => $this->faker->email,
                    'city' => $this->faker->city,
                    'country' => $this->faker->country,
                ]
            );
            
            $page->writeEntry($entry);
        }
        
        $count = 0;
        
        $fh = fopen($notebook->getLatestPageFile(), 'r');
        
        while (false !== ($row = fgetcsv($fh))) {
            $count++;
        }
        
        $this->assertEquals(1000, $count);
    }
    
    /** @test */
    public function can_get_latest()
    {
        $trackr = new SignUpsNotebook;
        
        $this->assertEquals('1500077646', $trackr->getLatestPageTimestamp());
    }
    
    /** @test */
    public function can_get_all_chunks_as_key()
    {
        $notebook = new SignUpsNotebook;
        
        $page = $notebook->loadLatestPage();
        
        dump($page->getAllChunksAsKey());
    }
}