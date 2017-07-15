<?php

namespace ColbyGatte\Chunky;

interface ConstraintInterface
{
    /**
     * Called before the test is ran.
     *
     * @param \ColbyGatte\Chunky\Chunks $chunks
     *
     * @return void
     */
    public function setChunks(Chunks $chunks);
    
    /**
     * @param \ColbyGatte\Chunky\Chunk $chunk
     *
     * @return bool
     */
    public function passesTest(Chunk $chunk);
}