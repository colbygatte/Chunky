<?php

namespace ColbyGatte\Chunky\Tests\Unit;

use ColbyGatte\Chunky\Tests\TestCase;
use ColbyGatte\Chunky\Tests\TestHelpers\BlueChunkSearch;
use ColbyGatte\Chunky\Tests\TestHelpers\SignUpsNotebook;

class ConstraintTest extends TestCase
{
    /** @test */
    public function can_use_constraint()
    {
        $resultEntries = (new BlueChunkSearch)->searchOn(
            (new SignUpsNotebook)->loadAllEntries()
        );

        $this->assertCount(41, $resultEntries->getEntries());
    }

    /** @test */
    public function search_reports_are_accurate()
    {
        $pageResult = (new BlueChunkSearch)->searchOn(
            (new SignUpsNotebook)->loadAllEntries()
        );

        $this->assertEquals(
            [
                [
                    'shortName' => "ColbyGatte\Chunky\Tests\TestHelpers\ColorHasBlueConstraint",
                    'constraint' => "ColbyGatte\Chunky\Tests\TestHelpers\ColorHasBlueConstraint"
                ],
                [
                    'shortName' => "priority",
                    'constraint' => "ColbyGatte\Chunky\Tests\TestHelpers\PriorityIsFiveConstraint"
                ]
            ],
            $pageResult->getEntries()[0]->latestSearchReport()->getPassedConstraints()
        );
    }
}