<?php

namespace ColbyGatte\Chunky;

interface ConstraintInterface
{
    /**
     * Called before the test is ran.
     *
     * @param \ColbyGatte\Chunky\Page $chunks
     *
     * @return void
     */
    public function setPage(Page $chunks);
    
    /**
     * @param \ColbyGatte\Chunky\Entry $chunk
     *
     * @return bool
     */
    public function passesTest(Entry $chunk);
}