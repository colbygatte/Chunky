<?php

namespace ColbyGatte\Chunky;

class Chunks
{
    /**
     * @var \ColbyGatte\Chunky\ChunkyDirectory
     */
    protected $chunkyDirectory;
    
    /**
     * @var \ColbyGatte\Chunky\Entry[]
     */
    protected $chunks = [];
    
    /**
     * Timestamp of the
     * @var int
     */
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
    
    /**
     * Search for all chunks equal to $chunk
     *
     * @param $chunk
     *
     * @return bool|\ColbyGatte\Chunky\Entry
     */
    public function searchForChunk($chunk)
    {
        return isset($this->chunks[$chunk])
            ? $this->chunks[$chunk]
            : false;
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
        $allChunks = [];
        
        foreach ($this->chunks as $chunksOfSameChunk) {
            array_push($allChunks, ...$chunksOfSameChunk);
        }
        
        return $allChunks;
    }
    
    public function addNewChunk($data = [])
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
     * @return \ColbyGatte\Chunky\Entry
     */
    public function makeChunk($data = [])
    {
        return $this->chunkyDirectory->newChunk()
            ->set($data)
            ->setTimestamp($this->timestamp);
    }
    
    public function addChunk(Entry $chunk)
    {
        if (! isset($this->chunks[$chunk->getChunk()])) {
            $this->chunks[$chunk->getChunk()] = [];
        }
        
        $this->chunks[$chunk->getChunk()][] = $chunk;
    }
}