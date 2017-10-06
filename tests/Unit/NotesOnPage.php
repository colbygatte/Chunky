<?php

namespace ColbyGatte\Chunky\Tests\Unit;

use ColbyGatte\Chunky\Tests\TestCase;
use ColbyGatte\Chunky\Tests\TestHelpers\SignUpsNotebook;
use ColbyGatte\Chunky\Tests\TestHelpers\TestWriteNotebook;

class NotesOnPage extends TestCase
{
    /** @test */
    public function can_read_notebook()
    {
        $this->assertEquals(
            ['Hello World!', 'FOO', 'BAR', 'BAZ'],
            (new SignUpsNotebook)->loadPage(1500077642)->loadNotes()
        );
    }

    /** @test */
    public function can_write_notes_on_page()
    {
        $notebook = new TestWriteNotebook;

        $page = $notebook->newPage();
        $page->addNote('hello');
        $page->addNote('hi');
        $page->writeNotes();

        $this->assertEquals(
            $notebook->loadPage($page->getTimestamp())->loadNotes(),
            ['hello', 'hi']
        );
    }
}