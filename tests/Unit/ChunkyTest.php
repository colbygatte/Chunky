<?php

namespace ColbyGatte\Chunky\Tests\Unit;

use ColbyGatte\Chunky\Tests\TestCase;
use ColbyGatte\Chunky\Tests\TestHelpers\SignUpsNotebook;
use ColbyGatte\Chunky\Tests\TestHelpers\TestWriteNotebook;
use ColbyGatte\Chunky\TrackrRules;

class ChunkyTest extends TestCase
{
    public function test_write_notebook()
    {
        $notebook = new TestWriteNotebook();

        $page = $notebook->newPage();

        // move this
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
        $notebook = new SignUpsNotebook;

        $this->assertEquals('1500077646', $notebook->getLatestPageTimestamp());
    }

    /**  */
    public function can_get_all_chunks_as_key()
    {
        $page = (new SignUpsNotebook)->loadLatestPage();

        dump($page->getAllChunksAsKey());
    }

    /** @test */
    public function can_get_unique_chunk_count()
    {
        $notebook = new SignUpsNotebook;

        $page = $notebook->loadAllEntries();

        $originalCount = count($page);

        // Add a chunk that already exists
        $page->addEntry(
            $notebook->newEntry()->setChunk('43be5057-ef13-314a-9365-f604c3bee9d2')
        );

        $this->assertCount(
            $originalCount,
            $page
        );

        // Add a chunk that doesn't exist
        $page->addEntry(
            $notebook->newEntry()->setChunk('new chunk for you!')
        );

        $this->assertCount(
            $originalCount + 1,
            $page
        );
    }
}