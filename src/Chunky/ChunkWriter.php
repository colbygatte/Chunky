<?php

namespace ColbyGatte\Chunky;

class ChunkWriter
{
    protected $fileHandle;
    
    /**
     * @var \ColbyGatte\Chunky\ChunkyDirectory
     */
    protected $chunkyDirectory;
    
    protected $timestamp;
    
    function __construct()
    {
        $this->timestamp = time();
    }
    
    public function setChunkyDirectory(ChunkyDirectory $chunkyDirectory)
    {
        $this->chunkyDirectory = $chunkyDirectory;
        
        return $this;
    }
    
    public function setFileHandle($fileHandle)
    {
        $this->fileHandle = $fileHandle;
        
        return $this;
    }
    
    public function writeChunk($chunk, $tags)
    {
        $chunk = $this->chunkyDirectory->newChunk()
            ->setTimestamp($this->timestamp)
            ->setChunk($chunk)
            ->setTag($tags);
        
        fputcsv($this->fileHandle, $chunk->toArray());
        
        return $this;
    }
}