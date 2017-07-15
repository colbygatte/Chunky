<?php

namespace ColbyGatte\Chunky;

class Chunks
{
    /**
     * @var \ColbyGatte\Chunky\ChunkyDirectory
     */
    protected $chunkyDirectory;
    
    /**
     * @var
     */
    protected $fileHandle;
    
    /**
     * @var \ColbyGatte\Chunky\Chunk[]
     */
    protected $chunks = [];
    
    protected $timestamp;
    
    /**
     * Entries constructor.
     *
     * @param \ColbyGatte\Chunky\ChunkyDirectory $chunkyDirectory
     */
    public function __construct(ChunkyDirectory $chunkyDirectory)
    {
        $this->chunkyDirectory = $chunkyDirectory;
    }
    
    /**
     * @param mixed $timestamp
     *
     * @return $this
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        
        return $this;
    }
    
    public function getChunk($chunk)
    {
        return isset($this->chunks[$chunk])
            ? $this->chunks[$chunk]
            : false;
    }
    
    public function setFileHandle($fileHandle)
    {
        $this->fileHandle = $fileHandle;
        
        return $this;
    }
    
    public function getAllUsedTags()
    {
    }
    
    public function getChunkyDirectory()
    {
        return $this->chunkyDirectory;
    }
    
    public function getChunks()
    {
        return $this->chunks;
    }
    
    public function addNewChunk($data)
    {
        $chunk = $this->makeChunk($data);
        
        $this->addChunk($chunk);
        
        return $chunk;
    }
    
    public function whereTagEqual($tag, $value = null)
    {
        $tag = is_string($tag) ? [$tag => $value] : $tag;
        
        $result = $this->chunkyDirectory->newChunks();
        
        foreach ($this->chunks as $chunk) {
            
            foreach ($tag as $_tag => $_value) {
                if ($chunk->isTagEqual($_tag, $_value)) {
                    continue 2;
                }
            }
            
            $result->addChunk($chunk);
        }
        
        return $result;
    }
    
    /**
     * @param $data
     *
     * @return \ColbyGatte\Chunky\Chunk
     */
    public function makeChunk($data = [])
    {
        return (new Chunk)->set($data)->setTimestamp($this->timestamp);
    }
    
    public function addChunk(Chunk $chunk)
    {
        $this->chunks[] = $chunk;
    }
    
    public function writeChunk($chunk)
    {
        if (is_array($chunk)) {
            $chunk = $this->makeChunk($chunk);
        }
        
        $this->addChunk($chunk);
        
        if (! $this->fileHandle) {
            throw new Exception("Error writing chunk");
        }
        
        fputcsv($this->fileHandle, $chunk->toArray());
    }
}